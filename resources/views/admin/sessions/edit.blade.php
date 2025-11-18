<x-admin-layout title="Редактировать сеанс">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Сеанс #{{ $session->id }}</h2>
        </header>
        <div class="conf-step__wrapper">
            <form method="POST" action="{{ route('admin.sessions.update', $session->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="movie_id" :value="__('Фильм')" />
                    <select id="movie_id"
                            name="movie_id"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        <option value="">Выберите фильм</option>
                        @foreach($movies ?? [] as $movie)
                            <option value="{{ $movie->id }}" {{ old('movie_id', $session->movie_id) == $movie->id ? 'selected' : '' }}>
                                {{ $movie->title }} ({{ $movie->duration }} мин)
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('movie_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="hall_id" :value="__('Зал')" />
                    <select id="hall_id"
                            name="hall_id"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        <option value="">Выберите зал</option>
                        @foreach($halls ?? [] as $hall)
                            <option value="{{ $hall->id }}" {{ old('hall_id', $session->hall_id) == $hall->id ? 'selected' : '' }}>
                                {{ $hall->name }} ({{ $hall->rows }} × {{ $hall->seats_per_row }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('hall_id')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="start_time" :value="__('Дата и время начала')" />
                        <x-text-input id="start_time"
                                      class="block mt-1 w-full"
                                      type="datetime-local"
                                      name="start_time"
                                      :value="old('start_time', \Carbon\Carbon::parse($session->start_time)->format('Y-m-d\TH:i'))"
                                      required />
                        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="end_time" :value="__('Дата и время окончания')" />
                        <x-text-input id="end_time"
                                      class="block mt-1 w-full"
                                      type="datetime-local"
                                      name="end_time"
                                      :value="old('end_time', \Carbon\Carbon::parse($session->end_time)->format('Y-m-d\TH:i'))"
                                      required />
                        <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.sessions.index') }}" class="conf-step__button conf-step__button-regular">
                        Отмена
                    </a>
                    <button type="submit" class="conf-step__button conf-step__button-accent">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
