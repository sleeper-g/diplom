<x-admin-layout title="Управление залами">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Залы кинотеатра</h2>
        </header>
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Всего залов: {{ $halls->count() }}</p>

            @if($halls->isEmpty())
                <div class="conf-step__empty">
                    Залы не найдены. Нажмите «Добавить зал», чтобы создать первый зал.
                </div>
            @else
                <ul class="conf-step__list conf-step__list_with-actions">
                    @foreach($halls as $hall)
                        <li>
                            <div>
                                <strong>{{ $hall->name }}</strong>
                                <span>({{ $hall->rows }} рядов × {{ $hall->seats_per_row }} мест в ряду = {{ $hall->rows * $hall->seats_per_row }} мест)</span>
                            </div>
                            <div class="conf-step__list-controls">
                                <a href="{{ route('admin.halls.edit', $hall->id) }}" class="conf-step__button-link">Редактировать</a>
                                <form method="POST" action="{{ route('admin.halls.destroy', $hall->id) }}" onsubmit="return confirm('Удалить зал {{ $hall->name }}?');">
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
                    Добавить зал
                </a>
            </div>
        </div>
    </section>
</x-admin-layout>

