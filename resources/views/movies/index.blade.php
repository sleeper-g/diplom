<x-cinema-layout>
    <main>
        @forelse($movies ?? [] as $movie)
            <section class="movie">
                <div class="movie__info">
                    <div class="movie__poster">
                        <img class="movie__poster-image" alt="{{ $movie->title }}" src="{{ asset('i/poster1.jpg') }}">
                    </div>
                    <div class="movie__description">
                        <h2 class="movie__title">{{ $movie->title }}</h2>
                        <p class="movie__synopsis">{{ $movie->description }}</p>
                        <p class="movie__data">
                            <span class="movie__data-duration">{{ $movie->duration }} минут</span>
                        </p>
                        <div style="margin-top: 1.5rem;">
                            <a href="{{ route('schedule.index', ['movie' => $movie->id]) }}" 
                               class="movie-seances__time" 
                               style="display: inline-block; margin-right: 0.5rem;">
                                Расписание
                            </a>
                            <a href="{{ route('movies.show', $movie->id) }}" 
                               class="movie-seances__time" 
                               style="display: inline-block;">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <section class="movie">
                <div class="movie__info">
                    <div class="movie__description">
                        <h2 class="movie__title">Фильмы пока не добавлены</h2>
                    </div>
                </div>
            </section>
        @endforelse
    </main>
</x-cinema-layout>
