<div>
@php
    $routeName = request()->route('name');
@endphp

<div x-data="{ count: {{ (int) $count }} }" x-init="window.addEventListener('cart-added', () => { count++ }); window.addEventListener('cart-updated', event => { if (event.detail && typeof event.detail.delta === 'number') { count = Math.max(0, count + event.detail.delta) } }); window.addEventListener('cart-reset', () => { count = 0 })">
    <a href="{{ $routeName ? route('catalogo.cart', $routeName) : route('home') }}" aria-label="Carrito" class="relative inline-flex items-center gap-2 p-3 rounded-full shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:scale-105 transition-transform">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h13"></path></svg>
        <span id="global-cart-count" x-text="count" x-show="count > 0" class="absolute -top-2 -right-2 inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded-full bg-indigo-600 text-white text-xs"></span>
    </a>
</div>

<script>
    (function(){
        var routeName = '{{ $routeName }}';
        var el = document.getElementById('global-cart-count');
        function update(){
            if (!el || !routeName) return;
            fetch('/' + routeName + '/cart-count')
                .then(function(r){ return r.json(); })
                .then(function(data){
                    var c = data.count || 0;
                    el.textContent = c;
                    el.style.display = c > 0 ? 'inline-flex' : 'none';
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
