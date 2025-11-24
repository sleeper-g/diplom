<x-admin-layout title="Создать зал">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Новый зал</h2>
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

            <form method="POST" action="{{ route('admin.halls.store') }}">
                @csrf

                <label class="conf-step__label conf-step__label-fullsize" for="name">
                    Название зала
                    <input class="conf-step__input" 
                           type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}" 
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
                               value="{{ old('rows') }}" 
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
                               value="{{ old('seats_per_row') }}" 
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
                           style="margin-right: 0.8rem; width: 18px; height: 18px; cursor: pointer;">
                    Сразу открыть продажи
                </label>

                <div class="conf-step__buttons text-center" style="margin-top: 2rem;">
                    <a href="{{ route('admin.halls.index') }}" class="conf-step__button conf-step__button-regular">
                        Отмена
                    </a>
                    <button type="submit" class="conf-step__button conf-step__button-accent">
                        Создать
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>

