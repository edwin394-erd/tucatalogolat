   <div class="rounded-2xl md:rounded-3xl shadow-sm hover:shadow-xl overflow-hidden flex flex-col group border border-black/5" style="background-color: var(--bg-card-aside);">
            
            {{-- Imagen: Altura adaptable (más pequeña en móvil) --}}
            <div class="relative h-40 s:h-48 md:h-56 overflow-hidden">
                <img src="{{ asset('storage/' . $item->fotos[0]->url) }}" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     loading="lazy">
            </div>

            {{-- Contenido: Padding reducido en móvil --}}
            <div class="p-3 md:p-6 flex flex-col flex-1">
                <h5 class="text-sm md:text-lg font-bold mb-1 md:mb-2 line-clamp-1" style="color: var(--text-secondary);">
                    {{ $item->name }}
                </h5>
                
                {{-- Descripción: Oculta o muy breve en móvil para ahorrar espacio --}}
                <p class="hidden md:block text-sm mb-6 flex-1 line-clamp-3 leading-relaxed" style="color: var(--text-secondary);">
                    {{ $item->description }}
                </p>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-auto gap-2">
                    @if($item->precio_descuento)
                    <div class="flex flex-col">
                        <span class="text-sm md:text-md font-black opacity-50 line-through" style="color: var(--text-secondary);">
                           ${{ number_format($item->price, 2) }} 
                        </span>
                        

                        <span class="text-lg md:text-2xl font-black" style="color: var(--text-secondary);">
                           ${{ number_format($item->precio_descuento, 2) }} 
                         </span>
                        
                    </div>
                    @else
                        <span class="text-lg md:text-2xl font-black" style="color: var(--text-secondary);">
                        ${{ number_format($item->price, 2) }} 
                    </span>
                    @endif
                   
                    
                    {{-- Botones: Más compactos en móvil --}}
                    <div class="flex gap-1 md:gap-2 w-full sm:w-auto">
                        <a href="https://wa.me/{{$catalogo->telefono_contacto}}?text={{ urlencode('Me interesa: ' . $item->name) }}" 
                           target="_blank" 
                           class="flex-1 sm:flex-none p-2 md:p-3 rounded-xl bg-green-500 text-white flex justify-center items-center shadow-md active:scale-90">
                            <svg fill="currentColor" class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 32 32">
                                <path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507zM16.062 28.228h-0.005c-2.319 0-4.489-0.64-6.342-1.753l-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353z"></path>
                            </svg>
                        </a>
                      
                        <button type="button" wire:click="addToCart({{ $item->id }})" class="flex-1 sm:flex-none p-2 md:p-3 rounded-xl text-white shadow-md active:scale-90 flex justify-center items-center" 
                                style="background-color: var(--primary-btn);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="{{ $iconColor }}" class="w-4 h-4 md:w-5 md:h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                </div>
</div>
</div>

      
