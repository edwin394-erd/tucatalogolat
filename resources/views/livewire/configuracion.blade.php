<div>

@php
    if (!function_exists('contrastRatio')) {
        function contrastRatio($hex1, $hex2) {
            $lum = function($hex) {
                $hex = str_replace('#', '', $hex);
                if (strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
                $r = hexdec(substr($hex,0,2)) / 255;
                $g = hexdec(substr($hex,2,2)) / 255;
                $b = hexdec(substr($hex,4,2)) / 255;
                $chan = function($c) {
                    return $c <= 0.03928 ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);
                };
                return 0.2126 * $chan($r) + 0.7152 * $chan($g) + 0.0722 * $chan($b);
            };
            $l1 = $lum($hex1); $l2 = $lum($hex2);
            $lighter = max($l1, $l2); $darker = min($l1, $l2);
            return round(($lighter + 0.05) / ($darker + 0.05), 2);
        }
    }

    if (!function_exists('contrastBadge')) {
        function contrastBadge($ratio) {
            if ($ratio >= 4.5) return ['label' => '✓ Buen contraste', 'class' => 'bg-green-100 text-green-700'];
            if ($ratio >= 3)   return ['label' => '⚠ Contraste bajo', 'class' => 'bg-yellow-100 text-yellow-700'];
            return ['label' => '✗ Texto ilegible', 'class' => 'bg-red-100 text-red-700'];
        }
    }

    if (!function_exists('readableTextColor')) {
        function readableTextColor($hex) {
            $h = str_replace('#', '', $hex);
            if (strlen($h) == 3) $h = $h[0].$h[0].$h[1].$h[1].$h[2].$h[2];
            $r = hexdec(substr($h,0,2)); $g = hexdec(substr($h,2,2)); $b = hexdec(substr($h,4,2));
            return (($r * 299 + $g * 587 + $b * 114) / 1000) < 128 ? '#ffffff' : '#000000';
        }
    }
@endphp

    <div class="mx-auto bg-white rounded-lg shadow-md p-6">
    <x-alert alert_type="success" />
    <h1 class="text-2xl font-bold text-gray-700  mb-5">{{ __('messages.configure_catalog') }}</h1>
    <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ __('messages.catalog_information') }}</h2>

    <!-- Banner estilo Twitter -->
    <div class="relative mb-8">
        <!-- Botón para cambiar banner -->
        <label class="absolute top-2 left-2 z-10 bg-white bg-opacity-80 px-2 py-1 rounded cursor-pointer text-xs font-semibold shadow hover:bg-gray-100 transition">
             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
            <input type="file" wire:model="banner" id="banner" accept="image/*" class="hidden">
        </label>
        @if($catalogo->banner_url)
            <img src="{{ asset('storage/' . $catalogo->banner_url) }}" alt="Banner" class="w-full h-40 object-cover rounded-t-lg inset-shadow-sm">
        @else
            <div class="w-full h-40 bg-gray-200 flex items-center justify-center rounded-t-lg text-gray-500">{{ __('messages.no_banner') }}</div>
        @endif

        <!-- Logo superpuesto estilo Twitter -->
        <div class="absolute left-6 -bottom-10">
            <div class="relative">
                <!-- Botón para cambiar logo -->
                <label class="absolute -top-1 right-1 z-10 bg-white bg-opacity-80 px-2 py-1 rounded cursor-pointer text-xs font-semibold shadow hover:bg-gray-100 transition">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>


                    <input type="file" wire:model="logo" id="logo" accept="image/*" class="hidden">
                </label>
                @if($catalogo->logo_url)
                    <img src="{{ asset('storage/' . $catalogo->logo_url) }}" alt="Logo" class="w-24 h-24 rounded-full object-cover border-4 border-white bg-white shadow">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 border-4 border-white shadow">{{ __('messages.no_logo') }}</div>
                @endif
            </div>
        </div>
    </div>

 
    <br>

    <div class="">
        <form action="" wire:submit.prevent="saveChanges" enctype="multipart/form-data">
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.catalog_name') }}</label>
                <input type="text" wire:model="name" name="name" id="name" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.catalog_name_placeholder') }}">
                <x-input-error for="name" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.catalog_description') }}</label>
                <textarea wire:model="description" id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-100 inset-shadow-sm rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="{{ __('messages.catalog_description_placeholder') }}"></textarea>
                <x-input-error for="description" class="mt-2" />
            </div>
             <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.contact_phone') }}</label>
                <input type="text" wire:model="telefono_contacto" name="telefono" id="telefono" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.contact_phone_placeholder') }}">
                <x-input-error for="telefono" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="horario" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.schedule') }}</label>
                <input type="text" wire:model="horario" name="horario" id="horario" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.schedule_placeholder') }}">
                <x-input-error for="horario" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="ubicacion" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.location') }}</label>
                <input type="text" wire:model="ubicacion" name="ubicacion" id="ubicacion" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.location_placeholder') }}">
                <x-input-error for="ubicacion" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="ubicacion_mapa" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.map_location') }}</label>
                <div class="flex">
                    <input type="text" wire:model="ubicacion_mapa" name="ubicacion_mapa" id="ubicacion_mapa" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.map_location_placeholder') }}">
                    <button type="button" class="ml-2 px-3 py-2 bg-indigo-500 text-white rounded-lg" @click="window.open('https://www.google.com/maps', '_blank')">{{ __('messages.pick_map') }}</button>
                </div>
                <x-input-error for="ubicacion_mapa" class="mt-2" />
            </div>

            <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ __('messages.social_media') }}</h2>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="instagram" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Instagram:</label>
                <input type="text" wire:model="instagram" name="instagram" id="instagram" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.instagram_placeholder') }}">
                <x-input-error for="instagram" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="facebook" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Facebook:</label>
                <input type="text" wire:model="facebook" name="facebook" id="facebook" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.facebook_placeholder') }}">
                <x-input-error for="facebook" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="twitter" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Twitter:</label>
                <input type="text" wire:model="twitter" name="twitter" id="twitter" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.twitter_placeholder') }}">
                <x-input-error for="twitter" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="tiktok" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">TikTok:</label>
                <input type="text" wire:model="tiktok" name="tiktok" id="tiktok" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="{{ __('messages.tiktok_placeholder') }}">
                <x-input-error for="tiktok" class="mt-2" />
            </div>

            <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ __('messages.select_catalog_structure') }}</h2>
            <div x-data="{ selected: @entangle('plantilla_id') }" class="flex items-center mb-4">
                @foreach ($plantillas as $template)
                    <div 
                        wire:key="template-{{ $template->id }}"
                        wire:click="selectTemplate({{ $template->id }})"
                        @click="selected = {{ $template->id }}" 
                        :class="selected == {{ $template->id }} ? 'border-indigo-500 shadow-lg bg-indigo-50' : 'border-gray-300'"
                        class=" mr-4 p-2 border rounded-lg cursor-pointer hover:shadow-lg transition flex flex-col mb-4 md:mb-0 md:w-1/4">
                        
                        <div class="flex items-center space-x-2 p-2">
                            <input type="radio" :checked="selected == {{ $template->id }}">
                            <p class="mb-0">{{ $template->name }}</p>
                        </div>
                        
                        <div class="ml-2 rounded-lg">
                            <img src="{{ asset('imgs/'.$template->image_url) }}" class="max-w-full md:h-auto md:object-contain">
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ===================== TEMAS / COLORES ===================== --}}
            {{-- Todo lo de aquí abajo (selección de tema, preview en vivo, y modal personalizado)
                 comparte el mismo scope de Alpine: selectedTheme (id del tema activo) y openCustom (modal abierto/cerrado). --}}
            <div x-data="{ selectedTheme: @entangle('tema_id'), openCustom: false }" class="mb-10">

                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-3">{{ __('messages.select_theme') }}</p>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($themes as $theme)
                            @php
                                $ratioMain = contrastRatio($theme->bg_color, $theme->primary_font_color);
                                $ratioCard = contrastRatio($theme->secondary_color, $theme->secondary_font_color);
                                $worst = min($ratioMain, $ratioCard);
                                $badge = contrastBadge($worst);
                            @endphp
                            <div wire:key="theme-{{ $theme->id }}" wire:click="selectTheme({{ $theme->id }})" @click="selectedTheme = {{ $theme->id }}"
                                 :class="selectedTheme == {{ $theme->id }} ? 'ring-2 ring-indigo-500' : 'ring-1 ring-gray-200'"
                                 class="w-44 rounded-xl cursor-pointer transition overflow-hidden shadow-sm hover:shadow-md bg-white">

                                {{-- Mini preview real: fondo principal con texto --}}
                                <div class="p-3" style="background-color: {{ $theme->bg_color }};">
                                    <p class="text-[11px] font-bold" style="color: {{ $theme->primary_font_color }};">{{ $theme->name }}</p>
                                    <p class="text-[9px] mt-0.5" style="color: {{ $theme->primary_font_color }}; opacity: 0.8;">Texto de ejemplo</p>

                                    {{-- Mini tarjeta secundaria dentro del preview --}}
                                    <div class="mt-2 rounded-lg p-2" style="background-color: {{ $theme->secondary_color }};">
                                        <p class="text-[9px] font-semibold" style="color: {{ $theme->secondary_font_color }};">Tarjeta / producto</p>
                                    </div>

                                    {{-- Botón de acento --}}
                                    <span class="inline-block mt-2 text-[9px] font-bold px-2 py-1 rounded-full"
                                          style="background-color: {{ $theme->primary_color }}; color: {{ readableTextColor($theme->primary_color) }};">
                                        Botón
                                    </span>
                                </div>

                                {{-- Badge de contraste + radio --}}
                                <div class="flex items-center justify-between px-3 py-2 border-t border-gray-100">
                                    <input type="radio" :checked="selectedTheme == {{ $theme->id }}" class="pointer-events-none">
                                    <span class="text-[9px] font-semibold px-2 py-0.5 rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                </div>
                            </div>
                        @endforeach

                        {{-- Tarjeta "Personalizado" con el mismo formato de preview --}}
                        <div wire:click="selectCustomTheme()" @click="selectedTheme = 'custom'"
                             :class="selectedTheme == 'custom' ? 'ring-2 ring-indigo-500' : 'ring-1 ring-gray-200'"
                             class="w-44 rounded-xl cursor-pointer transition overflow-hidden shadow-sm hover:shadow-md bg-white group relative">

                            <button @click.stop="openCustom = true" type="button" class="absolute top-2 right-2 z-10 bg-indigo-600 text-white rounded-full p-1.5 shadow-lg hover:bg-indigo-700 transition opacity-0 group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>

                            @php
                                $cRatioMain = contrastRatio($bg_custom ?? '#ffffff', $primary_font_custom ?? '#000000');
                                $cRatioCard = contrastRatio($secondary_custom ?? '#cccccc', $secondary_font_custom ?? '#000000');
                                $cBadge = contrastBadge(min($cRatioMain, $cRatioCard));
                            @endphp
                            <div class="p-3" style="background-color: {{ $bg_custom ?? '#ffffff' }};">
                                <p class="text-[11px] font-bold" style="color: {{ $primary_font_custom ?? '#000000' }};">{{ __('messages.custom') }}</p>
                                <p class="text-[9px] mt-0.5" style="color: {{ $primary_font_custom ?? '#000000' }}; opacity: 0.8;">Texto de ejemplo</p>
                                <div class="mt-2 rounded-lg p-2" style="background-color: {{ $secondary_custom ?? '#cccccc' }};">
                                    <p class="text-[9px] font-semibold" style="color: {{ $secondary_font_custom ?? '#000000' }};">Tarjeta / producto</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between px-3 py-2 border-t border-gray-100">
                                <input type="radio" :checked="selectedTheme == 'custom'" class="pointer-events-none">
                                <span class="text-[9px] font-semibold px-2 py-0.5 rounded-full {{ $cBadge['class'] }}">{{ $cBadge['label'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== VISTA PREVIA EN VIVO (mockup de navegador/móvil) ===== --}}
                <div x-data="{ device: 'desktop' }">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.live_preview') ?? 'Vista previa en vivo' }}</h3>

                        <div class="inline-flex items-center rounded-full bg-gray-100 p-1 text-xs font-medium">
                            <button type="button" @click="device = 'desktop'"
                                    :class="device === 'desktop' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="w-4 h-4">
                                    <rect x="3" y="4" width="18" height="12" rx="1.5"/><path d="M8 20h8M12 16v4"/>
                                </svg>
                                Escritorio
                            </button>
                            <button type="button" @click="device = 'mobile'"
                                    :class="device === 'mobile' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="w-4 h-4">
                                    <rect x="7" y="2" width="10" height="20" rx="2"/><path d="M11 18h2"/>
                                </svg>
                                Móvil
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-center bg-gray-100 rounded-2xl p-6 sm:p-10 overflow-hidden">
                        <div class="transition-all duration-300 ease-out"
                             :class="device === 'desktop' ? 'w-full max-w-3xl' : 'w-[300px]'">

                            <div x-show="device === 'desktop'" class="rounded-t-xl bg-[#E8E8ED] border border-b-0 border-gray-300 px-3 py-2 flex items-center gap-2">
                                <div class="flex gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#FF5F57]"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#FEBC2E]"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#28C840]"></span>
                                </div>
                                <div class="flex-1 mx-3 bg-white rounded-md px-3 py-1 text-[11px] text-gray-500 truncate border border-gray-200">
                                    🔒 tucatalogolat.domcloud.dev/{{ auth()->user()->catalogo->name_handle ?? 'tu-catalogo' }}
                                </div>
                            </div>

                            <div x-show="device === 'mobile'" class="rounded-t-[1.5rem] bg-gray-900 h-6 flex items-center justify-center">
                                <div class="w-16 h-1.5 rounded-full bg-gray-700"></div>
                            </div>

                            <div class="border border-gray-300 overflow-hidden"
                                 :class="device === 'desktop' ? 'rounded-b-xl' : 'rounded-b-[1.5rem] border-x-4 border-b-4 border-gray-900'"
                                 style="background-color: {{ $selectedTheme->bg_color ?? '#F2F2F2' }};">

                                <div class="overflow-y-auto" :class="device === 'desktop' ? 'h-[420px]' : 'h-[520px]'">

                                    <div class="relative">
                                        @if($catalogo->banner_url)
                                            <img src="{{ asset('storage/' . $catalogo->banner_url) }}" class="w-full h-24 object-cover">
                                        @else
                                            <div class="w-full h-24" style="background: linear-gradient(135deg, {{ $selectedTheme->primary_color ?? '#4F46E5' }}, {{ $selectedTheme->secondary_color ?? '#f0f0f0' }});"></div>
                                        @endif

                                        <div class="absolute left-1/2 -bottom-6 -translate-x-1/2">
                                            @if($catalogo->logo_url)
                                                <img src="{{ asset('storage/' . $catalogo->logo_url) }}" class="w-14 h-14 rounded-full object-cover border-2 border-white shadow">
                                            @else
                                                <div class="w-14 h-14 rounded-full border-2 border-white shadow flex items-center justify-center text-[10px] font-bold"
                                                     style="background-color: {{ $selectedTheme->primary_color ?? '#4F46E5' }}; color: white;">
                                                    {{ strtoupper(substr($name ?? $catalogo->name ?? 'C', 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="pt-9 pb-4 px-4 text-center">
                                        <p class="text-sm font-bold" style="color: {{ $selectedTheme->primary_font_color ?? '#333' }};">
                                            {{ $name ?? $catalogo->name ?? 'Nombre del catálogo' }}
                                        </p>
                                        <p class="text-[10px] mt-1 line-clamp-2" style="color: {{ $selectedTheme->secondary_font_color ?? '#666' }};">
                                            {{ $description ?? $catalogo->description ?? 'Descripción de tu catálogo aparecerá aquí.' }}
                                        </p>
                                    </div>

                                    <div class="px-4 mb-3">
                                        <div class="rounded-full px-3 py-1.5 text-[10px] shadow-sm" style="background-color: {{ $selectedTheme->secondary_color ?? '#f0f0f0' }}; color: {{ $selectedTheme->secondary_font_color ?? '#666' }};">
                                            🔍 Buscar productos...
                                        </div>
                                    </div>

                                    <div class="flex gap-2 px-4 mb-4 overflow-x-auto no-scrollbar">
                                        <span class="flex-shrink-0 text-[10px] px-3 py-1 rounded-full font-medium"
                                              style="background-color: {{ $selectedTheme->primary_color ?? '#4F46E5' }}; color: white;">Todos</span>
                                        @forelse (($catalogo->categories ?? collect())->take(3) as $cat)
                                            <span class="flex-shrink-0 text-[10px] px-3 py-1 rounded-full border"
                                                  style="border-color: {{ $selectedTheme->primary_color ?? '#4F46E5' }}; color: {{ $selectedTheme->primary_font_color ?? '#333' }};">{{ $cat->name }}</span>
                                        @empty
                                            @foreach(['Categoría A', 'Categoría B'] as $dummy)
                                                <span class="flex-shrink-0 text-[10px] px-3 py-1 rounded-full border"
                                                      style="border-color: {{ $selectedTheme->primary_color ?? '#4F46E5' }}; color: {{ $selectedTheme->primary_font_color ?? '#333' }};">{{ $dummy }}</span>
                                            @endforeach
                                        @endforelse
                                    </div>

                                    <div class="grid px-4 gap-3 pb-6" :class="device === 'desktop' ? 'grid-cols-3' : 'grid-cols-2'">
                                        @foreach(range(1, 3) as $i)
                                            <div class="rounded-lg overflow-hidden shadow-sm" style="background-color: {{ $selectedTheme->secondary_color ?? '#eee' }};">
                                                <div class="h-14" style="background-color: {{ $selectedTheme->bg_color ?? '#f0f0f0' }};"></div>
                                                <div class="p-1.5">
                                                    <p class="text-[9px] font-semibold truncate" style="color: {{ $selectedTheme->secondary_font_color ?? '#333' }};">Producto {{ $i }}</p>
                                                    <p class="text-[9px] font-bold" style="color: {{ $selectedTheme->primary_color ?? '#4F46E5' }};">$99.000</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== MODAL: EDITOR DE COLORES PERSONALIZADOS ===== --}}
                <div x-show="openCustom" x-data="{
                        bg: @entangle('bg_custom').live,
                        cardBg: @entangle('secondary_custom').live,
                        primary: @entangle('primary_custom').live,
                        fontMain: @entangle('primary_font_custom').live,
                        fontCard: @entangle('secondary_font_custom').live,
                        luminance(hex) {
                            hex = hex.replace('#','');
                            if (hex.length === 3) hex = hex.split('').map(c => c+c).join('');
                            const r = parseInt(hex.substr(0,2),16)/255, g = parseInt(hex.substr(2,2),16)/255, b = parseInt(hex.substr(4,2),16)/255;
                            const chan = c => c <= 0.03928 ? c/12.92 : Math.pow((c+0.055)/1.055, 2.4);
                            return 0.2126*chan(r) + 0.7152*chan(g) + 0.0722*chan(b);
                        },
                        ratio(h1, h2) {
                            const l1 = this.luminance(h1), l2 = this.luminance(h2);
                            const lighter = Math.max(l1,l2), darker = Math.min(l1,l2);
                            return ((lighter+0.05)/(darker+0.05)).toFixed(2);
                        },
                        badgeClass(r) {
                            r = parseFloat(r);
                            return r >= 4.5 ? 'bg-green-100 text-green-700' : (r >= 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                        },
                        badgeLabel(r) {
                            r = parseFloat(r);
                            return r >= 4.5 ? '✓ Buen contraste' : (r >= 3 ? '⚠ Contraste bajo' : '✗ Texto ilegible');
                        }
                     }"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4" x-cloak>

                    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-lg" @click.away="openCustom = false">
                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ __('messages.configure_colors') }}</h3>
                        <p class="text-xs text-gray-500 mb-5">Ajusta cada fondo junto a su texto — verás el contraste en vivo.</p>

                        <div class="space-y-5">
                            <div class="rounded-xl border border-gray-200 p-3">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-600 block mb-1">Fondo principal</label>
                                        <input type="color" x-model="bg" class="h-9 w-full rounded cursor-pointer">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-600 block mb-1">Texto sobre fondo principal</label>
                                        <input type="color" x-model="fontMain" class="h-9 w-full rounded cursor-pointer">
                                    </div>
                                </div>
                                <div class="rounded-lg p-3 flex items-center justify-between" :style="`background-color: ${bg}`">
                                    <span class="text-sm font-semibold" :style="`color: ${fontMain}`">Así se ve tu texto</span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full" :class="badgeClass(ratio(bg, fontMain))" x-text="badgeLabel(ratio(bg, fontMain))"></span>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 p-3">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-600 block mb-1">Fondo de tarjetas/producto</label>
                                        <input type="color" x-model="cardBg" class="h-9 w-full rounded cursor-pointer">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-600 block mb-1">Texto sobre tarjetas</label>
                                        <input type="color" x-model="fontCard" class="h-9 w-full rounded cursor-pointer">
                                    </div>
                                </div>
                                <div class="rounded-lg p-3 flex items-center justify-between" :style="`background-color: ${cardBg}`">
                                    <span class="text-sm font-semibold" :style="`color: ${fontCard}`">Nombre del producto</span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full" :class="badgeClass(ratio(cardBg, fontCard))" x-text="badgeLabel(ratio(cardBg, fontCard))"></span>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 p-3">
                                <label class="text-xs font-medium text-gray-600 block mb-1">Color de acento (botones)</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" x-model="primary" class="h-9 w-16 rounded cursor-pointer">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold" :style="`background-color: ${primary}; color: ${ratio(primary,'#ffffff') >= ratio(primary,'#000000') ? '#ffffff' : '#000000'}`">Ejemplo de botón</span>
                                </div>
                            </div>
                        </div>

                        <button @click="openCustom = false" type="button" class="mt-6 w-full bg-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:bg-indigo-700 transition">{{ __('messages.done') }}</button>
                    </div>
                </div>

            </div>
            {{-- ===================== FIN TEMAS / COLORES ===================== --}}

            <div class="flex justify-end p-0 m-0">
                <button type="button" wire:click="saveChanges" class="px-4 py-2 bg-indigo-600 text-white rounded ransition">{{ __('messages.save_changes') }}</button>
            </div>
        </form>
    </div>

<br>

</div>
</div>