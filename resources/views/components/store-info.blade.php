 <div x-data="{ showHorario: false, showUbicacion: false, showDescripcion: false }" class="flex gap-2 mb-6 justify-center">
            {{-- Botón Horario --}}
            <button title="Horario" @click="showHorario = true" class="p-3 rounded-2xl text-white shadow-lg active:scale-90 hover:opacity-90" style="background-color: var(--primary-btn);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="{{ $iconColor }}" class="w-5 h-5">
                <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/>
            </svg>
            </button>
            
           

            {{-- Botón Ubicación --}}
            <button title="Ubicación" @click="showUbicacion = true" class="p-3 rounded-2xl text-white shadow-lg active:scale-90 hover:opacity-90" style="background-color: var(--primary-btn);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="{{ $iconColor }}" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6-5.686-6-10A6 6 0 1 1 18 11c0 4.314-6 10-6 10z"/>
                <circle cx="12" cy="11" r="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </button>
           
            {{-- Modal Mejorado Horario --}}
            <div x-show="showHorario" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div @click.away="showHorario = false" 
                     class="bg-white rounded-2xl shadow-2xl p-0 max-w-xs w-full overflow-hidden"
                     style="background-color: var(--bg-card-aside); color: var(--text-secondary);">
                    <div class="flex items-center gap-2 px-6 pt-6 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="var(--text-secondary)" class="w-7 h-7">
                            <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/>
                        </svg>
                        <h3 class="font-bold text-lg flex-1" style="color: var(--text-secondary);">{{__('messages.schedule')}}</h3>
                        <button @click="showHorario = false" class="text-2xl font-bold hover:opacity-70" style="color: var(--text-secondary);">&times;</button>
                    
                    </div>
                    <div class="px-6 pb-6 pt-2 text-base" style="color: var(--text-secondary);">
                        {{ $catalogo->horario ?? 'No especificado.' }}
                    </div>

                </div>
            </div>

            {{-- Modal Mejorado Ubicación --}}
            <div x-show="showUbicacion" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div @click.away="showUbicacion = false" 
                     class="bg-white rounded-2xl shadow-2xl p-0 max-w-xs w-full overflow-hidden"
                     style="background-color: var(--bg-card-aside); color: var(--text-secondary);">
                    <div class="flex items-center gap-2 px-6 pt-6 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="var(--text-secondary)" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6-5.686-6-10A6 6 0 1 1 18 11c0 4.314-6 10-6 10z"/>
                            <circle cx="12" cy="11" r="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h3 class="font-bold text-lg flex-1" style="color: var(--text-secondary);">{{__('messages.location')}}</h3>
                        <button @click="showUbicacion = false" class="text-2xl font-bold hover:opacity-70" style="color: var(--text-secondary);">&times;</button>
                    </div>
                    <div class="px-6 pb-6 pt-2 text-base" style="color: var(--text-secondary);">
                        {{ $catalogo->ubicacion ?? 'No especificada.' }}

                        @if($catalogo->ubicacion_mapa)
                            {{-- <br><br>
                            <iframe 
                                src="{{ $catalogo->ubicacion_mapa }}" 
                                width="100%" 
                                height="200" 
                                style="border:0; border-radius: 12px;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                            <br> --}}
                            <a href="{{ $catalogo->ubicacion_mapa }}" target="_blank" class="text-indigo-600 hover:underline">{{__('messages.view_on_google_maps')}}</a>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Botón Descripción Mejorado --}}
            <button title="Descripción" @click="showDescripcion = true" class="p-3 rounded-2xl text-white shadow-lg active:scale-90 hover:opacity-90" style="background-color: var(--primary-btn);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="{{ $iconColor }}" class="w-5 h-5">
                    <rect x="4" y="5" width="16" height="14" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5"/>
                </svg>
            </button>
            {{-- Modal Descripción Mejorado --}}
            <div x-show="showDescripcion" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div @click.away="showDescripcion = false"
                     class="bg-white rounded-2xl shadow-2xl p-0 max-w-xs w-full overflow-hidden"
                     style="background-color: var(--bg-card-aside); color: var(--text-secondary);">
                    <div class="flex items-center gap-2 px-6 pt-6 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="var(--text-secondary)" class="w-7 h-7">
                            <rect x="4" y="5" width="16" height="14" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h5"/>
                        </svg>
                        <h3 class="font-bold text-lg flex-1" style="color: var(--text-secondary);">{{__('messages.description')}}</h3>
                        <button @click="showDescripcion = false" class="text-2xl font-bold hover:opacity-70" style="color: var(--text-secondary);">&times;</button>
                    </div>
                    <div class="px-6 pb-6 pt-2 text-base max-h-60 overflow-y-auto" style="color: var(--text-secondary);">
                        {{ $catalogo->description ?? 'Sin descripción.' }}
                    </div>
                </div>
            </div>
        </div>