<x-cinema-layout>
    @php
        $today = \Carbon\Carbon::today();
        $selectedDate = request('date', $today->format('Y-m-d'));
        $date = \Carbon\Carbon::parse($selectedDate);
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $dayDate = $today->copy()->addDays($i);
            $days[] = [
                'date' => $dayDate,
                'dayOfWeek' => $dayDate->locale('ru')->dayName,
                'dayNumber' => $dayDate->day,
                'isToday' => $i === 0,
                'isWeekend' => $dayDate->isWeekend(),
            ];
        }
    @endphp

    <nav class="page-nav">
        @foreach($days as $day)
            <a class="page-nav__day {{ $day['isToday'] ? 'page-nav__day_today' : '' }} {{ $day['isWeekend'] ? 'page-nav__day_weekend' : '' }} {{ $selectedDate == $day['date']->format('Y-m-d') ? 'page-nav__day_chosen' : '' }}" 
               href="{{ route('schedule.index', ['date' => $day['date']->format('Y-m-d'), 'movie' => request('movie')]) }}">
                <span class="page-nav__day-week">{{ mb_substr($day['dayOfWeek'], 0, 2) }}</span>
                <span class="page-nav__day-number">{{ $day['dayNumber'] }}</span>
            </a>
        @endforeach
        <a class="page-nav__day page-nav__day_next" href="#"></a>
    </nav>

    <main>
        @php
            $sessionsByMovie = \App\Models\Session::with(['movie', 'hall'])
                ->whereDate('start_time', $selectedDate)
                ->when(request('movie'), function($query) {
                    return $query->where('movie_id', request('movie'));
                })
                ->orderBy('start_time')
                ->get()
                ->groupBy('movie_id');
        @endphp

        @forelse($sessionsByMovie as $movieId => $sessions)
            @php $movie = $sessions->first()->movie; @endphp
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
                    </div>
                </div>

                @php
                    $sessionsByHall = $sessions->groupBy('hall_id');
                @endphp
                @foreach($sessionsByHall as $hallId => $hallSessions)
                    @php $hall = $hallSessions->first()->hall; @endphp
                    <div class="movie-seances__hall">
                        <h3 class="movie-seances__hall-title">{{ $hall->name }}</h3>
                        <ul class="movie-seances__list">
                            @foreach($hallSessions as $session)
                                <li class="movie-seances__time-block">
                                    <a class="movie-seances__time" href="{{ route('booking.create', $session->id) }}">
                                        {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </section>
        @empty
            <section class="movie">
                <div class="movie__info">
                    <div class="movie__description">
                        <h2 class="movie__title">На выбранную дату сеансов нет</h2>
                        <p class="movie__synopsis">Попробуйте выбрать другую дату</p>
                    </div>
                </div>
            </section>
        @endforelse
    </main>
</x-cinema-layout>
