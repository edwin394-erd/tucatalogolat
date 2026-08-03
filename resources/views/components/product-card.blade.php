   <div class="rounded-2xl md:rounded-3xl shadow-sm hover:shadow-xl overflow-hidden flex flex-col group border border-black/5" style="background-color: var(--bg-card-aside);">
            
            {{-- Imagen: Altura adaptable (más pequeña en móvil) --}}
            <div class="relative h-40 sm:h-48 md:h-56 overflow-hidden">
                <img src="{{ asset('storage/' . $item->fotos[0]->url) }}" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     loading="lazy">
            </div>

            {{-- Contenido: Padding reducido en móvil --}}
            <div class="p-3 md:p-6 flex flex-col flex-1">
                <h5 class="text-sm md:text-lg font-bold mb-1 md:mb-2 line-clamp-1" style="color: var(--text-primary);">
                    {{ $item->name }}
                </h5>
                
                {{-- Descripción: siempre visible para móviles y escritorio --}}
                <p class="text-sm mb-6 flex-1 line-clamp-3 leading-relaxed" style="color: var(--text-primary);">
                    {{ $item->description }}
                </p>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-auto gap-2">
                    @if($item->precio_descuento)
                    <div class="flex flex-col">
                        <span class="text-sm md:text-md font-black opacity-50 line-through" style="color: var(--text-primary);">
                           ${{ number_format($item->price, 2) }} 
                        </span>
                        

                        <span class="text-lg md:text-2xl font-black" style="color: var(--text-primary);">
                           ${{ number_format($item->precio_descuento, 2) }} 
                         </span>
                        
                    </div>
                    @else
                        <span class="text-lg md:text-2xl font-black" style="color: var(--text-primary);">
                        ${{ number_format($item->price, 2) }} 
                    </span>
                    @endif
                   
                    
                    {{-- Botones: Más compactos en móvil --}}
                    <div class="flex gap-1 md:gap-2 w-full sm:w-auto">
                        <a href="https://wa.me/{{$catalogo->telefono_contacto}}?text={{ urlencode('Me interesa: ' . $item->name) }}" 
                           target="_blank" 
                           class="flex-1 sm:flex-none p-2 md:p-3 rounded-xl bg-green-500 text-white flex justify-center items-center shadow-md active:scale-90">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6.014 8.00613C6.12827 7.1024 7.30277 5.87414 8.23488 6.01043L8.23339 6.00894C9.14051 6.18132 9.85859 7.74261 10.2635 8.44465C10.5504 8.95402 10.3641 9.4701 10.0965 9.68787C9.7355 9.97883 9.17099 10.3803 9.28943 10.7834C9.5 11.5 12 14 13.2296 14.7107C13.695 14.9797 14.0325 14.2702 14.3207 13.9067C14.5301 13.6271 15.0466 13.46 15.5548 13.736C16.3138 14.178 17.0288 14.6917 17.69 15.27C18.0202 15.546 18.0977 15.9539 17.8689 16.385C17.4659 17.1443 16.3003 18.1456 15.4542 17.9421C13.9764 17.5868 8 15.27 6.08033 8.55801C5.97237 8.24048 5.99955 8.12044 6.014 8.00613Z" fill="#ffffff"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C10.7764 23 10.0994 22.8687 9 22.5L6.89443 23.5528C5.56462 24.2177 4 23.2507 4 21.7639V19.5C1.84655 17.492 1 15.1767 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM6 18.6303L5.36395 18.0372C3.69087 16.4772 3 14.7331 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C11.0143 21 10.552 20.911 9.63595 20.6038L8.84847 20.3397L6 21.7639V18.6303Z" fill="#ffffff"></path> </g></svg>
                        </a>
                      
                        <button type="button" x-data="{ added: false }" @click="added = true; setTimeout(() => added = false, 500); window.dispatchEvent(new CustomEvent('cart-added'))" wire:click="addToCart({{ $item->id }})" :class="added ? 'scale-125 shadow-2xl ring-4 ring-white/80 animate-pulse' : ''" class="flex-1 sm:flex-none p-2 md:p-3 rounded-xl text-white shadow-md transition-all duration-200 ease-out active:scale-95 flex justify-center items-center" 
                                style="background-color: var(--primary-btn);">
                           <svg height="24px" width="24px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 294.873 294.873" xml:space="preserve" fill="#000000" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path style="fill:#ffffff;" d="M287.373,37.98h-46.046c-8.789,0-17.546,6.626-19.936,15.085l-12.438,44.023 c-1.423-0.396-2.92-0.625-4.478-0.625H99.761c-5.056-22.543-25.217-39.442-49.263-39.442C22.653,57.021,0,79.675,0,107.518 c0,25.479,18.974,46.601,43.532,50.006c-0.011,0.329-0.009,0.661,0.024,0.998l2.61,26.457c0.925,9.373,9.027,16.715,18.446,16.715 h115.462c8.827,0,17.546-6.675,19.85-15.195l14.439-53.397c0.001-0.001,0.001-0.003,0.001-0.003l21.46-75.955 c0.583-2.061,3.359-4.163,5.502-4.163h46.046c4.142,0,7.5-3.357,7.5-7.5S291.515,37.98,287.373,37.98z M15,107.518 c0-19.573,15.924-35.497,35.498-35.497s35.497,15.924,35.497,35.497c0,19.573-15.924,35.497-35.497,35.497S15,127.092,15,107.518z M185.445,182.583c-0.551,2.036-3.262,4.111-5.371,4.111H64.612c-1.646,0-3.356-1.549-3.518-3.188l-2.578-26.135 c22.774-3.65,40.497-22.58,42.31-45.908h103.648c0.072,0,0.137,0.003,0.193,0.007c-0.011,0.056-0.025,0.119-0.044,0.188 L185.445,182.583z"></path> <path style="fill:#ffffff;" d="M86.504,210.236c-12.863,0-23.328,10.465-23.328,23.328c0,12.863,10.465,23.328,23.328,23.328 c12.863,0,23.329-10.465,23.329-23.328C109.833,220.701,99.367,210.236,86.504,210.236z M86.504,241.892 c-4.592,0-8.328-3.736-8.328-8.328c0-4.592,3.736-8.328,8.328-8.328c4.592,0,8.329,3.736,8.329,8.328 C94.833,238.156,91.096,241.892,86.504,241.892z"></path> <path style="fill:#ffffff;" d="M160.472,210.236c-12.863,0-23.328,10.465-23.328,23.328c0,12.863,10.465,23.328,23.328,23.328 c12.863,0,23.328-10.465,23.328-23.328C183.8,220.701,173.335,210.236,160.472,210.236z M160.472,241.892 c-4.592,0-8.328-3.736-8.328-8.328c0-4.592,3.736-8.328,8.328-8.328c4.592,0,8.328,3.736,8.328,8.328 C168.8,238.156,165.064,241.892,160.472,241.892z"></path> <path style="fill:#ffffff;" d="M57.996,126.094v-11.075h11.078c4.142,0,7.5-3.357,7.5-7.5s-3.358-7.5-7.5-7.5H57.996V88.94 c0-4.143-3.358-7.5-7.5-7.5s-7.5,3.357-7.5,7.5v11.078H31.921c-4.142,0-7.5,3.357-7.5,7.5s3.358,7.5,7.5,7.5h11.075v11.075 c0,4.143,3.358,7.5,7.5,7.5S57.996,130.236,57.996,126.094z"></path> </g> </g></svg>
                        </button>
                    </div>
                </div>
</div>
</div>

      
