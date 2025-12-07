<x-admin-layout title="Редактировать фильм">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Фильм: {{ $movie->title }}</h2>
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

            <form method="POST" action="{{ route('admin.movies.update', $movie->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label class="conf-step__label conf-step__label-fullsize" for="title">
                    Название фильма
                    <input class="conf-step__input" 
                           type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $movie->title) }}" 
                           placeholder="Например, &laquo;Гражданин Кейн&raquo;" 
                           required 
                           autofocus
                           style="width: 100%; max-width: 500px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px; background: #fff; color: #000;">
                </label>

                <label class="conf-step__label conf-step__label-fullsize" for="poster">
                    Постер фильма
                    @if($movie->poster)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset($movie->poster) }}" 
                                 alt="Текущий постер" 
                                 style="max-width: 200px; max-height: 300px; border: 1px solid #b7b7b7; border-radius: 2px; display: block;">
                            <small style="display: block; margin-top: 5px; color: #848484; font-size: 1.2rem;">Текущий постер</small>
                        </div>
                    @endif
                    <input class="conf-step__input" 
                           type="file" 
                           name="poster" 
                           id="poster" 
                           accept="image/*"
                           style="width: 100%; max-width: 500px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px; background: #fff; color: #000;">
                    <small style="display: block; margin-top: 5px; color: #848484; font-size: 1.2rem;">Максимальный размер: 2MB. Форматы: JPEG, PNG, JPG, GIF. Оставьте пустым, чтобы сохранить текущий постер.</small>
                </label>

                <label class="conf-step__label conf-step__label-fullsize" for="description">
                    Описание фильма
                    <textarea class="conf-step__input" 
                              name="description" 
                              id="description" 
                              rows="5" 
                              required
                              style="width: 100%; max-width: 500px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px; background: #fff; color: #000; font-family: inherit; resize: vertical;">{{ old('description', $movie->description) }}</textarea>
                </label>

                <label class="conf-step__label" for="duration">
                    Длительность (минуты)
                    <input class="conf-step__input" 
                           type="number" 
                           name="duration" 
                           id="duration" 
                           value="{{ old('duration', $movie->duration) }}" 
                           min="1" 
                           max="300" 
                           required
                           style="width: 120px; padding: 8px; font-size: 1.4rem; border: 1px solid #b7b7b7; border-radius: 2px; background: #fff; color: #000;">
                </label>

                <div class="conf-step__buttons text-center" style="margin-top: 2rem;">
                    <a href="{{ route('admin.movies.index') }}" class="conf-step__button conf-step__button-regular">
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
