<x-admin-layout title="Настройка цен">
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Цены на билеты</h2>
        </header>
        <div class="conf-step__wrapper">
            <form method="POST" action="{{ route('admin.prices.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
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
                    <p class="mt-1 text-sm text-gray-500">
                        Стоимость стандартного кресла
                    </p>
                </div>

                <div>
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
                    <p class="mt-1 text-sm text-gray-500">
                        Стоимость VIP кресла
                    </p>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="conf-step__button conf-step__button-regular">Отмена</a>
                    <button type="submit" class="conf-step__button conf-step__button-accent">Сохранить цены</button>
                </div>
            </form>

            <div class="mt-8 p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-2">Информация</h3>
                <p class="text-sm text-gray-600 mb-2">
                    Обновленные значения автоматически подставляются во все новые бронирования.
                </p>
                <p class="text-sm text-gray-600">
                    Уже оформленные билеты сохраняют цену, по которой были приобретены.
                </p>
            </div>
        </div>
    </section>
</x-admin-layout>

