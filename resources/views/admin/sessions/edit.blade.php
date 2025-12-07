<x-admin-layout title="Редактировать сеанс">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Сеанс #{{ $session->id }}</h2>
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

            <form method="POST" action="{{ route('admin.sessions.update', $session->id) }}">
                @csrf
                @method('PUT')

                <label class="conf-step__label conf-step__label-fullsize" for="movie_id">
                    Фильм
                    <select id="movie_id"
                            name="movie_id"
                            class="conf-step__input"
                            required
                            style="width: 100%; max-width: 500px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px; background: #fff; color: #000;">
                        <option value="">Выберите фильм</option>
                        @foreach($movies ?? [] as $movie)
                            <option value="{{ $movie->id }}" {{ old('movie_id', $session->movie_id) == $movie->id ? 'selected' : '' }}>
                                {{ $movie->title }} ({{ $movie->duration }} мин)
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="conf-step__label conf-step__label-fullsize" for="hall_id">
                    Зал
                    <select id="hall_id"
                            name="hall_id"
                            class="conf-step__input"
                            required
                            style="width: 100%; max-width: 500px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px; background: #fff; color: #000;">
                        <option value="">Выберите зал</option>
                        @foreach($halls ?? [] as $hall)
                            <option value="{{ $hall->id }}" {{ old('hall_id', $session->hall_id) == $hall->id ? 'selected' : '' }}>
                                {{ $hall->name }} ({{ $hall->rows }} × {{ $hall->seats_per_row }})
                            </option>
                        @endforeach
                    </select>
                </label>

                <div class="conf-step__legend" style="margin-top: 1rem; display: flex; gap: 2rem; flex-wrap: wrap;">
                    <label class="conf-step__label" for="start_time">
                        Дата и время начала
                        <input class="conf-step__input" 
                               type="datetime-local" 
                               name="start_time" 
                               id="start_time" 
                               value="{{ old('start_time', \Carbon\Carbon::parse($session->start_time)->format('Y-m-d\TH:i')) }}" 
                               required
                               style="width: 100%; max-width: 300px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px; background: #fff; color: #000;">
                    </label>
                    <label class="conf-step__label" for="end_time">
                        Дата и время окончания
                        <input class="conf-step__input" 
                               type="datetime-local" 
                               name="end_time" 
                               id="end_time" 
                               value="{{ old('end_time', \Carbon\Carbon::parse($session->end_time)->format('Y-m-d\TH:i')) }}" 
                               required
                               style="width: 100%; max-width: 300px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px; background: #fff; color: #000;">
                    </label>
                </div>

                <div class="conf-step__buttons text-center" style="margin-top: 2rem;">
                    <a href="{{ route('admin.sessions.index') }}" class="conf-step__button conf-step__button-regular">
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
