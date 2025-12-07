<x-cinema-layout>
    <main>
        <section class="movie">
            <div class="movie__info">
                <div class="movie__poster">
                    <img class="movie__poster-image" alt="{{ $movie->title }}" src="{{ $movie->poster ? asset($movie->poster) : asset('i/poster1.jpg') }}">
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
                           style="display: inline-block;">
                            Посмотреть расписание
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-cinema-layout>
