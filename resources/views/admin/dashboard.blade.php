<x-admin-layout title="Идёмвкино" subtitle="Администраторррская">
    @php
        $seatMatrix = $selectedHall && $selectedHall->relationLoaded('seats')
            ? $selectedHall->seats->groupBy('row')->sortKeys()->map(fn ($row) => $row->sortBy('number'))
            : collect();
        $sessionsByHall = $sessions->groupBy('hall_id');
        $dayStart = \Carbon\Carbon::parse($date)->startOfDay();
        $pixelPerMinute = 0.5;
    @endphp

    <!-- Управление залами -->
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Управление залами</h2>
        </header>
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Доступные залы:</p>

            @if($halls->isEmpty())
                <div class="conf-step__empty">
                    Пока нет созданных залов. Нажмите «Создать зал», чтобы добавить первый.
                </div>
            @else
                <ul class="conf-step__list conf-step__list_with-actions">
                    @foreach($halls as $hall)
                        <li>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                                    <strong style="font-size: 1.6rem;">{{ $hall->name }}</strong>
                                    <span class="conf-step__status {{ $hall->is_active ? 'conf-step__status_online' : 'conf-step__status_offline' }}">
                                        {{ $hall->is_active ? 'Продажи открыты' : 'Продажи остановлены' }}
                                    </span>
                                </div>
                                <span style="font-size: 1.4rem; color: #848484; font-weight: 300;">
                                    {{ $hall->rows }} рядов × {{ $hall->seats_per_row }} мест в ряду = {{ $hall->rows * $hall->seats_per_row }} мест
                                </span>
                            </div>
                            <div class="conf-step__list-controls">
                                <a href="{{ route('admin.halls.edit', $hall->id) }}" class="conf-step__button-link">Настроить</a>
                                <form method="POST" action="{{ route('admin.halls.toggle', $hall->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="conf-step__button-link" style="background: transparent; border: 1px solid #63536C; cursor: pointer; font-family: inherit;">
                                        {{ $hall->is_active ? 'Приостановить' : 'Открыть' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.halls.destroy', $hall->id) }}" onsubmit="return confirm('Удалить зал {{ $hall->name }}?');" style="display: inline;">
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
                <a href="{{ route('admin.halls.create') }}" class="conf-step__button conf-step__button-accent">
                    Создать зал
                </a>
            </div>
        </div>
    </section>

    <!-- Конфигурация залов -->
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Конфигурация залов</h2>
        </header>
        <div class="conf-step__wrapper">
            @if($halls->isEmpty())
                <div class="conf-step__empty">
                    Добавьте хотя бы один зал, чтобы настроить ряды и места.
                </div>
            @else
                <p class="conf-step__paragraph">Выберите зал для конфигурации:</p>
                <form method="GET" action="{{ route('admin.dashboard') }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <ul class="conf-step__selectors-box">
                        @foreach($halls as $hallOption)
                            <li>
                                <input type="radio"
                                       class="conf-step__radio"
                                       id="hall-{{ $hallOption->id }}"
                                       name="hall_id"
                                       value="{{ $hallOption->id }}"
                                       onchange="this.form.submit()"
                                       {{ (int)$selectedHallId === $hallOption->id ? 'checked' : '' }}>
                                <span class="conf-step__selector">{{ $hallOption->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </form>

                @if($selectedHall)
                    <p class="conf-step__paragraph">Основные параметры:</p>
                    <form method="POST" action="{{ route('admin.halls.update', $selectedHall->id) }}">
                        @csrf
                        @method('PUT')

                        <label class="conf-step__label" style="display:block; margin-bottom: 15px;">
                            Название
                            <input type="text" name="name" class="conf-step__input" value="{{ old('name', $selectedHall->name) }}" required>
                        </label>

                        <div class="conf-step__legend">
                            <label class="conf-step__label">Рядов, шт
                                <input type="number" class="conf-step__input" name="rows" min="1" max="20" value="{{ old('rows', $selectedHall->rows) }}" required>
                            </label>
                            <span class="multiplier">x</span>
                            <label class="conf-step__label">Мест, шт
                                <input type="number" class="conf-step__input" name="seats_per_row" min="1" max="30" value="{{ old('seats_per_row', $selectedHall->seats_per_row) }}" required>
                            </label>
                        </div>

                        <input type="hidden" name="is_active" value="{{ $selectedHall->is_active ? 1 : 0 }}">

                        <p class="conf-step__paragraph">Обозначения:</p>
                        <div class="conf-step__legend">
                            <span class="conf-step__chair conf-step__chair_standart"></span> — обычные места
                            <span class="conf-step__chair conf-step__chair_vip"></span> — VIP места
                            <span class="conf-step__chair conf-step__chair_disabled"></span> — заблокировано
                        </div>

                        <div class="conf-step__hall">
                            <div class="conf-step__hall-wrapper">
                                @forelse($seatMatrix as $rowSeats)
                                    <div class="conf-step__row">
                                        @foreach($rowSeats as $seat)
                                            @php
                                                $type = strtolower($seat->type ?? 'regular');
                                                $seatClass = match ($type) {
                                                    'vip' => 'conf-step__chair_vip',
                                                    'disabled' => 'conf-step__chair_disabled',
                                                    default => 'conf-step__chair_standart',
                                                };
                                            @endphp
                                            <span class="conf-step__chair {{ $seatClass }}"></span>
                                        @endforeach
                                    </div>
                                @empty
                                    <div class="conf-step__empty">
                                        Места будут сгенерированы автоматически после сохранения параметров зала.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <fieldset class="conf-step__buttons text-center">
                            <a href="{{ route('admin.halls.index') }}" class="conf-step__button conf-step__button-regular">Все залы</a>
                            <button type="submit" class="conf-step__button conf-step__button-accent">Сохранить</button>
                        </fieldset>
                    </form>
                @endif
            @endif
        </div>
    </section>

    <!-- Настройка цен -->
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Конфигурация цен</h2>
        </header>
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Установите цены для типов кресел:</p>
            <form method="POST" action="{{ route('admin.prices.update') }}">
                @csrf
                @method('PUT')
                <div class="conf-step__legend">
                    <label class="conf-step__label">Цена, ₽
                        <input type="number" class="conf-step__input" min="0" step="50" name="regular_price" value="{{ old('regular_price', $regularPrice) }}" required>
                    </label>
                    за <span class="conf-step__chair conf-step__chair_standart"></span> обычные места
                </div>
                <div class="conf-step__legend">
                    <label class="conf-step__label">Цена, ₽
                        <input type="number" class="conf-step__input" min="0" step="50" name="vip_price" value="{{ old('vip_price', $vipPrice) }}" required>
                    </label>
                    за <span class="conf-step__chair conf-step__chair_vip"></span> VIP места
                </div>
                <fieldset class="conf-step__buttons text-center">
                    <button type="submit" class="conf-step__button conf-step__button-accent">Сохранить</button>
                </fieldset>
            </form>
        </div>
    </section>

    <!-- Сетка сеансов -->
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Сетка сеансов</h2>
        </header>
        <div class="conf-step__wrapper">
            <div class="conf-step__paragraph" style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                <form method="GET" action="{{ route('admin.dashboard') }}" style="display:flex; gap:10px; align-items:center;">
                    <label class="conf-step__label" style="margin-bottom:0;">
                        Дата
                        <input type="date" class="conf-step__input" name="date" value="{{ $date }}">
                    </label>
                    <input type="hidden" name="hall_id" value="{{ $selectedHallId }}">
                    <button type="submit" class="conf-step__button conf-step__button-regular">Показать</button>
                </form>
                <div>
                    <a href="{{ route('admin.movies.create') }}" class="conf-step__button conf-step__button-accent" style="margin-right:10px;">Добавить фильм</a>
                    <a href="{{ route('admin.sessions.create') }}" class="conf-step__button conf-step__button-regular">Добавить сеанс</a>
                </div>
            </div>

            <div class="conf-step__movies">
                @forelse($movies as $movie)
                    <div class="conf-step__movie">
                        <img class="conf-step__movie-poster" src="{{ asset('i/admin/poster.png') }}" alt="poster">
                        <h3 class="conf-step__movie-title">{{ $movie->title }}</h3>
                        <p class="conf-step__movie-duration">{{ $movie->duration }} минут</p>
                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="conf-step__button conf-step__button-regular" style="margin-top:10px;">Редактировать</a>
                    </div>
                @empty
                    <div class="conf-step__empty" style="width:100%;">
                        Добавьте фильмы, чтобы сформировать афишу.
                    </div>
                @endforelse
            </div>

            <div class="conf-step__seances">
                @forelse($halls as $hall)
                    @php
                        $hallSessions = $sessionsByHall->get($hall->id, collect());
                    @endphp
                    <div class="conf-step__seances-hall">
                        <h3 class="conf-step__seances-title">{{ $hall->name }}</h3>
                        <div class="conf-step__seances-timeline">
                            @forelse($hallSessions as $session)
                                @php
                                    $start = \Carbon\Carbon::parse($session->start_time);
                                    $end = \Carbon\Carbon::parse($session->end_time);
                                    $duration = max(15, $start->diffInMinutes($end));
                                    $offset = max(0, $dayStart->diffInMinutes($start));
                                    $width = max(30, $duration * $pixelPerMinute);
                                    $left = min(720, $offset * $pixelPerMinute);
                                    $colorHue = ($session->movie_id * 37) % 360;
                                    $background = "hsl({$colorHue}, 70%, 70%)";
                                @endphp
                                <div class="conf-step__seances-movie" style="width: {{ $width }}px; left: {{ $left }}px; background-color: {{ $background }};">
                                    <p class="conf-step__seances-movie-title">{{ \Illuminate\Support\Str::limit($session->movie->title, 32) }}</p>
                                    <p class="conf-step__seances-movie-start">{{ $start->format('H:i') }}</p>
                                </div>
                            @empty
                                <div class="conf-step__empty">
                                    Нет сеансов на выбранную дату.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="conf-step__empty">
                        Добавьте зал, чтобы планировать расписание.
                    </div>
                @endforelse
            </div>

            <fieldset class="conf-step__buttons text-center">
                <a href="{{ route('admin.sessions.index') }}" class="conf-step__button conf-step__button-regular">Все сеансы</a>
            </fieldset>
        </div>
    </section>

    <!-- Открыть продажи -->
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Открыть продажи</h2>
        </header>
        <div class="conf-step__wrapper text-center">
            <p class="conf-step__paragraph">
                Активных залов: {{ $stats['activeHalls'] ?? 0 }} / {{ $stats['totalHalls'] ?? 0 }} ·
                Сеансов в выбранный день: {{ $stats['sessionsCount'] ?? 0 }} ·
                Забронировано сегодня: {{ $stats['ticketsToday'] ?? 0 }}
            </p>

            @if($selectedHall)
                <form method="POST" action="{{ route('admin.halls.toggle', $selectedHall->id) }}">
                    @csrf
                    <button type="submit" class="conf-step__button conf-step__button-accent">
                        {{ $selectedHall->is_active ? 'Приостановить продажу билетов' : 'Открыть продажу билетов' }}
                    </button>
                </form>
            @else
                <a href="{{ route('admin.halls.create') }}" class="conf-step__button conf-step__button-accent">
                    Создать первый зал
                </a>
            @endif
        </div>
    </section>
</x-admin-layout>

