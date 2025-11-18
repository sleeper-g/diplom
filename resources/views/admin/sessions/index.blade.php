<x-admin-layout title="Управление сеансами">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Расписание</h2>
        </header>
        <div class="conf-step__wrapper">
            <div class="flex justify-between items-center mb-6 gap-4 flex-wrap">
                <form method="GET" action="{{ route('admin.sessions.index') }}" class="flex gap-4 items-end">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            Фильтр по дате
                        </label>
                        <input type="date"
                               name="date"
                               id="date"
                               value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="conf-step__button conf-step__button-regular">
                        Показать
                    </button>
                </form>
                <a href="{{ route('admin.sessions.create') }}" class="conf-step__button conf-step__button-accent">
                    Добавить сеанс
                </a>
            </div>

            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Фильм
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Зал
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Начало
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Окончание
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sessions ?? [] as $session)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $session->movie->title ?? 'Не указан' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $session->hall->name ?? 'Не указан' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($session->start_time)->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($session->end_time)->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                    <a href="{{ route('admin.sessions.edit', $session->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Редактировать
                                    </a>
                                    <form method="POST" action="{{ route('admin.sessions.destroy', $session->id) }}" class="inline" onsubmit="return confirm('Удалить сеанс?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Сеансы не найдены. <a href="{{ route('admin.sessions.create') }}" class="text-indigo-600 hover:underline">Создайте первый сеанс</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-admin-layout>

