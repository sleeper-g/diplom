<x-admin-layout title="Создать фильм">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Новый фильм</h2>
        </header>
        <div class="conf-step__wrapper">
            <form method="POST" action="{{ route('admin.movies.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="title" :value="__('Название фильма')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Описание')" />
                    <textarea id="description"
                              name="description"
                              rows="5"
                              class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              required>{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="duration" :value="__('Длительность (минуты)')" />
                        <x-text-input id="duration" class="block mt-1 w-full" type="number" name="duration" :value="old('duration')" min="1" max="300" required />
                        <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.movies.index') }}" class="conf-step__button conf-step__button-regular">
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

