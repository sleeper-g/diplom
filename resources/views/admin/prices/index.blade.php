<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Настройка цен') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.prices.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Regular Price -->
                        <div class="mb-6">
                            <x-input-label for="regular_price" :value="__('Цена обычного места (₽)')" />
                            <x-text-input id="regular_price" 
                                          class="block mt-1 w-full" 
                                          type="number" 
                                          name="regular_price" 
                                          :value="old('regular_price', $regularPrice ?? 500)" 
                                          min="0" 
                                          step="50"
                                          required />
                            <x-input-error :messages="$errors->get('regular_price')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Цена для обычных мест в зале
                            </p>
                        </div>

                        <!-- VIP Price -->
                        <div class="mb-6">
                            <x-input-label for="vip_price" :value="__('Цена VIP места (₽)')" />
                            <x-text-input id="vip_price" 
                                          class="block mt-1 w-full" 
                                          type="number" 
                                          name="vip_price" 
                                          :value="old('vip_price', $vipPrice ?? 1000)" 
                                          min="0" 
                                          step="50"
                                          required />
                            <x-input-error :messages="$errors->get('vip_price')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Цена для VIP мест в зале
                            </p>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Сохранить цены') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Price Info -->
                    <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Информация о ценах
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Установленные цены будут применяться ко всем новым бронированиям.
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Существующие бронирования не будут затронуты изменением цен.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

