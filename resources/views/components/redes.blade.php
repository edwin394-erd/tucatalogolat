    {{-- Floating panel: always reserve bottom-right spot. Show cart above socials; if no socials, cart occupies the spot. --}}
    <div class="fixed bottom-4 right-4 z-50 flex flex-col items-center gap-2">
        <div class="mb-1">
            @livewire('cart-badge')
        </div>

        @if ($catalogo->facebook || $catalogo->instagram || $catalogo->twitter || $catalogo->tiktok)
            <div class="px-3 py-2 rounded-2xl shadow-lg flex items-center gap-2 text-xs font-bold" style="background-color: var(--bg-card-aside); color: black; border-color: var(--primary-btn);">
                @if ($catalogo->facebook)
                    <a href="{{ $catalogo->facebook }}" target="_blank" title="Facebook" class="hover:opacity-80">
                        <img src="{{ asset('imgs/facebook.png') }}" alt="Facebook" width="32" height="32">
                    </a>
                @endif
                @if ($catalogo->instagram)
                    <a href="{{ $catalogo->instagram }}" target="_blank" title="Instagram" class="hover:opacity-80">
                        <img src="{{ asset('imgs/instagram.png') }}" alt="Instagram" width="32" height="32">
                    </a>
                @endif
                @if ($catalogo->twitter)
                    <a href="{{ $catalogo->twitter }}" target="_blank" title="Twitter" class="hover:opacity-80">
                        <img src="{{ asset('imgs/x.png') }}" alt="Twitter" width="32" height="32">
                    </a>
                @endif
                @if ($catalogo->tiktok)
                    <a href="{{ $catalogo->tiktok }}" target="_blank" title="TikTok" class="hover:opacity-80">
                        <img src="{{ asset('imgs/tiktok.png') }}" alt="TikTok" width="32" height="32">
                    </a>
                @endif
            </div>
        @endif
    </div>