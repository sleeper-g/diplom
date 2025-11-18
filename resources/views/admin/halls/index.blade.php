<x-admin-layout title="Управление залами">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Залы кинотеатра</h2>
        </header>
        <div class="conf-step__wrapper">
            <div class="flex justify-between items-center mb-6 gap-4">
                <p class="conf-step__paragraph m-0">Всего залов: {{ $halls->count() }}</p>
                <a href="{{ route('admin.halls.create') }}" class="conf-step__button conf-step__button-accent">
                    Добавить зал
                </a>
            </div>
            <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Название
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Рядов
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Мест в ряду
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Всего мест
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($halls ?? [] as $hall)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $hall->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $hall->rows }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $hall->seats_per_row }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $hall->rows * $hall->seats_per_row }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                    <a href="{{ route('admin.halls.edit', $hall->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Редактировать
                                    </a>
                                    <form method="POST" action="{{ route('admin.halls.destroy', $hall->id) }}" class="inline" onsubmit="return confirm('Удалить {{ $hall->name }}?');">
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
                                    Залы не найдены. <a href="{{ route('admin.halls.create') }}" class="text-indigo-600 hover:underline">Создайте первый зал</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-admin-layout>

