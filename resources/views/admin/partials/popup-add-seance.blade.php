<div class="popup" id="popup-add-seance">
    <div class="popup__container">
        <div class="popup__content">
            <div class="popup__header">
                <h2 class="popup__title">
                    Добавление сеанса
                    <a class="popup__dismiss" href="#" onclick="closePopup('popup-add-seance'); return false;">
                        <img src="{{ asset('i/admin/close.png') }}" alt="Закрыть">
                    </a>
                </h2>
            </div>
            <div class="popup__wrapper">
                <form action="{{ route('admin.sessions.store') }}" method="post" accept-charset="utf-8">
                    @csrf
                    <label class="conf-step__label conf-step__label-fullsize" for="hall_id">
                        Название зала
                        <select class="conf-step__input" name="hall_id" required>
                            <option value="">Выберите зал</option>
                            @foreach($halls ?? [] as $hall)
                                <option value="{{ $hall->id }}">{{ $hall->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="conf-step__label conf-step__label-fullsize" for="movie_id">
                        Название фильма
                        <select class="conf-step__input" name="movie_id" required>
                            <option value="">Выберите фильм</option>
                            @foreach($movies ?? [] as $movie)
                                <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="conf-step__label conf-step__label-fullsize" for="start_time">
                        Дата и время начала
                        <input class="conf-step__input" type="datetime-local" name="start_time" required>
                    </label>
                    <label class="conf-step__label conf-step__label-fullsize" for="end_time">
                        Дата и время окончания
                        <input class="conf-step__input" type="datetime-local" name="end_time" required>
                    </label>
                    <div class="conf-step__buttons text-center">
                        <input type="submit" value="Добавить" class="conf-step__button conf-step__button-accent">
                        <button class="conf-step__button conf-step__button-regular" type="button" onclick="closePopup('popup-add-seance'); return false;">Отменить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

