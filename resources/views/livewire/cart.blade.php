@section('title', ($catalogo->name ?? 'Catalogo') . ' - Carrito')
@section('description', 'Revisa tu carrito de compras en ' . ($catalogo->name ?? 'este catálogo') . '.')
@section('og_title', ($catalogo->name ?? 'Catalogo') . ' - Carrito')
@section('og_image', $catalogo->logo_url ? asset('storage/' . $catalogo->logo_url) : asset('imgs/icono.ico'))
@section('canonical', route('catalogo.cart', $catalogo->name_handle))

@php
    $pColor = $catalogo->theme->primary_color ?? '#4F46E5';
    $bgColor = $catalogo->theme->bg_color ?? '#F2F2F2';
    $sColor = $catalogo->theme->secondary_color ?? '#f0f0f0';
    $pFont = $catalogo->theme->primary_font_color ?? '#333333';
    $sFont = $catalogo->theme->secondary_font_color ?? '#666666';

    function isDarkColorCart($hex) {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        return (($r * 299 + $g * 587 + $b * 114) / 1000) < 128;
    }

    $iconColor = isDarkColorCart($pColor) ? '#ffffff' : '#000000';
@endphp

<div class="min-h-screen pb-10 px-4 sm:px-6 lg:px-8"
     style="--primary-btn: {{ $pColor }};
            --bg-main: {{ $bgColor }};
            --bg-card-aside: {{ $sColor }};
            --text-primary: {{ $pFont }};
            --text-secondary: {{ $sFont }};
            background-color: var(--bg-main); color: var(--text-primary);">
    <x-alert alert_type="success" />
    <div class="max-w-6xl mx-auto py-6 sm:py-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold leading-tight" style="color: var(--text-primary);">{{ __('messages.cart') }}</h1>
                <p class="text-sm mt-1" style="color: var(--text-primary); opacity: 0.65;">{{ __('messages.cart_description') }}</p>
            </div>
            <a href="{{ route('catalogo', $catalogo->name_handle) }}" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-full shadow hover:opacity-90 transition text-sm sm:text-base" style="background-color: var(--primary-btn); color: {{ $iconColor }};">{{ __('messages.continue_shopping') }}</a>
        </div>

        @php
            $itemsPayload = [];
            foreach ($cart->items as $ci) {
                $product = $ci->product;
                $itemsPayload[$ci->product_id] = [
                    'cart_item_id' => $ci->id,
                    'product_id' => $ci->product_id,
                    'quantity' => $ci->quantity,
                    'name' => $product?->name,
                    'short_description' => $product?->short_description ?? 
                        (strlen((string)($product?->description ?? '')) ? 
                            \Illuminate\Support\Str::limit($product->description, 60) : ''),
                    'price' => $ci->price,
                    'image' => $product && $product->fotos->first() ? asset('storage/' . $product->fotos->first()->url) : null,
                    'variant' => $ci->variant ? ['size' => $ci->variant->size, 'color' => $ci->variant->color] : null,
                ];
            }
        @endphp

        <div x-data="cartPage()" x-init="init()" class="grid gap-6 lg:grid-cols-[2fr_1fr]">

            {{-- Lista de productos: fondo bg-card-aside → todo su texto va en text-secondary --}}
            <div class="rounded-3xl shadow p-4 sm:p-6" style="background-color: var(--bg-card-aside); color: var(--text-secondary);">

                <template x-if="Object.keys(Alpine.store('cart') ? Alpine.store('cart').items : {}).length === 0">
                    <div class="rounded-3xl p-6 sm:p-10 text-center shadow" style="background-color: var(--bg-main); color: var(--text-primary);">
                        <h2 class="text-lg sm:text-xl font-semibold mb-2" style="color: var(--text-primary);">{{ __('messages.cart_empty') }}</h2>
                        <p class="text-sm sm:text-base" style="color: var(--text-primary); opacity: 0.7;">{{ __('messages.cart_empty_description') }}</p>
                    </div>
                </template>

                <template x-if="Object.keys(Alpine.store('cart') ? Alpine.store('cart').items : {}).length > 0">
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-wide opacity-60" style="color: var(--text-secondary);">
                            {{ __('messages.cart_items') ?? 'Productos' }}
                        </p>

                        <template x-for="(qty, pid) in Alpine.store('cart').items" :key="pid">
                            {{-- Cada item tiene su propio fondo bg-main → su texto va en text-primary --}}
                            <div class="flex flex-col gap-4 rounded-3xl border p-3 sm:p-4" style="background-color: var(--bg-main); border-color: color-mix(in srgb, var(--primary-btn) 20%, transparent); color: var(--text-primary);">
                                <div class="flex items-start gap-3 sm:gap-4">
                                    <div class="h-16 w-16 sm:h-20 sm:w-20 shrink-0 overflow-hidden rounded-2xl sm:rounded-3xl bg-gray-100">
                                        <img x-bind:src="itemsData[pid] ? itemsData[pid].image : ''" x-show="itemsData[pid] && itemsData[pid].image" class="h-full w-full object-cover" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-semibold text-sm sm:text-base line-clamp-2" style="color: var(--text-primary);" x-text="itemsData[pid] ? itemsData[pid].name : 'Producto'">Producto</h3>
                                        <p class="text-xs sm:text-sm mt-1 line-clamp-2" style="color: var(--text-primary); opacity: 0.65;" x-text="itemsData[pid] ? itemsData[pid].short_description : ''"></p>
                                        <p class="mt-2 text-sm sm:text-base font-semibold" style="color: var(--text-primary);">$<span x-text="(itemsData[pid] ? Number(itemsData[pid].price).toFixed(2) : '0.00')"></span></p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-2 sm:justify-end">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <button @click="decrease(pid)" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border text-lg leading-none" style="border-color: var(--primary-btn); background-color: var(--bg-card-aside); color: var(--text-secondary);">−</button>
                                        <span class="min-w-[2rem] text-center text-sm sm:text-base font-semibold" style="color: var(--text-primary);" x-text="Alpine.store('cart').items[pid]">0</span>
                                        <button @click="increase(pid)" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border text-lg leading-none" style="border-color: var(--primary-btn); background-color: var(--primary-btn); color: {{ $iconColor }};">+</button>
                                    </div>
                                    <button @click="remove(pid)" class="px-3 py-2 rounded-xl text-xs sm:text-sm font-medium text-white" style="background-color: color-mix(in srgb, var(--primary-btn) 10%, #DC2626 90%);">{{ __('messages.remove') }}</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Resumen: fondo bg-card-aside → todo su texto va en text-secondary --}}
            <div class="rounded-3xl shadow p-4 sm:p-6 h-fit lg:sticky lg:top-6" style="background-color: var(--bg-card-aside); color: var(--text-secondary);">
                <h2 class="text-sm font-semibold uppercase tracking-wide opacity-60" style="color: var(--text-secondary);">
                    {{ __('messages.cart_summary') }}
                </h2>

                <div class="mt-4 flex items-center justify-between text-base sm:text-lg font-semibold" style="color: var(--text-secondary);">
                    <span>{{ __('messages.subtotal') }}</span>
                    <span>$<span x-text="total().toFixed(2)">0.00</span></span>
                </div>

                <div class="mt-5 space-y-3">
                    <button @click="checkout()" class="w-full rounded-3xl px-4 py-3 text-sm sm:text-base font-semibold hover:opacity-90 transition" style="background-color: var(--primary-btn); color: {{ $iconColor }};">{{ __('messages.checkout') }}</button>
                    <button @click="clear()" class="w-full rounded-3xl border px-4 py-3 text-sm sm:text-base font-medium" style="border-color: var(--primary-btn); background-color: transparent; color: var(--text-secondary);">{{ __('messages.clear_cart') }}</button>
                </div>
            </div>
        </div>

        <script>
            function cartPage(){
                return {
                    itemsData: {!! json_encode($itemsPayload) !!},
                    init(){
                        var routeName = '{{ $catalogo->name }}';
                        var key = 'cart_' + (routeName || 'global');
                        if (!window.Alpine) return;

                        if (!Alpine.store || !Alpine.store('cart')) {
                            Alpine.store('cart', {
                                items: JSON.parse(localStorage.getItem(key) || '{}'),
                                save: function(){ localStorage.setItem(key, JSON.stringify(this.items)); },
                                count: function(){ return Object.values(this.items).reduce(function(a,b){ return a + (Number(b)||0); }, 0); },
                                _syncTimer: null,
                                _scheduleSync: function(){ var self = this; if (self._syncTimer) clearTimeout(self._syncTimer); self._syncTimer = setTimeout(function(){ try { var token = (document.querySelector('meta[name="csrf-token"]')||{}).getAttribute('content')||''; fetch('/' + routeName + '/cart-sync', { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ items: self.items }) }).then(function(r){ return r.json(); }).then(function(data){ window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } })); }).catch(function(){}); } catch(e){} }, 400); },
                                add: function(id){ id = String(id); this.items[id] = (this.items[id]||0) + 1; this.save(); this._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count() } })); },
                                increase: function(id){ this.add(id); },
                                decrease: function(id){ id = String(id); if (!this.items[id]) return; this.items[id] = (this.items[id]||0) - 1; if (this.items[id] <= 0) delete this.items[id]; this.save(); this._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count() } })); }
                            });

                            try {
                                var s = Alpine.store('cart');
                                if (Object.keys(s.items || {}).length === 0) {
                                    var seeded = {};
                                    for (var k in this.itemsData) {
                                        if (this.itemsData[k] && this.itemsData[k].quantity) seeded[k] = this.itemsData[k].quantity;
                                    }
                                    if (Object.keys(seeded).length > 0) { s.items = seeded; s.save(); }
                                }
                            } catch(e){}
                            window.cartAdd = window.cartAdd || function(id){ try { if (Alpine.store('cart')) Alpine.store('cart').add(id); } catch(e){} };
                            window.cartIncrease = window.cartIncrease || function(id){ try { if (Alpine.store('cart')) Alpine.store('cart').increase(id); } catch(e){} };
                            window.cartDecrease = window.cartDecrease || function(id){ try { if (Alpine.store('cart')) Alpine.store('cart').decrease(id); } catch(e){} };
                            window.cartReset = window.cartReset || function(){ try { if (Alpine.store('cart')) { Alpine.store('cart').items = {}; if (typeof Alpine.store('cart').save === 'function') Alpine.store('cart').save(); } window.dispatchEvent(new CustomEvent('cart-reset')); window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: 0 } })); } catch(e){} };
                            window.cartSyncNow = window.cartSyncNow || function(){ try { var token = (document.querySelector('meta[name="csrf-token"]')||{}).getAttribute('content')||''; return fetch('/' + routeName + '/cart-sync', { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ items: Alpine.store('cart').items }) }).then(function(r){ return r.json(); }).then(function(data){ window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: data.count } })); return data; }).catch(function(){ return Promise.resolve(); }); } catch(e){ return Promise.resolve(); } };
                        }

                        window.addEventListener('cart-updated', () => { /* reactive via store */ });
                        window.addEventListener('cart-reset', () => { try { if (Alpine.store('cart')) { Alpine.store('cart').items = {}; Alpine.store('cart').save(); } } catch(e){} });
                    },
                    increase(pid){ window.cartIncrease(pid); },
                    decrease(pid){ window.cartDecrease(pid); },
                    remove(pid){ try { if (Alpine.store('cart')) { delete Alpine.store('cart').items[pid]; Alpine.store('cart').save(); Alpine.store('cart')._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: Alpine.store('cart').count() } })); } } catch(e){} },
                    clear(){ if (window.cartReset) window.cartReset(); },
                    total(){ var t = 0; if (!(Alpine.store && Alpine.store('cart'))) return 0; var items = Alpine.store('cart').items; for (var pid in items){ var q = Number(items[pid]||0); var price = this.itemsData[pid] ? Number(this.itemsData[pid].price) : 0; t += q * price; } return t; },
                    checkout(){
                        var routeName = '{{ $catalogo->name_handle ?? $catalogo->name }}';
                        var token = (document.querySelector('meta[name="csrf-token"]') || {}).getAttribute('content') || '';

                        function doCheckout(){
                            fetch('/' + routeName + '/checkout', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }
                            })
                            .then(function(response){ return response.json(); })
                            .then(function(data){
                                if (data && data.url) {
                                    window.location.href = data.url;
                                }
                            })
                            .catch(function(){
                                window.location.reload();
                            });
                        }

                        if (window.cartSyncNow) {
                            window.cartSyncNow().then(doCheckout);
                        } else {
                            doCheckout();
                        }
                    }
                }
            }
        </script>
    </div>
</div>