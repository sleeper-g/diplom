<x-admin-layout title="Управление фильмами">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Фильмотека</h2>
        </header>
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">В базе: {{ $movies->count() }} фильм(ов)</p>

            @if($movies->isEmpty())
                <div class="conf-step__empty">
                    Фильмы не найдены. Нажмите «Добавить фильм», чтобы создать первый фильм.
                </div>
            @else
                <ul class="conf-step__list conf-step__list_with-actions">
                    @foreach($movies as $movie)
                        <li style="position: relative;">
                            <div style="display: flex; align-items: flex-start; gap: 1.5rem;">
                                <div style="flex-shrink: 0;">
                                    <img src="{{ $movie->poster ? asset($movie->poster) : asset('i/poster1.jpg') }}" 
                                         alt="{{ $movie->title }}" 
                                         style="width: 60px; height: 85px; object-fit: cover; border: 1px solid #b7b7b7; border-radius: 2px;">
                                </div>
                                <div style="flex-grow: 1;">
                                    <strong>{{ $movie->title }}</strong>
                                    <span style="display: block; margin-top: 0.5rem; font-size: 1.4rem; color: #848484; font-weight: 300;">
                                        {{ Str::limit($movie->description, 100) }}
                                    </span>
                                    <span style="display: block; margin-top: 0.5rem; font-size: 1.2rem; color: #848484;">
                                        Длительность: {{ $movie->duration }} мин
                                    </span>
                                </div>
                            </div>
                            <div class="conf-step__list-controls">
                                <a href="{{ route('admin.movies.edit', $movie->id) }}" class="conf-step__button-link">Редактировать</a>
                                <form method="POST" action="{{ route('admin.movies.destroy', $movie->id) }}" onsubmit="return confirm('Удалить фильм {{ $movie->title }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="conf-step__button conf-step__button-trash"></button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="conf-step__buttons text-center">
                <a href="{{ route('admin.movies.create') }}" class="conf-step__button conf-step__button-accent">
                    Добавить фильм
                </a>
            </div>
        </div>
    </section>
</x-admin-layout>

