<div class="popup" id="popup-remove-hall">
    <div class="popup__container">
        <div class="popup__content">
            <div class="popup__header">
                <h2 class="popup__title">
                    Удаление зала
                    <a class="popup__dismiss" href="#" onclick="closePopup('popup-remove-hall'); return false;">
                        <img src="{{ asset('i/admin/close.png') }}" alt="Закрыть">
                    </a>
                </h2>
            </div>
            <div class="popup__wrapper">
                <form id="form-remove-hall" method="post" accept-charset="utf-8">
                    @csrf
                    @method('DELETE')
                    <p class="conf-step__paragraph">Вы действительно хотите удалить зал <span id="hall-name">""</span>?</p>
                    <div class="conf-step__buttons text-center">
                        <input type="submit" value="Удалить" class="conf-step__button conf-step__button-accent">
                        <button class="conf-step__button conf-step__button-regular" type="button" onclick="closePopup('popup-remove-hall'); return false;">Отменить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

