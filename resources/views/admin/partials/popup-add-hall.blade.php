<div class="popup" id="popup-add-hall">
    <div class="popup__container">
        <div class="popup__content">
            <div class="popup__header">
                <h2 class="popup__title">
                    Добавление зала
                    <a class="popup__dismiss" href="#" onclick="closePopup('popup-add-hall'); return false;">
                        <img src="{{ asset('i/admin/close.png') }}" alt="Закрыть">
                    </a>
                </h2>
            </div>
            <div class="popup__wrapper">
                <form action="{{ route('admin.halls.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                    <label class="conf-step__label conf-step__label-fullsize" for="name">
                        Название зала
                        <input class="conf-step__input" type="text" placeholder="Например, &laquo;Зал 1&raquo;" name="name" required>
                    </label>
                    <label class="conf-step__label conf-step__label-fullsize" for="rows">
                        Количество рядов
                        <input class="conf-step__input" type="number" min="1" max="20" name="rows" required>
                    </label>
                    <label class="conf-step__label conf-step__label-fullsize" for="seats_per_row">
                        Мест в ряду
                        <input class="conf-step__input" type="number" min="1" max="30" name="seats_per_row" required>
                    </label>
                    <div class="conf-step__buttons text-center">
                        <input type="submit" value="Добавить зал" class="conf-step__button conf-step__button-accent">
                        <button class="conf-step__button conf-step__button-regular" type="button" onclick="closePopup('popup-add-hall'); return false;">Отменить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

