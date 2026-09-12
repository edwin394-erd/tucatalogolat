<div>
@php
    $routeName = request()->route('name');
@endphp

<div x-data="{ count: {{ (int) $count }} }" x-init="window.addEventListener('cart-added', () => { count++ }); window.addEventListener('cart-updated', event => { if (event.detail && typeof event.detail.count === 'number') { count = event.detail.count } }); window.addEventListener('cart-reset', () => { count = 0 }); if (window.Alpine && Alpine.store && Alpine.store('cart')) { count = Alpine.store('cart').count() }">
    <a href="{{ $routeName ? route('catalogo.cart', $routeName) : route('home') }}" aria-label="Carrito" class="relative inline-flex items-center gap-2 p-3 rounded-full shadow-lg hover:scale-105 transition-transform" style="background-color: var(--primary-btn); color: var(--text-on-primary, #fff);" wire:navigate x-on:click.prevent="(window.cartSyncNow ? window.cartSyncNow() : Promise.resolve()).then(()=> Alpine.navigate($event.currentTarget.href))">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h13"></path></svg>
        Ver Carrito
        <span id="global-cart-count" x-text="count" x-show="count > 0" class="ml-2 inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold" style="background-color: var(--bg-card-aside); color: var(--text-primary);"></span>
    </a>
</div>

<script>
    (function(){
        var routeName = '{{ $routeName }}';
        var el = document.getElementById('global-cart-count');

        // Fallback helpers (will be replaced when Alpine initializes)
        window.cartAdd = window.cartAdd || function(id){ try { var key = 'cart_' + (routeName || 'global'); var items = JSON.parse(localStorage.getItem(key) || '{}'); items[String(id)] = (items[String(id)]||0) + 1; localStorage.setItem(key, JSON.stringify(items)); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: Object.values(items).reduce(function(a,b){ return a + (Number(b)||0); },0) } })); } catch(e){} };
        window.cartIncrease = window.cartIncrease || function(id){ window.cartAdd(id); };
        window.cartDecrease = window.cartDecrease || function(id){ try { var key = 'cart_' + (routeName || 'global'); var items = JSON.parse(localStorage.getItem(key) || '{}'); if (!items[String(id)]) return; items[String(id)] = (items[String(id)]||0) - 1; if (items[String(id)] <= 0) delete items[String(id)]; localStorage.setItem(key, JSON.stringify(items)); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: Object.values(items).reduce(function(a,b){ return a + (Number(b)||0); },0) } })); } catch(e){} };
        window.cartReset = window.cartReset || function(){ try { var key = 'cart_' + (routeName || 'global'); localStorage.removeItem(key); window.dispatchEvent(new CustomEvent('cart-reset')); window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: 0 } })); } catch(e){} };

        // Alpine store for client-side cart (optimistic UI)
        document.addEventListener('alpine:init', function(){
            var key = 'cart_' + (routeName || 'global');
            if (!Alpine.store('cart')) {
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
            }

            // expose simple helpers for templates
            window.cartAdd = function(id){ if (Alpine.store('cart')) Alpine.store('cart').add(id); };
            window.cartIncrease = function(id){ if (Alpine.store('cart')) Alpine.store('cart').increase(id); };
            window.cartDecrease = function(id){ if (Alpine.store('cart')) Alpine.store('cart').decrease(id); };

            // force immediate sync and return a promise
            window.cartSyncNow = function(){
                try {
                    var token = (document.querySelector('meta[name="csrf-token"]')||{}).getAttribute('content')||'';
                    return fetch('/' + routeName + '/cart-sync', { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ items: Alpine.store('cart').items }) }).then(function(r){ return r.json(); }).then(function(data){ window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } })); return data; }).catch(function(e){ return Promise.resolve(); });
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
                            var key = 'cart_' + (routeName || 'global');
                            localStorage.removeItem(key);
                            if (window.Alpine && Alpine.store && Alpine.store('cart')) {
                                Alpine.store('cart').items = {};
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
        waitForLivewire();
    })();
</script>
</div>
