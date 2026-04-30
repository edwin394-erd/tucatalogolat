          {{-- Redes Sociales Flotantes (Solo si hay alguna red social configurada) --}}
@if ($catalogo->facebook || $catalogo->instagram || $catalogo->twitter || $catalogo->tiktok)
        <div  
           class="px-4 py-2 rounded rounded-2xl fixed bottom-4 right-4 shadow-lg flex items-center gap-2 z-50 border-b-2vf  text-xs font-bold" 
           style="background-color: var(--bg-card-aside); color: black; border-color: var(--primary-btn);">
          
            @if ($catalogo->facebook)
                <a href="{{ $catalogo->facebook }}" target="_blank" title="Facebook" class="hover:opacity-80">
                    {{-- <svg class="w-5 h-5" fill="currentColor" style="color:#1877F3;" viewBox="0 0 24 24">
                        <path d="M22.675 0h-21.35C.595 0 0 .592 0 1.326v21.348C0 23.408.595 24 1.325 24h11.495v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.797.143v3.24l-1.918.001c-1.504 0-1.797.715-1.797 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.408 24 22.674V1.326C24 .592 23.406 0 22.675 0"/>
                    </svg> --}}
                    <img src="{{ asset('imgs/facebook.png') }}" alt="TikTok" width="32px" height="32px">

                </a>
            @endif
            @if ($catalogo->instagram)
                <a href="{{ $catalogo->instagram }}" target="_blank" title="Instagram" class="hover:opacity-80">
                    <img src="{{ asset('imgs/instagram.png') }}" alt="Instagram" width="32px" height="32px">
                </a>
            @endif
            @if ($catalogo->twitter)
                <a href="{{ $catalogo->twitter }}" target="_blank" title="Twitter" class="hover:opacity-80">
                    <img src="{{ asset('imgs/x.png') }}" alt="Twitter" width="32px" height="32px">
                </a>
            @endif
            @if ($catalogo->tiktok)
                <a href="{{ $catalogo->tiktok }}" target="_blank" title="TikTok" class="hover:opacity-80">
                   <img src="{{ asset('imgs/tiktok.png') }}" alt="TikTok" width="32px" height="32px">
                </a>
            @endif
        </div>
    @endif