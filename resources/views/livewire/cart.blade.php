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
                $selectionIds = $ci->variant_selections ?: ($ci->variant_id ? [$ci->variant_id] : []);
                $lineKey = $ci->product_id . ':' . implode('-', $selectionIds ?: ['0']);
                $itemsPayload[$lineKey] = [
                    'cart_item_id' => $ci->id,
                    'product_id' => $ci->product_id,
                    'quantity' => $ci->quantity,
                    'name' => $product?->name,
                    'short_description' => $product?->short_description ?? 
                        (strlen((string)($product?->description ?? '')) ? 
                            \Illuminate\Support\Str::limit($product->description, 60) : ''),
                    'price' => $ci->price,
                    'image' => $product && $product->fotos->first() ? asset('storage/' . $product->fotos->first()->url) : null,
                    'variant' => $ci->variant ? ['name' => $ci->variant->name, 'size' => $ci->variant->size, 'color' => $ci->variant->color] : null,
                    'variant_description' => $ci->variant_description,
                    'variant_id' => $ci->variant_id,
                    'variant_selections' => $selectionIds,
                ];
            }
        @endphp

        <div x-data="cartPage()" x-init="init()">
            <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">

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
                                        <p x-show="itemsData[pid] && itemsData[pid].variant_description" class="mt-1 text-xs font-medium" style="color: var(--text-primary); opacity: 0.75;" x-text="itemsData[pid] ? itemsData[pid].variant_description : ''"></p>
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
                    <button @click="openCheckout()" class="w-full rounded-3xl px-4 py-3 text-sm sm:text-base font-semibold hover:opacity-90 transition" style="background-color: var(--primary-btn); color: {{ $iconColor }};">{{ __('messages.checkout') }}</button>
                    <button @click="clear()" class="w-full rounded-3xl border px-4 py-3 text-sm sm:text-base font-medium" style="border-color: var(--primary-btn); background-color: transparent; color: var(--text-secondary);">{{ __('messages.clear_cart') }}</button>
                </div>
            </div>
            </div>

        <div x-show="checkoutOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" @keydown.escape.window="checkoutOpen = false">
            <div @click.outside="checkoutOpen = false" class="w-full max-w-md rounded-3xl p-5 sm:p-6 shadow-2xl" style="background-color: var(--bg-card-aside); color: var(--text-secondary);">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-lg font-bold">Datos para el pedido</h2>
                    <button type="button" @click="checkoutOpen = false" class="text-2xl leading-none opacity-60 hover:opacity-100" aria-label="Cerrar">&times;</button>
                </div>
                <p class="mt-1 text-sm opacity-70">Completa tus datos antes de enviar el pedido por WhatsApp.</p>

                <form class="mt-5 space-y-3" @submit.prevent="submitCheckout()">
                    <label class="block text-sm font-medium">
                        Nombre completo
                        <input x-model.trim="customerName" required maxlength="120" type="text" class="mt-1 w-full rounded-2xl border-0 px-4 py-3 outline-none focus:ring-2" style="background-color: var(--bg-main); color: var(--text-primary); --tw-ring-color: var(--primary-btn);">
                    </label>
                    <label class="block text-sm font-medium">
                        Teléfono
                        <input x-model.trim="customerPhone" required maxlength="40" type="tel" class="mt-1 w-full rounded-2xl border-0 px-4 py-3 outline-none focus:ring-2" style="background-color: var(--bg-main); color: var(--text-primary); --tw-ring-color: var(--primary-btn);">
                    </label>
                    <label class="block text-sm font-medium">
                        Nota <span class="font-normal opacity-60">(opcional)</span>
                        <textarea x-model.trim="customerNotes" maxlength="1000" rows="3" class="mt-1 w-full resize-none rounded-2xl border-0 px-4 py-3 outline-none focus:ring-2" style="background-color: var(--bg-main); color: var(--text-primary); --tw-ring-color: var(--primary-btn);"></textarea>
                    </label>
                    <p x-show="checkoutError" x-text="checkoutError" class="text-sm font-medium text-red-600"></p>
                    <button type="submit" :disabled="checkoutLoading" class="w-full rounded-3xl px-4 py-3 font-semibold transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-60" style="background-color: var(--primary-btn); color: {{ $iconColor }};">
                        <span x-show="!checkoutLoading">Enviar pedido por WhatsApp</span>
                        <span x-show="checkoutLoading">Registrando pedido...</span>
                    </button>
                </form>
            </div>
        </div>

        <script>
            function cartPage(){
                return {
                    itemsData: {!! json_encode($itemsPayload) !!},
                    checkoutOpen: false,
                    checkoutLoading: false,
                    checkoutError: '',
                    customerName: '',
                    customerPhone: '',
                    customerNotes: '',
                    init(){
                        var routeName = '{{ $catalogo->name_handle ?? $catalogo->name }}';
                        var key = 'cart_v2_' + (routeName || 'global');
                        if (!window.Alpine) return;

                        if (!Alpine.store || !Alpine.store('cart')) {
                            Alpine.store('cart', {
                                items: JSON.parse(localStorage.getItem(key) || '{}'),
                                products: JSON.parse(localStorage.getItem(key + '_products') || '{}'),
                                variants: JSON.parse(localStorage.getItem(key + '_variants') || '{}'),
                                selections: JSON.parse(localStorage.getItem(key + '_selections') || '{}'),
                                save: function(){ localStorage.setItem(key, JSON.stringify(this.items)); localStorage.setItem(key + '_products', JSON.stringify(this.products)); localStorage.setItem(key + '_variants', JSON.stringify(this.variants)); localStorage.setItem(key + '_selections', JSON.stringify(this.selections)); },
                                count: function(){ return Object.values(this.items).reduce(function(a,b){ return a + (Number(b)||0); }, 0); },
                                quantityFor: function(id){ id = String(id); var self = this; return Object.keys(this.items).reduce(function(total, lineKey){ return total + (String(self.products[lineKey] || lineKey.split(':')[0]) === id ? (Number(self.items[lineKey]) || 0) : 0); }, 0); },
                                hydrate: function(lines){ this.items = {}; this.products = {}; this.variants = {}; this.selections = {}; (lines || []).forEach(function(line){ var lineKey = line.line_key || (line.product_id + ':' + (line.variant_id || '0')); this.items[lineKey] = Number(line.quantity) || 0; this.products[lineKey] = Number(line.product_id); if (line.variant_id) this.variants[lineKey] = Number(line.variant_id); if (line.variant_selections) this.selections[lineKey] = line.variant_selections; }, this); this.save(); },
                                _syncTimer: null,
                                _scheduleSync: function(){ var self = this; if (self._syncTimer) clearTimeout(self._syncTimer); self._syncTimer = setTimeout(function(){ try { var token = (document.querySelector('meta[name="csrf-token"]')||{}).getAttribute('content')||''; fetch('/' + routeName + '/cart-sync', { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ items: self.items, products: self.products, variants: self.variants, selections: self.selections }) }).then(function(r){ return r.json(); }).then(function(data){ if (data.items && self.hydrate) self.hydrate(data.items); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } })); }).catch(function(){}); } catch(e){} }, 400); },
                                findKey: function(id){ id = String(id); return Object.keys(this.items).find(function(lineKey){ return String(this.products[lineKey] || lineKey.split(':')[0]) === id; }, this); },
                                add: function(id, variantId, selectionIds){ id = String(id); selectionIds = (selectionIds || (variantId ? [variantId] : [])).map(Number).sort(function(a,b){ return a-b; }); var lineKey = id + ':' + (selectionIds.join('-') || '0'); this.items[lineKey] = (this.items[lineKey]||0) + 1; this.products[lineKey] = Number(id); if (variantId) this.variants[lineKey] = Number(variantId); this.selections[lineKey] = selectionIds; this.save(); this._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count() } })); },
                                increase: function(id){ id = String(id); var lineKey = this.items[id] ? id : this.findKey(id); if (lineKey) { this.items[lineKey] += 1; this.save(); this._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count() } })); } else this.add(id); },
                                decrease: function(id){ id = String(id); var lineKey = this.items[id] ? id : this.findKey(id); if (!lineKey) return; this.items[lineKey] -= 1; if (this.items[lineKey] <= 0) { delete this.items[lineKey]; delete this.products[lineKey]; delete this.variants[lineKey]; delete this.selections[lineKey]; } this.save(); this._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count() } })); }
                            });

                            try {
                                var s = Alpine.store('cart');
                                var serverLines = Object.keys(this.itemsData).map(function(lineKey) {
                                    var line = this.itemsData[lineKey];
                                    return {
                                        line_key: lineKey,
                                        product_id: line.product_id,
                                        variant_id: line.variant_id,
                                        variant_selections: line.variant_selections,
                                        quantity: line.quantity,
                                    };
                                }, this);
                                if (s.hydrate) s.hydrate(serverLines);
                            } catch(e){}
                            window.cartAdd = window.cartAdd || function(id, variantId, selectionIds){ try { if (Alpine.store('cart')) Alpine.store('cart').add(id, variantId, selectionIds); } catch(e){} };
                            window.cartIncrease = window.cartIncrease || function(id){ try { if (Alpine.store('cart')) Alpine.store('cart').increase(id); } catch(e){} };
                            window.cartDecrease = window.cartDecrease || function(id){ try { if (Alpine.store('cart')) Alpine.store('cart').decrease(id); } catch(e){} };
                            window.cartReset = window.cartReset || function(){ try { if (Alpine.store('cart')) { Alpine.store('cart').items = {}; if (typeof Alpine.store('cart').save === 'function') Alpine.store('cart').save(); } window.dispatchEvent(new CustomEvent('cart-reset')); window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: 0 } })); } catch(e){} };
                            window.cartSyncNow = window.cartSyncNow || function(){ try { var token = (document.querySelector('meta[name="csrf-token"]')||{}).getAttribute('content')||''; return fetch('/' + routeName + '/cart-sync', { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ items: Alpine.store('cart').items, products: Alpine.store('cart').products, variants: Alpine.store('cart').variants, selections: Alpine.store('cart').selections }) }).then(function(r){ return r.json(); }).then(function(data){ if (data.items && Alpine.store('cart').hydrate) Alpine.store('cart').hydrate(data.items); window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: data.count } })); return data; }).catch(function(){ return Promise.resolve(); }); } catch(e){ return Promise.resolve(); } };
                        }

                        window.addEventListener('cart-updated', () => { /* reactive via store */ });
                        window.addEventListener('cart-reset', () => { try { if (Alpine.store('cart')) { Alpine.store('cart').items = {}; Alpine.store('cart').save(); } } catch(e){} });
                    },
                    increase(pid){ window.cartIncrease(pid); },
                    decrease(pid){ window.cartDecrease(pid); },
                    remove(pid){ try { if (Alpine.store('cart')) { delete Alpine.store('cart').items[pid]; delete Alpine.store('cart').products[pid]; delete Alpine.store('cart').variants[pid]; Alpine.store('cart').save(); Alpine.store('cart')._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: Alpine.store('cart').count() } })); } } catch(e){} },
                    clear(){ if (window.cartReset) window.cartReset(); },
                    total(){ var t = 0; if (!(Alpine.store && Alpine.store('cart'))) return 0; var items = Alpine.store('cart').items; for (var pid in items){ var q = Number(items[pid]||0); var price = this.itemsData[pid] ? Number(this.itemsData[pid].price) : 0; t += q * price; } return t; },
                    openCheckout(){
                        if (!Alpine.store('cart') || Object.keys(Alpine.store('cart').items || {}).length === 0) return;
                        this.checkoutError = '';
                        this.checkoutOpen = true;
                    },
                    submitCheckout(){
                        var routeName = '{{ $catalogo->name_handle ?? $catalogo->name }}';
                        var token = (document.querySelector('meta[name="csrf-token"]') || {}).getAttribute('content') || '';
                        this.checkoutLoading = true;
                        this.checkoutError = '';

                        var doCheckout = () => fetch('/' + routeName + '/checkout', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({
                                    customer_name: this.customerName,
                                    customer_phone: this.customerPhone,
                                    customer_notes: this.customerNotes
                                })
                            })
                            .then(response => response.json().then(data => ({ response: response, data: data })))
                            .then(({ response, data }) => {
                                if (!response.ok) {
                                    const errors = data.errors ? Object.values(data.errors).flat() : [];
                                    throw new Error(errors[0] || data.message || 'No se pudo registrar el pedido.');
                                }
                                if (data && data.url) {
                                    window.location.href = data.url;
                                }
                            });

                        var sync = window.cartSyncNow ? window.cartSyncNow() : Promise.resolve();
                        sync.then(doCheckout)
                            .catch(error => {
                                this.checkoutLoading = false;
                                this.checkoutError = error.message || 'No se pudo registrar el pedido.';
                            });
                    }
                }
            }
        </script>
    </div>
</div>