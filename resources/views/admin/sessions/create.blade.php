<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Создать сеанс') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.sessions.store') }}">
                        @csrf

                        <!-- Movie -->
                        <div class="mb-4">
                            <x-input-label for="movie_id" :value="__('Фильм')" />
                            <select id="movie_id" 
                                    name="movie_id" 
                                    class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                                    required>
                                <option value="">Выберите фильм</option>
                                @foreach($movies ?? [] as $movie)
                                    <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                                        {{ $movie->title }} ({{ $movie->duration }} мин)
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('movie_id')" class="mt-2" />
                        </div>

                        <!-- Hall -->
                        <div class="mb-4">
                            <x-input-label for="hall_id" :value="__('Зал')" />
                            <select id="hall_id" 
                                    name="hall_id" 
                                    class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                                    required>
                                <option value="">Выберите зал</option>
                                @foreach($halls ?? [] as $hall)
                                    <option value="{{ $hall->id }}" {{ old('hall_id') == $hall->id ? 'selected' : '' }}>
                                        {{ $hall->name }} ({{ $hall->rows }}x{{ $hall->seats_per_row }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('hall_id')" class="mt-2" />
                        </div>

                        <!-- Start Time -->
                        <div class="mb-4">
                            <x-input-label for="start_time" :value="__('Дата и время начала')" />
                            <x-text-input id="start_time" 
                                          class="block mt-1 w-full" 
                                          type="datetime-local" 
                                          name="start_time" 
                                          :value="old('start_time')" 
                                          required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <!-- End Time -->
                        <div class="mb-4">
                            <x-input-label for="end_time" :value="__('Дата и время окончания')" />
                            <x-text-input id="end_time" 
                                          class="block mt-1 w-full" 
                                          type="datetime-local" 
                                          name="end_time" 
                                          :value="old('end_time')" 
                                          required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.sessions.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Отмена
                            </a>
                            <x-primary-button>
                                {{ __('Создать') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

