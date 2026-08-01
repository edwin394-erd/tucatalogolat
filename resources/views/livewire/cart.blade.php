<div class="min-h-screen pb-10 px-4 sm:px-6 lg:px-8" style="background-color: var(--bg-main); color: var(--text-primary);">
    <x-alert alert_type="success" />
    <div class="max-w-6xl mx-auto py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold">{{ __('messages.cart') }}</h1>
                <p class="text-sm text-gray-600">{{ __('messages.cart_description') }}</p>
            </div>
            <a href="{{ route('catalogo', $catalogo->name) }}" class="px-4 py-2 rounded-full bg-white text-black shadow hover:bg-gray-100">{{ __('messages.continue_shopping') }}</a>
        </div>

        @if($cart->items->isEmpty())
            <div class="rounded-3xl bg-white p-10 text-center shadow">
                <h2 class="text-xl font-semibold mb-2">{{ __('messages.cart_empty') }}</h2>
                <p class="text-gray-500">{{ __('messages.cart_empty_description') }}</p>
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="rounded-3xl bg-white shadow p-6">
                    <div class="space-y-4">
                        @foreach($cart->items as $item)
                            <div class="flex flex-col gap-4 rounded-3xl border border-gray-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="h-20 w-20 overflow-hidden rounded-3xl bg-gray-100">
                                        @if($item->product && $item->product->fotos->first())
                                            <img src="{{ asset('storage/' . $item->product->fotos->first()->url) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover" />
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">{{ $item->product->name ?? __('messages.product_not_available') }}</h3>
                                        @if($item->variant)
                                            <p class="text-sm text-gray-500">Variante: {{ $item->variant->size }} {{ $item->variant->color }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500">{{ $item->product->short_description ?? str($item->product->description ?? '')->limit(60) }}</p>
                                        <p class="mt-2 text-sm font-semibold">
                                            @php
                                                $price = $item->price;
                                                if ($item->variant) {
                                                    $price += $item->variant->price_adjustment;
                                                }
                                            @endphp
                                            ${{ number_format($price, 2) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button @click="window.dispatchEvent(new CustomEvent('cart-updated', { detail: { delta: -1 } }))" wire:click="decreaseQuantity({{ $item->id }})" class="px-3 py-2 rounded-xl border border-gray-300">-</button>
                                    <span class="w-10 text-center">{{ $item->quantity }}</span>
                                    <button @click="window.dispatchEvent(new CustomEvent('cart-updated', { detail: { delta: 1 } }))" wire:click="increaseQuantity({{ $item->id }})" class="px-3 py-2 rounded-xl border border-gray-300">+</button>
                                    <button @click="window.dispatchEvent(new CustomEvent('cart-updated', { detail: { delta: -{{ $item->quantity }} } }))" wire:click="removeItem({{ $item->id }})" class="px-3 py-2 rounded-xl bg-red-600 text-white">{{ __('messages.remove') }}</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl bg-white shadow p-6 space-y-6">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-500">{{ __('messages.cart_summary') }}</p>
                        <div class="flex items-center justify-between text-lg font-semibold">
                            <span>{{ __('messages.subtotal') }}</span>
                            <span>${{ number_format($cart->total, 2) }}</span>
                        </div>
                    </div>
                    <button wire:click="checkout" class="w-full rounded-3xl bg-indigo-600 px-4 py-3 text-white font-semibold hover:bg-indigo-700">{{ __('messages.checkout') }}</button>
                    <button @click="window.dispatchEvent(new CustomEvent('cart-reset'))" wire:click="clearCart" class="w-full rounded-3xl border border-gray-300 px-4 py-3 text-gray-700">{{ __('messages.clear_cart') }}</button>
                </div>
            </div>
        @endif
    </div>
</div>
