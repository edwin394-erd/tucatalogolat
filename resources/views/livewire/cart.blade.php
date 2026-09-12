<div class="min-h-screen pb-10 px-4 sm:px-6 lg:px-8" style="background-color: var(--bg-main); color: var(--text-primary);">
    <x-alert alert_type="success" />
    <div class="max-w-6xl mx-auto py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold">{{ __('messages.cart') }}</h1>
                <p class="text-sm text-gray-600">{{ __('messages.cart_description') }}</p>
            </div>
            <a href="{{ route('catalogo', $catalogo->name_handle) }}" class="px-4 py-2 rounded-full bg-white text-black shadow hover:bg-gray-100">{{ __('messages.continue_shopping') }}</a>
        </div>

        @php
            // Prepare a lightweight items payload for the client to render immediately
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
            <div class="rounded-3xl bg-white shadow p-6">
                <div class="space-y-4">
                    <template x-if="Object.keys(Alpine.store('cart') ? Alpine.store('cart').items : {}).length === 0">
                        <div class="rounded-3xl bg-white p-10 text-center shadow">
                            <h2 class="text-xl font-semibold mb-2">{{ __('messages.cart_empty') }}</h2>
                            <p class="text-gray-500">{{ __('messages.cart_empty_description') }}</p>
                        </div>
                    </template>

                    <template x-if="Object.keys(Alpine.store('cart') ? Alpine.store('cart').items : {}).length > 0">
                        <template x-for="(qty, pid) in Alpine.store('cart').items" :key="pid">
                            <div class="flex flex-col gap-4 rounded-3xl border border-gray-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="h-20 w-20 overflow-hidden rounded-3xl bg-gray-100">
                                        <img x-bind:src="itemsData[pid] ? itemsData[pid].image : ''" x-show="itemsData[pid] && itemsData[pid].image" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <h3 class="font-semibold" x-text="itemsData[pid] ? itemsData[pid].name : 'Producto'">Producto</h3>
                                        <p class="text-sm text-gray-500" x-text="itemsData[pid] ? itemsData[pid].short_description : ''"></p>
                                        <p class="mt-2 text-sm font-semibold">${{ "" }}<span x-text="(itemsData[pid] ? Number(itemsData[pid].price).toFixed(2) : '0.00')"></span></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button @click="decrease(pid)" class="px-3 py-2 rounded-xl border border-gray-300">-</button>
                                    <span class="w-10 text-center" x-text="Alpine.store('cart').items[pid]">0</span>
                                    <button @click="increase(pid)" class="px-3 py-2 rounded-xl border border-gray-300">+</button>
                                    <button @click="remove(pid)" class="px-3 py-2 rounded-xl bg-red-600 text-white">{{ __('messages.remove') }}</button>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>

            <div class="rounded-3xl bg-white shadow p-6 space-y-6">
                <div class="space-y-2">
                    <p class="text-sm text-gray-500">{{ __('messages.cart_summary') }}</p>
                    <div class="flex items-center justify-between text-lg font-semibold">
                        <span>{{ __('messages.subtotal') }}</span>
                        <span>$<span x-text="total().toFixed(2)">0.00</span></span>
                    </div>
                </div>
                <button @click="checkout()" class="w-full rounded-3xl bg-indigo-600 px-4 py-3 text-white font-semibold hover:bg-indigo-700">{{ __('messages.checkout') }}</button>
                <button @click="clear()" class="w-full rounded-3xl border border-gray-300 px-4 py-3 text-gray-700">{{ __('messages.clear_cart') }}</button>
            </div>
        </div>

        <script>
            function cartPage(){
                return {
                    itemsData: {!! json_encode($itemsPayload) !!},
                    init(){
                        var routeName = '{{ $catalogo->name }}';
                        var key = 'cart_' + (routeName || 'global');
                        // ensure Alpine exists
                        if (!window.Alpine) return;

                        // If store doesn't exist, create it (mirror logic in cart-badge)
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

                            // if store empty but server provided items, populate from server payload
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
                            // expose helpers in case cart-badge script didn't run on this page
                            window.cartAdd = window.cartAdd || function(id){ try { if (Alpine.store('cart')) Alpine.store('cart').add(id); } catch(e){} };
                            window.cartIncrease = window.cartIncrease || function(id){ try { if (Alpine.store('cart')) Alpine.store('cart').increase(id); } catch(e){} };
                            window.cartDecrease = window.cartDecrease || function(id){ try { if (Alpine.store('cart')) Alpine.store('cart').decrease(id); } catch(e){} };
                            window.cartReset = window.cartReset || function(){ try { if (Alpine.store('cart')) { Alpine.store('cart').items = {}; if (typeof Alpine.store('cart').save === 'function') Alpine.store('cart').save(); } window.dispatchEvent(new CustomEvent('cart-reset')); window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: 0 } })); } catch(e){} };
                            window.cartSyncNow = window.cartSyncNow || function(){ try { var token = (document.querySelector('meta[name="csrf-token"]')||{}).getAttribute('content')||''; return fetch('/' + routeName + '/cart-sync', { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ items: Alpine.store('cart').items }) }).then(function(r){ return r.json(); }).then(function(data){ window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: data.count } })); return data; }).catch(function(){ return Promise.resolve(); }); } catch(e){ return Promise.resolve(); } };
                        }

                        // listen to global events to re-render if needed
                        window.addEventListener('cart-updated', () => { /* reactive via store */ });
                        window.addEventListener('cart-reset', () => { try { if (Alpine.store('cart')) { Alpine.store('cart').items = {}; Alpine.store('cart').save(); } } catch(e){} });
                    },
                    increase(pid){ window.cartIncrease(pid); },
                    decrease(pid){ window.cartDecrease(pid); },
                    remove(pid){ try { if (Alpine.store('cart')) { delete Alpine.store('cart').items[pid]; Alpine.store('cart').save(); Alpine.store('cart')._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated',{ detail: { count: Alpine.store('cart').count() } })); } } catch(e){} },
                    clear(){ if (window.cartReset) window.cartReset(); },
                    total(){ var t = 0; if (!(Alpine.store && Alpine.store('cart'))) return 0; var items = Alpine.store('cart').items; for (var pid in items){ var q = Number(items[pid]||0); var price = this.itemsData[pid] ? Number(this.itemsData[pid].price) : 0; t += q * price; } return t; },
                    checkout(){
                        // ensure server has latest
                        if (window.cartSyncNow) {
                            window.cartSyncNow().then(function(){
                                // after sync, call Livewire checkout via fetch to keep existing behavior
                                // redirect to the Livewire checkout action by submitting a form
                                var form = document.createElement('form');
                                form.method = 'POST';
                                form.action = window.location.pathname + '?_action=checkout';
                                var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                var input = document.createElement('input'); input.type='hidden'; input.name='_token'; input.value=token; form.appendChild(input);
                                document.body.appendChild(form);
                                form.submit();
                            });
                        } else {
                            // fallback: submit immediately
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = window.location.pathname + '?_action=checkout';
                            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            var input = document.createElement('input'); input.type='hidden'; input.name='_token'; input.value=token; form.appendChild(input);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                }
            }
        </script>
        
    </div>
</div>
