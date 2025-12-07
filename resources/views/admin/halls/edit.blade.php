<x-admin-layout title="Редактировать зал">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Редактирование: {{ $hall->name }}</h2>
        </header>
        <div class="conf-step__wrapper" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @if ($errors->any())
                <div class="conf-step__alert conf-step__alert_error">
                    <div class="conf-step__alert-title">Ошибка</div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.halls.update', $hall->id) }}">
                @csrf
                @method('PUT')

                <label class="conf-step__label conf-step__label-fullsize" for="name">
                    Название зала
                    <input class="conf-step__input" 
                           type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $hall->name) }}" 
                           placeholder="Например, &laquo;Зал 1&raquo;" 
                           required 
                           autofocus
                           style="width: 100%; max-width: 400px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px;">
                </label>

                <div class="conf-step__legend" style="margin-top: 1rem;">
                    <label class="conf-step__label">
                        Количество рядов
                        <input type="number" 
                               class="conf-step__input" 
                               name="rows" 
                               id="rows"
                               value="{{ old('rows', $hall->rows) }}" 
                               min="1" 
                               max="20" 
                               required
                               style="width: 100px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px;">
                    </label>
                    <span class="multiplier">×</span>
                    <label class="conf-step__label">
                        Мест в ряду
                        <input type="number" 
                               class="conf-step__input" 
                               name="seats_per_row" 
                               id="seats_per_row"
                               value="{{ old('seats_per_row', $hall->seats_per_row) }}" 
                               min="1" 
                               max="30" 
                               required
                               style="width: 100px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px;">
                    </label>
                </div>

                <label class="conf-step__label" style="display: flex; align-items: center; margin-top: 1.5rem; font-size: 1.4rem; color: #848484;">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           @checked(old('is_active', $hall->is_active))
                           style="margin-right: 0.8rem; width: 18px; height: 18px; cursor: pointer;">
                    Продажи открыты
                </label>

                <p class="conf-step__paragraph">Обозначения:</p>
                <div class="conf-step__legend">
                    <span class="conf-step__chair conf-step__chair_standart"></span> — обычные места
                    <span class="conf-step__chair conf-step__chair_vip"></span> — VIP места
                    <span class="conf-step__chair conf-step__chair_disabled"></span> — заблокировано
                </div>

                <p class="conf-step__paragraph" style="font-size: 1.2rem; color: #848484; margin-top: 1rem;">
                    Кликните на место, чтобы изменить его тип (обычное ↔ VIP ↔ заблокировано)
                </p>

                <div class="conf-step__hall">
                    <div class="conf-step__hall-wrapper">
                        @forelse($seatMatrix ?? [] as $rowSeats)
                            <div class="conf-step__row">
                                @foreach($rowSeats as $seat)
                                    @php
                                        $type = strtolower($seat->type ?? 'regular');
                                        $seatClass = match ($type) {
                                            'vip' => 'conf-step__chair_vip',
                                            'disabled' => 'conf-step__chair_disabled',
                                            default => 'conf-step__chair_standart',
                                        };
                                    @endphp
                                    <span class="conf-step__chair {{ $seatClass }} seat-editable" 
                                          data-seat-id="{{ $seat->id }}"
                                          data-seat-type="{{ $type }}"
                                          data-row="{{ $seat->row }}"
                                          data-number="{{ $seat->number }}"
                                          title="Ряд {{ $seat->row }}, Место {{ $seat->number }}">
                                    </span>
                                @endforeach
                            </div>
                        @empty
                            <div class="conf-step__empty">
                                Места будут сгенерированы автоматически после сохранения параметров зала.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="conf-step__buttons text-center" style="margin-top: 2rem;">
                    <a href="{{ route('admin.halls.index') }}" class="conf-step__button conf-step__button-regular">
                        Отмена
                    </a>
                    <button type="submit" class="conf-step__button conf-step__button-accent">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const seatElements = document.querySelectorAll('.seat-editable');
    
    seatElements.forEach(seat => {
        seat.addEventListener('click', function() {
            const seatId = this.dataset.seatId;
            const currentType = this.dataset.seatType;
            
            // Toggle type: regular -> vip -> disabled -> regular
            let newType;
            let newClass;
            switch(currentType) {
                case 'regular':
                    newType = 'vip';
                    newClass = 'conf-step__chair_vip';
                    break;
                case 'vip':
                    newType = 'disabled';
                    newClass = 'conf-step__chair_disabled';
                    break;
                case 'disabled':
                default:
                    newType = 'regular';
                    newClass = 'conf-step__chair_standart';
                    break;
            }
            
            // Update seat type via AJAX
            fetch(`/admin/seats/${seatId}/update-type`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: newType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    this.className = `conf-step__chair ${newClass} seat-editable`;
                    this.dataset.seatType = newType;
                } else {
                    alert('Ошибка при обновлении типа места: ' + (data.message || 'Неизвестная ошибка'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ошибка при обновлении типа места');
            });
        });
    });
});
</script>
@endpush
