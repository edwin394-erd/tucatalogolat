<div>
@php
    $routeName = $catalogo?->name_handle ?? request()->route('name');
@endphp

<div x-data="{ count: {{ (int) $count }} }" x-init="window.addEventListener('cart-added', () => { count++ }); window.addEventListener('cart-updated', event => { if (event.detail && typeof event.detail.count === 'number') { count = event.detail.count } }); window.addEventListener('cart-reset', () => { count = 0 }); if (window.Alpine && Alpine.store && Alpine.store('cart')) { count = Alpine.store('cart').count() }">
    <a href="{{ $routeName ? route('catalogo.cart', $routeName) : route('home') }}" aria-label="Carrito" class="relative inline-flex items-center gap-2 p-3 rounded-full shadow-lg hover:scale-105 transition-transform"style="background-color: var(--primary-btn); color: {{ $iconColor }};" wire:navigate x-on:click.prevent="const href = $event.currentTarget.href; (window.cartSyncNow ? window.cartSyncNow() : Promise.resolve()).then(() => Alpine.navigate(href))">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h13"></path></svg>
        Ver Carrito
        <span id="global-cart-count" x-text="count" x-show="count > 0" class="ml-2 inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold" style="background-color: var(--bg-card-aside); color: var(--text-secondary);"></span>
    </a>
</div>

<script>
    (function(){
        var routeName = '{{ $routeName }}';
        var el = document.getElementById('global-cart-count');

        // Fallback helpers (will be replaced when Alpine initializes)
        window.cartAdd = window.cartAdd || function(id, variantId){ try { var key = 'cart_' + (routeName || 'global'); var lineKey = String(id) + ':' + (variantId || '0'); var items = JSON.parse(localStorage.getItem(key) || '{}'); var products = JSON.parse(localStorage.getItem(key + '_products') || '{}'); var variants = JSON.parse(localStorage.getItem(key + '_variants') || '{}'); items[lineKey] = (items[lineKey]||0) + 1; products[lineKey] = Number(id); if (variantId) variants[lineKey] = Number(variantId); localStorage.setItem(key, JSON.stringify(items)); localStorage.setItem(key + '_products', JSON.stringify(products)); localStorage.setItem(key + '_variants', JSON.stringify(variants)); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: Object.values(items).reduce(function(a,b){ return a + (Number(b)||0); },0) } })); } catch(e){} };
        window.cartIncrease = window.cartIncrease || function(id){ window.cartAdd(id); };
        window.cartDecrease = window.cartDecrease || function(id){ try { var key = 'cart_' + (routeName || 'global'); var items = JSON.parse(localStorage.getItem(key) || '{}'); if (!items[String(id)]) return; items[String(id)] = (items[String(id)]||0) - 1; if (items[String(id)] <= 0) delete items[String(id)]; localStorage.setItem(key, JSON.stringify(items)); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: Object.values(items).reduce(function(a,b){ return a + (Number(b)||0); },0) } })); } catch(e){} };
        window.cartReset = window.cartReset || function(){ try { var key = 'cart_v2_' + (routeName || 'global'); localStorage.removeItem(key); localStorage.removeItem(key + '_products'); localStorage.removeItem(key + '_variants'); localStorage.removeItem(key + '_selections'); window.dispatchEvent(new CustomEvent('cart-reset')); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: 0 } })); } catch(e){} };

        // Alpine store for client-side cart (optimistic UI)
        document.addEventListener('alpine:init', function(){
            var key = 'cart_v2_' + (routeName || 'global');
            if (!Alpine.store('cart')) {
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
                        add: function(id, variantId, selectionIds){ id = String(id); selectionIds = (selectionIds || (variantId ? [variantId] : [])).map(Number).sort(function(a,b){ return a-b; }); var lineKey = id + ':' + (selectionIds.join('-') || '0'); this.items[lineKey] = (this.items[lineKey]||0) + 1; this.products[lineKey] = Number(id); if (variantId) this.variants[lineKey] = Number(variantId); this.selections[lineKey] = selectionIds; this.save(); this._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count() } })); window.dispatchEvent(new CustomEvent('cart-added', { detail: { message: 'Producto agregado al carrito.' } })); },
                        increase: function(id){ var lineKey = this.items[id] ? id : this.findKey(id); if (lineKey) { this.items[lineKey] += 1; this.save(); this._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count() } })); } else this.add(id); },
                        decrease: function(id){ var lineKey = this.items[id] ? id : this.findKey(id); if (!lineKey) return; this.items[lineKey] -= 1; if (this.items[lineKey] <= 0) { delete this.items[lineKey]; delete this.products[lineKey]; delete this.variants[lineKey]; delete this.selections[lineKey]; } this.save(); this._scheduleSync(); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.count() } })); }
                });
            }

            // expose simple helpers for templates
            window.cartAdd = function(id, variantId, selectionIds){ if (Alpine.store('cart')) Alpine.store('cart').add(id, variantId, selectionIds); };
            window.cartIncrease = function(id){ if (Alpine.store('cart')) Alpine.store('cart').increase(id); };
            window.cartDecrease = function(id){ if (Alpine.store('cart')) Alpine.store('cart').decrease(id); };

            // force immediate sync and return a promise
            window.cartSyncNow = function(){
                try {
                    var token = (document.querySelector('meta[name="csrf-token"]')||{}).getAttribute('content')||'';
                    return fetch('/' + routeName + '/cart-sync', { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ items: Alpine.store('cart').items, products: Alpine.store('cart').products, variants: Alpine.store('cart').variants, selections: Alpine.store('cart').selections }) }).then(function(r){ return r.json(); }).then(function(data){ if (data.items && Alpine.store('cart').hydrate) Alpine.store('cart').hydrate(data.items); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } })); return data; }).catch(function(e){ return Promise.resolve(); });
                } catch(e) { return Promise.resolve(); }
            };

            // initialize badge from store
            if (el) {
                var c = Alpine.store('cart').count();
                el.textContent = c;
                el.style.display = c > 0 ? 'inline-flex' : 'none';
            }
        });

        // fallback server-driven count update (existing behavior)
        function update(){
            if (!el || !routeName) return;
            fetch('/' + routeName + '/cart-count')
                .then(function(r){ return r.json(); })
                .then(function(data){
                    var c = data.count || 0;
                    el.textContent = c;
                    el.style.display = c > 0 ? 'inline-flex' : 'none';

                    // If server reports empty cart, make sure client store/localStorage is cleared too
                    if (c === 0) {
                        try {
                            var key = 'cart_v2_' + (routeName || 'global');
                            localStorage.removeItem(key);
                            localStorage.removeItem(key + '_products');
                            localStorage.removeItem(key + '_variants');
                            localStorage.removeItem(key + '_selections');
                            if (window.Alpine && Alpine.store && Alpine.store('cart')) {
                                Alpine.store('cart').items = {};
                                Alpine.store('cart').products = {};
                                Alpine.store('cart').variants = {};
                                Alpine.store('cart').selections = {};
                                if (typeof Alpine.store('cart').save === 'function') Alpine.store('cart').save();
                            }
                            window.dispatchEvent(new CustomEvent('cart-reset'));
                            window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: 0 } }));
                        } catch(e) { /* ignore */ }
                    }
                }).catch(function(){ /* ignore */ });
        }

        function attachLivewireHook(){
            if (!window.Livewire || typeof window.Livewire.hook !== 'function') {
                return false;
            }
            window.Livewire.hook('commit', function(payload){
                payload.succeed(function(){
                    update();
                });
            });
            return true;
        }

        function waitForLivewire(){
            if (attachLivewireHook()) {
                return;
            }
            document.addEventListener('livewire:initialized', function(){
                attachLivewireHook();
            }, { once: true });
            var interval = setInterval(function(){
                if (attachLivewireHook()) {
                    clearInterval(interval);
                }
            }, 100);
            setTimeout(function(){ clearInterval(interval); }, 5000);
        }
        update();
        window.addEventListener('pageshow', update);
        waitForLivewire();
    })();
</script>
</div>
