<x-admin-layout title="Управление фильмами">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Фильмотека</h2>
        </header>
        <div class="conf-step__wrapper">
            <div class="flex justify-between items-center mb-6 gap-4">
                <p class="conf-step__paragraph m-0">В базе: {{ $movies->count() }} фильм(ов)</p>
                <a href="{{ route('admin.movies.create') }}" class="conf-step__button conf-step__button-accent">
                    Добавить фильм
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($movies ?? [] as $movie)
                    <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                        <img src="{{ asset('i/admin/poster.png') }}" alt="" class="absolute top-6 right-6 w-12 opacity-20">
                        <h3 class="text-lg font-semibold mb-2">{{ $movie->title }}</h3>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                            {{ $movie->description }}
                        </p>
                        <p class="text-sm text-gray-500 mb-4">Длительность: {{ $movie->duration }} мин</p>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="conf-step__button conf-step__button-regular flex-1 text-center">
                                Редактировать
                            </a>
                            <form method="POST" action="{{ route('admin.movies.destroy', $movie->id) }}" class="flex-1" onsubmit="return confirm('Удалить фильм {{ $movie->title }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="conf-step__button conf-step__button-accent w-full">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="conf-step__empty col-span-full">
                        Фильмы не найдены. <a href="{{ route('admin.movies.create') }}">Создайте первый фильм</a>.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-admin-layout>

