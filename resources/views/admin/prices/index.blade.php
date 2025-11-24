<x-admin-layout title="Настройка цен">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Цены на билеты</h2>
        </header>
        <div class="conf-step__wrapper">
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

            <form method="POST" action="{{ route('admin.prices.update') }}">
                @csrf
                @method('PUT')

                <label class="conf-step__label conf-step__label-fullsize" for="regular_price">
                    Цена обычного места (₽)
                    <input class="conf-step__input"
                           type="number"
                           name="regular_price"
                           id="regular_price"
                           value="{{ old('regular_price', $regularPrice ?? 500) }}"
                           min="0"
                           step="50"
                           required>
                </label>
                <p class="conf-step__paragraph" style="margin-top: -0.8rem; margin-bottom: 1.5rem; font-size: 1.2rem; color: #848484;">
                    Стоимость стандартного кресла
                </p>

                <label class="conf-step__label conf-step__label-fullsize" for="vip_price">
                    Цена VIP места (₽)
                    <input class="conf-step__input"
                           type="number"
                           name="vip_price"
                           id="vip_price"
                           value="{{ old('vip_price', $vipPrice ?? 1000) }}"
                           min="0"
                           step="50"
                           required>
                </label>
                <p class="conf-step__paragraph" style="margin-top: -0.8rem; margin-bottom: 1.5rem; font-size: 1.2rem; color: #848484;">
                    Стоимость VIP кресла
                </p>

                <div class="conf-step__buttons text-center">
                    <a href="{{ route('admin.dashboard') }}" class="conf-step__button conf-step__button-regular">Отмена</a>
                    <button type="submit" class="conf-step__button conf-step__button-accent">Сохранить цены</button>
                </div>
            </form>

            <div class="conf-step__alert" style="margin-top: 3rem; background-color: rgba(255, 255, 255, 0.85); border-left: 4px solid #16A6AF;">
                <div class="conf-step__alert-title" style="color: #146C72;">Информация</div>
                <p class="conf-step__paragraph" style="margin-top: 0.5rem; margin-bottom: 0.5rem;">
                    Обновленные значения автоматически подставляются во все новые бронирования.
                </p>
                <p class="conf-step__paragraph" style="margin-top: 0.5rem; margin-bottom: 0;">
                    Уже оформленные билеты сохраняют цену, по которой были приобретены.
                </p>
            </div>
        </div>
    </section>
</x-admin-layout>

