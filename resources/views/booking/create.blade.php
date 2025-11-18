<x-cinema-layout>
    <main>
        <section class="buying">
            <div class="buying__info">
                <div class="buying__info-description">
                    <h2 class="buying__info-title">{{ $session->movie->title ?? 'Фильм' }}</h2>
                    <p class="buying__info-start">Начало сеанса: {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</p>
                    <p class="buying__info-hall">{{ $session->hall->name ?? 'Не указан' }}</p>
                </div>
                <div class="buying__info-hint">
                    <p>Тапните дважды,<br>чтобы увеличить</p>
                </div>
            </div>

            @if($errors->any())
                <div style="padding: 1.5rem; background-color: rgba(241, 235, 230, 0.95); margin-bottom: 1rem; color: #DE2121;">
                    <ul style="list-style: none; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('booking.store') }}" id="booking-form">
                @csrf
                <input type="hidden" name="session_id" value="{{ $session->id }}">
                <input type="hidden" name="selected_seats" id="selected_seats" value="[]">

                <div class="buying-scheme">
                    <div class="buying-scheme__wrapper">
                        @for($row = 1; $row <= ($session->hall->rows ?? 8); $row++)
                            <div class="buying-scheme__row">
                                @for($seat = 1; $seat <= ($session->hall->seats_per_row ?? 10); $seat++)
                                    @php
                                        $seatModel = $seats->first(function($s) use ($row, $seat) {
                                            return $s->row == $row && $s->number == $seat;
                                        });
                                        $isBooked = $seatModel && $session->tickets->contains('seat_id', $seatModel->id);
                                        $seatType = $seatModel->type ?? 'regular';
                                    @endphp
                                    <span class="buying-scheme__chair 
                                        @if($isBooked)
                                            buying-scheme__chair_taken
                                        @elseif($seatType === 'VIP')
                                            buying-scheme__chair_vip
                                        @else
                                            buying-scheme__chair_standart
                                        @endif
                                        seat-btn"
                                        data-seat-id="{{ $seatModel->id ?? '' }}"
                                        data-row="{{ $row }}"
                                        data-number="{{ $seat }}"
                                        data-type="{{ $seatType }}"
                                        @if(!$isBooked) style="cursor: pointer;" @endif>
                                    </span>
                                @endfor
                            </div>
                        @endfor
                    </div>

                    <div class="buying-scheme__legend">
                        <div class="col">
                            <p class="buying-scheme__legend-price">
                                <span class="buying-scheme__chair buying-scheme__chair_standart"></span> 
                                Свободно (<span class="buying-scheme__legend-value">{{ $regularPrice ?? 500 }}</span>руб)
                            </p>
                            <p class="buying-scheme__legend-price">
                                <span class="buying-scheme__chair buying-scheme__chair_vip"></span> 
                                Свободно VIP (<span class="buying-scheme__legend-value">{{ $vipPrice ?? 1000 }}</span>руб)
                            </p>
                        </div>
                        <div class="col">
                            <p class="buying-scheme__legend-price">
                                <span class="buying-scheme__chair buying-scheme__chair_taken"></span> 
                                Занято
                            </p>
                            <p class="buying-scheme__legend-price">
                                <span class="buying-scheme__chair buying-scheme__chair_selected"></span> 
                                Выбрано
                            </p>
                        </div>
                    </div>
                </div>

                <div id="selected-info" style="display: none; padding: 1.5rem; background-color: rgba(241, 235, 230, 0.95); margin-top: 2rem;">
                    <h3 style="font-weight: 700; font-size: 1.6rem; margin-bottom: 1rem;">Выбранные места:</h3>
                    <div id="selected-seats-list" style="font-size: 1.4rem; margin-bottom: 1rem;"></div>
                    <div style="font-weight: 700; font-size: 1.8rem;">
                        Итого: <span id="total-price">0</span> ₽
                    </div>
                </div>

                <button type="submit" 
                        id="submit-btn"
                        disabled
                        class="acceptin-button"
                        style="margin-top: 2rem;">
                    Забронировать
                </button>
            </form>
        </section>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedSeats = new Set();
            const regularPrice = {{ $regularPrice ?? 500 }};
            const vipPrice = {{ $vipPrice ?? 1000 }};

            document.querySelectorAll('.seat-btn').forEach(btn => {
                const isBooked = btn.classList.contains('buying-scheme__chair_taken');
                if (!isBooked) {
                    btn.addEventListener('click', function() {
                        const seatId = this.dataset.seatId;
                        const row = this.dataset.row;
                        const number = this.dataset.number;
                        const type = this.dataset.type;
                        
                        if (!seatId) {
                            console.error('Seat ID is missing');
                            return;
                        }
                        
                        const key = `${seatId}-${row}-${number}`;

                        if (selectedSeats.has(key)) {
                            selectedSeats.delete(key);
                            // Убираем класс выбранного места
                            this.classList.remove('buying-scheme__chair_selected');
                            // Возвращаем исходный класс
                            if (type === 'VIP') {
                                this.classList.add('buying-scheme__chair_vip');
                            } else {
                                this.classList.add('buying-scheme__chair_standart');
                            }
                        } else {
                            selectedSeats.add(key);
                            // Убираем исходные классы
                            this.classList.remove('buying-scheme__chair_vip', 'buying-scheme__chair_standart');
                            // Добавляем класс выбранного места
                            this.classList.add('buying-scheme__chair_selected');
                        }

                        updateSelectedInfo();
                    });
                }
            });

            function updateSelectedInfo() {
                const infoDiv = document.getElementById('selected-info');
                const listDiv = document.getElementById('selected-seats-list');
                const totalPriceSpan = document.getElementById('total-price');
                const submitBtn = document.getElementById('submit-btn');
                const selectedSeatsInput = document.getElementById('selected_seats');

                if (selectedSeats.size === 0) {
                    infoDiv.style.display = 'none';
                    submitBtn.disabled = true;
                    selectedSeatsInput.value = '[]';
                    return;
                }

                infoDiv.style.display = 'block';
                submitBtn.disabled = false;

                let total = 0;
                let seatsList = [];

                selectedSeats.forEach(key => {
                    const [seatId, row, number] = key.split('-');
                    const btn = document.querySelector(`[data-seat-id="${seatId}"][data-row="${row}"][data-number="${number}"]`);
                    if (!btn) {
                        console.error('Button not found for key:', key);
                        return;
                    }
                    const type = btn.dataset.type;
                    const price = type === 'VIP' ? vipPrice : regularPrice;
                    total += price;
                    seatsList.push({
                        seat_id: seatId,
                        row: row,
                        number: number,
                        type: type,
                        price: price
                    });
                });

                listDiv.innerHTML = seatsList.map(seat => 
                    `Ряд ${seat.row}, Место ${seat.number} (${seat.type === 'VIP' ? 'VIP' : 'Обычное'}) - ${seat.price} ₽`
                ).join('<br>');

                totalPriceSpan.textContent = total;
                selectedSeatsInput.value = JSON.stringify(seatsList);
            }
        });
    </script>
    @endpush
</x-cinema-layout>
