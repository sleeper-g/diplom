<x-admin-layout title="Управление сеансами">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Расписание</h2>
        </header>
        <div class="conf-step__wrapper">
            <form method="GET" action="{{ route('admin.sessions.index') }}" class="conf-step__paragraph" style="margin-bottom: 2rem;">
                <label class="conf-step__label" for="date">
                    Фильтр по дате
                    <input type="date"
                           name="date"
                           id="date"
                           value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                           class="conf-step__input"
                           style="width: 200px; display: inline-block; margin-left: 1rem;">
                </label>
                <button type="submit" class="conf-step__button conf-step__button-regular" style="margin-left: 1rem;">
                    Показать
                </button>
            </form>

            @if($sessions->isEmpty())
                <div class="conf-step__empty">
                    Сеансы не найдены. Нажмите «Добавить сеанс», чтобы создать первый сеанс.
                </div>
            @else
                <ul class="conf-step__list conf-step__list_with-actions">
                    @foreach($sessions as $session)
                        <li>
                            <div>
                                <strong>{{ $session->movie->title ?? 'Не указан' }}</strong>
                                <span>— Зал: {{ $session->hall->name ?? 'Не указан' }}</span>
                                <span>({{ \Carbon\Carbon::parse($session->start_time)->format('d.m.Y H:i') }} — {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }})</span>
                            </div>
                            <div class="conf-step__list-controls">
                                <a href="{{ route('admin.sessions.edit', $session->id) }}" class="conf-step__button-link">Редактировать</a>
                                <form method="POST" action="{{ route('admin.sessions.destroy', $session->id) }}" onsubmit="return confirm('Удалить сеанс?');">
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
                <a href="{{ route('admin.sessions.create') }}" class="conf-step__button conf-step__button-accent">
                    Добавить сеанс
                </a>
            </div>
        </div>
    </section>
</x-admin-layout>

