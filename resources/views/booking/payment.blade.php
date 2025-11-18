<x-cinema-layout>
    <main>
        <section class="ticket">
            <header class="tichet__check">
                <h2 class="ticket__check-title">Вы выбрали билеты:</h2>
            </header>
            
            <div class="ticket__info-wrapper">
                <p class="ticket__info">На фильм: <span class="ticket__details ticket__title">{{ $session->movie->title ?? 'Фильм' }}</span></p>
                <p class="ticket__info">Места: <span class="ticket__details ticket__chairs">
                    @if(isset($selectedSeatsData) && is_array($selectedSeatsData))
                        @foreach($selectedSeatsData as $seat)
                            Ряд {{ $seat['row'] ?? '' }}, Место {{ $seat['number'] ?? '' }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    @else
                        Не указаны
                    @endif
                </span></p>
                <p class="ticket__info">В зале: <span class="ticket__details ticket__hall">{{ $session->hall->name ?? 'Не указан' }}</span></p>
                <p class="ticket__info">Начало сеанса: <span class="ticket__details ticket__start">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</span></p>
                <p class="ticket__info">Стоимость: <span class="ticket__details ticket__cost">{{ $totalPrice ?? 0 }}</span> рублей</p>

                <form method="POST" action="{{ route('booking.confirm') }}" style="margin-top: 2rem;">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                    <input type="hidden" name="selected_seats" value="{{ json_encode($selectedSeatsData) }}">
                    <button type="submit" class="acceptin-button">Получить код бронирования</button>
                </form>

                <p class="ticket__hint">После оплаты билет будет доступен в этом окне, а также придёт вам на почту. Покажите QR-код нашему контроллёру у входа в зал.</p>
                <p class="ticket__hint">Приятного просмотра!</p>
            </div>
        </section>
    </main>
</x-cinema-layout>

