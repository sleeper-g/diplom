<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Редактировать зал') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.halls.update', $hall->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Название зала')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $hall->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Rows -->
                        <div class="mb-4">
                            <x-input-label for="rows" :value="__('Количество рядов')" />
                            <x-text-input id="rows" class="block mt-1 w-full" type="number" name="rows" :value="old('rows', $hall->rows)" min="1" max="20" required />
                            <x-input-error :messages="$errors->get('rows')" class="mt-2" />
                        </div>

                        <!-- Seats Per Row -->
                        <div class="mb-4">
                            <x-input-label for="seats_per_row" :value="__('Мест в ряду')" />
                            <x-text-input id="seats_per_row" class="block mt-1 w-full" type="number" name="seats_per_row" :value="old('seats_per_row', $hall->seats_per_row)" min="1" max="30" required />
                            <x-input-error :messages="$errors->get('seats_per_row')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.halls.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Отмена
                            </a>
                            <x-primary-button>
                                {{ __('Сохранить') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

