<x-admin-layout title="Создать зал">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Новый зал</h2>
        </header>
        <div class="conf-step__wrapper">
            <form method="POST" action="{{ route('admin.halls.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Название зала')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="rows" :value="__('Количество рядов')" />
                        <x-text-input id="rows" class="block mt-1 w-full" type="number" name="rows" :value="old('rows')" min="1" max="20" required />
                        <x-input-error :messages="$errors->get('rows')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="seats_per_row" :value="__('Мест в ряду')" />
                        <x-text-input id="seats_per_row" class="block mt-1 w-full" type="number" name="seats_per_row" :value="old('seats_per_row')" min="1" max="30" required />
                        <x-input-error :messages="$errors->get('seats_per_row')" class="mt-2" />
                    </div>
                </div>

                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Сразу открыть продажи</span>
                </label>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.halls.index') }}" class="conf-step__button conf-step__button-regular">
                        Отмена
                    </a>
                    <button type="submit" class="conf-step__button conf-step__button-accent">
                        Создать
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>

