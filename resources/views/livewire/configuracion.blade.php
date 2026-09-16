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

    $selectedThemeKey = is_object($selectedTheme) ? ($selectedTheme->id ?? 'custom') : ($selectedTheme ?? 'none');
    $needsBrandSetup = blank($catalogo->description) || blank($catalogo->logo_url) || blank($catalogo->banner_url);
    $needsDesignSetup = ! $catalogo->design_configured;
@endphp

<div x-data="{ 
    activeTab: @js($needsBrandSetup ? 'general' : ($needsDesignSetup ? 'design' : 'general')), 
    selectedTemplate: @entangle('plantilla_id'), 
    selectedTheme: @entangle('tema_id'), 
    openCustom: false 
}" class="mx-auto max-w-6xl bg-white rounded-xl shadow-md p-6">

    <x-alert alert_type="success" />

    <!-- Encabezado de Página -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.configure_catalog') }}</h1>
            <p class="text-sm text-gray-500">Personaliza la información, apariencia y redes sociales de tu catálogo.</p>
        </div>
    </div>

    @if($needsBrandSetup)
        <div class="mb-6 flex flex-col gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 sm:flex-row sm:items-center sm:justify-between" role="alert">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.1 12.28A1.5 1.5 0 004.49 18.4h15.02a1.5 1.5 0 001.3-2.26l-7.1-12.28a1.5 1.5 0 00-2.6 0z"/></svg>
                <div>
                    <p class="text-sm font-bold">Primero completa la información de tu marca</p>
                    <p class="mt-1 text-xs leading-5">Agrega descripción, logo y banner. Después podrás elegir la plantilla y los colores.</p>
                </div>
            </div>
        </div>
    @elseif($needsDesignSetup)
        <div class="mb-6 flex flex-col gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 sm:flex-row sm:items-center sm:justify-between" role="alert">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.1 12.28A1.5 1.5 0 004.49 18.4h15.02a1.5 1.5 0 001.3-2.26l-7.1-12.28a1.5 1.5 0 00-2.6 0z"/></svg>
                <div>
                    <p class="text-sm font-bold">Completa primero el diseño de tu catálogo</p>
                    <p class="mt-1 text-xs leading-5">Selecciona una plantilla y una paleta de colores. Son pasos obligatorios para publicar tu catálogo.</p>
                </div>
            </div>
            <button type="button" @click="activeTab = 'design'" class="shrink-0 rounded-lg bg-amber-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-amber-700">Ir a Diseño y Estilo</button>
        </div>
    @endif

    <!-- Navegación por Pestañas -->
    <div class="flex border-b border-gray-200 mb-8 overflow-x-auto no-scrollbar">
        <button type="button" @click="activeTab = 'general'"
            :class="activeTab === 'general' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="py-3 px-5 border-b-2 font-medium text-sm transition-all flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Información y Marca
        </button>

        <button type="button" @click="activeTab = 'social'"
            :class="activeTab === 'social' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="py-3 px-5 border-b-2 font-medium text-sm transition-all flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            Redes Sociales
        </button>

        <button type="button" @click="activeTab = 'design'"
            :class="activeTab === 'design' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="py-3 px-5 border-b-2 font-medium text-sm transition-all flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            Diseño y Estilo
            @if($needsDesignSetup && ! $needsBrandSetup)
                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">Obligatorio</span>
            @endif
        </button>
    </div>

    <!-- Formulario Unificado -->
    <form wire:submit.prevent="saveChanges" enctype="multipart/form-data">

        <!-- ================= PESTAÑA 1: INFORMACIÓN Y MARCA ================= -->
        <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            
            <!-- Editor visual Banner & Logo -->
            <div class="relative mb-10">
                <label class="absolute top-3 left-3 z-10 bg-white/90 hover:bg-white text-gray-700 px-3 py-1.5 rounded-lg cursor-pointer text-xs font-semibold shadow-md transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Cambiar Banner
                    <input type="file" wire:model="banner" id="banner" accept="image/*" class="hidden">
                </label>

                @if($catalogo->banner_url)
                    <img src="{{ asset('storage/' . $catalogo->banner_url) }}" alt="Banner" class="w-full h-44 object-cover rounded-xl shadow-inner border">
                @else
                    <div class="w-full h-44 bg-gradient-to-r from-slate-100 to-slate-200 border border-dashed border-gray-300 flex items-center justify-center rounded-xl text-gray-400 text-sm font-medium">
                        {{ __('messages.no_banner') }}
                    </div>
                @endif

                <!-- Logo superpuesto -->
                <div class="absolute left-6 -bottom-8">
                    <div class="relative group">
                        <label class="absolute inset-0 bg-black/40 rounded-full cursor-pointer opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                            <input type="file" wire:model="logo" id="logo" accept="image/*" class="hidden">
                        </label>
                        
                        @if($catalogo->logo_url)
                            <img src="{{ asset('storage/' . $catalogo->logo_url) }}" alt="Logo" class="w-24 h-24 rounded-full object-cover border-4 border-white bg-white shadow-md">
                        @else
                            <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center text-gray-400 border-4 border-white shadow-md text-xs font-semibold">
                                {{ __('messages.no_logo') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="-mt-6 mb-5 grid grid-cols-1 gap-1 sm:grid-cols-2">
                <x-input-error for="banner" />
                <x-input-error for="logo" />
            </div>

            <!-- Campos de Información General -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-12">
                <div class="md:col-span-2">
                    <label for="name" class="block mb-1.5 text-sm font-medium text-gray-700">{{ __('messages.catalog_name') }}</label>
                    <input type="text" wire:model="name" id="name" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" placeholder="{{ __('messages.catalog_name_placeholder') }}">
                    <x-input-error for="name" class="mt-1" />
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block mb-1.5 text-sm font-medium text-gray-700">{{ __('messages.catalog_description') }}</label>
                    <textarea wire:model="description" id="description" rows="3" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" placeholder="{{ __('messages.catalog_description_placeholder') }}"></textarea>
                    <x-input-error for="description" class="mt-1" />
                </div>

                <div>
                    <label for="telefono" class="block mb-1.5 text-sm font-medium text-gray-700">{{ __('messages.contact_phone') }}</label>
                    <input type="text" wire:model="telefono_contacto" id="telefono" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" placeholder="{{ __('messages.contact_phone_placeholder') }}">
                    <x-input-error for="telefono" class="mt-1" />
                </div>

                <div>
                    <label for="horario" class="block mb-1.5 text-sm font-medium text-gray-700">{{ __('messages.schedule') }}</label>
                    <input type="text" wire:model="horario" id="horario" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" placeholder="{{ __('messages.schedule_placeholder') }}">
                    <x-input-error for="horario" class="mt-1" />
                </div>

                <div>
                    <label for="ubicacion" class="block mb-1.5 text-sm font-medium text-gray-700">{{ __('messages.location') }}</label>
                    <input type="text" wire:model="ubicacion" id="ubicacion" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" placeholder="{{ __('messages.location_placeholder') }}">
                    <x-input-error for="ubicacion" class="mt-1" />
                </div>

                <div>
                    <label for="ubicacion_mapa" class="block mb-1.5 text-sm font-medium text-gray-700">{{ __('messages.map_location') }}</label>
                    <div class="flex gap-2">
                        <input type="text" wire:model="ubicacion_mapa" id="ubicacion_mapa" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition" placeholder="{{ __('messages.map_location_placeholder') }}">
                        <button type="button" @click="window.open('https://www.google.com/maps', '_blank')" class="px-3.5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-semibold text-xs rounded-lg transition whitespace-nowrap border border-indigo-200">
                            {{ __('messages.pick_map') }}
                        </button>
                    </div>
                    <x-input-error for="ubicacion_mapa" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- ================= PESTAÑA 2: REDES SOCIALES ================= -->
        <div x-show="activeTab === 'social'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="instagram" class="block mb-1.5 text-sm font-medium text-gray-700">Instagram</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">@</span>
                        <input type="text" wire:model="instagram" id="instagram" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-8 p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="{{ __('messages.instagram_placeholder') }}">
                    </div>
                    <x-input-error for="instagram" class="mt-1" />
                </div>

                <div>
                    <label for="facebook" class="block mb-1.5 text-sm font-medium text-gray-700">Facebook</label>
                    <input type="text" wire:model="facebook" id="facebook" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="{{ __('messages.facebook_placeholder') }}">
                    <x-input-error for="facebook" class="mt-1" />
                </div>

                <div>
                    <label for="twitter" class="block mb-1.5 text-sm font-medium text-gray-700">Twitter / X</label>
                    <input type="text" wire:model="twitter" id="twitter" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="{{ __('messages.twitter_placeholder') }}">
                    <x-input-error for="twitter" class="mt-1" />
                </div>

                <div>
                    <label for="tiktok" class="block mb-1.5 text-sm font-medium text-gray-700">TikTok</label>
                    <input type="text" wire:model="tiktok" id="tiktok" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="{{ __('messages.tiktok_placeholder') }}">
                    <x-input-error for="tiktok" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- ================= PESTAÑA 3: DISEÑO Y ESTILO ================= -->
        <div x-show="activeTab === 'design'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(360px,0.9fr)] gap-8 items-start">
                <div>
            
            <!-- Estructura de Plantillas -->
            <div class="mb-10">
                <h3 class="text-base font-semibold text-gray-800 mb-3">{{ __('messages.select_catalog_structure') }}</h3>
                <x-input-error for="plantilla_id" class="mb-3" />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($plantillas as $template)
                        <div wire:key="template-{{ $template->id }}-{{ $selectedTemplate ?? 'none' }}"
                             wire:click="selectTemplate({{ $template->id }})"
                             @click="selectedTemplate = {{ $template->id }}"
                             :class="selectedTemplate == {{ $template->id }} ? 'border-indigo-600 ring-2 ring-indigo-500/20 bg-indigo-50/30' : 'border-gray-200 hover:border-gray-300 bg-white'"
                             class="p-3 border rounded-xl cursor-pointer transition shadow-sm flex flex-col justify-between">
                            
                            <div class="flex items-center space-x-2.5 mb-2">
                                <input type="radio" :checked="selectedTemplate == {{ $template->id }}" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="font-medium text-sm text-gray-800">{{ $template->name }}</span>
                            </div>
                            
                            <div class="rounded-lg overflow-hidden bg-gray-100 border border-gray-100">
                                <img src="{{ asset('imgs/'.$template->image_url) }}" class="w-full h-28 object-cover">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Selección de Temas de Color -->
            <div class="mb-10">
                <h3 class="text-base font-semibold text-gray-800 mb-1">{{ __('messages.select_theme') }}</h3>
                <p class="text-xs text-gray-500 mb-4">Elige una paleta cromática optimizada para tu catálogo.</p>
                <x-input-error for="tema_id" class="mb-3" />

                <div class="flex flex-wrap gap-4">
                    @foreach ($themes as $theme)
                        @php
                            $ratioMain = contrastRatio($theme->bg_color, $theme->primary_font_color);
                            $ratioCard = contrastRatio($theme->secondary_color, $theme->secondary_font_color);
                            $worst = min($ratioMain, $ratioCard);
                            $badge = contrastBadge($worst);
                        @endphp
                        <div wire:key="theme-{{ $theme->id }}-{{ $selectedThemeKey }}" 
                             wire:click="selectTheme({{ $theme->id }})" 
                             @click="selectedTheme = {{ $theme->id }}"
                             :class="selectedTheme == {{ $theme->id }} ? 'ring-2 ring-indigo-600 border-transparent shadow-md' : 'border-gray-200 hover:border-gray-300'"
                             class="w-40 rounded-xl cursor-pointer transition overflow-hidden border bg-white shadow-sm flex flex-col justify-between">

                            <div class="p-3" style="background-color: {{ $theme->bg_color }};">
                                <p class="text-[11px] font-bold" style="color: {{ $theme->primary_font_color }};">{{ $theme->name }}</p>
                                <p class="text-[9px] mt-0.5" style="color: {{ $theme->primary_font_color }}; opacity: 0.8;">Texto de ejemplo</p>

                                <div class="mt-2 rounded-lg p-2" style="background-color: {{ $theme->secondary_color }};">
                                    <p class="text-[9px] font-semibold" style="color: {{ $theme->secondary_font_color }};">Tarjeta / producto</p>
                                </div>

                                <span class="inline-block mt-2 text-[9px] font-bold px-2 py-0.5 rounded-full"
                                      style="background-color: {{ $theme->primary_color }}; color: {{ readableTextColor($theme->primary_color) }};">
                                    Botón
                                </span>
                            </div>

                            <div class="flex items-center justify-between px-3 py-2 border-t border-gray-100 bg-gray-50">
                                <input type="radio" :checked="selectedTheme == {{ $theme->id }}" class="text-indigo-600 pointer-events-none">
                                <span class="text-[9px] font-semibold px-2 py-0.5 rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            </div>
                        </div>
                    @endforeach

                    <!-- Opción Personalizada -->
                    <div wire:key="theme-custom-{{ $selectedThemeKey }}" wire:click="selectCustomTheme()" 
                         @click="selectedTheme = 'custom'"
                         :class="selectedTheme == 'custom' ? 'ring-2 ring-indigo-600 border-transparent shadow-md' : 'border-gray-200 hover:border-gray-300'"
                         class="w-40 rounded-xl cursor-pointer transition overflow-hidden border bg-white shadow-sm flex flex-col justify-between group relative">

                        <button @click.stop="openCustom = true" type="button" title="Editar colores" class="absolute top-2 right-2 z-10 bg-indigo-600 text-white rounded-full p-1.5 shadow hover:bg-indigo-700 transition opacity-0 group-hover:opacity-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
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
                        <div class="flex items-center justify-between px-3 py-2 border-t border-gray-100 bg-gray-50">
                            <input type="radio" :checked="selectedTheme == 'custom'" class="text-indigo-600 pointer-events-none">
                            <span class="text-[9px] font-semibold px-2 py-0.5 rounded-full {{ $cBadge['class'] }}">{{ $cBadge['label'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

                </div>

            <!-- Vista Previa en Vivo -->
            <div x-data="{ device: 'desktop' }" class="lg:sticky lg:top-4 lg:self-start border-t border-gray-200 pt-8 lg:border-t-0 lg:pt-0">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-gray-800">{{ __('messages.live_preview') ?? 'Vista previa en vivo' }}</h3>

                    <div class="inline-flex items-center rounded-lg bg-gray-100 p-1 text-xs font-medium border">
                        <button type="button" @click="device = 'desktop'"
                                :class="device === 'desktop' ? 'bg-white shadow text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="1.5"/><path d="M8 20h8M12 16v4"/></svg>
                            Escritorio
                        </button>
                        <button type="button" @click="device = 'mobile'"
                                :class="device === 'mobile' ? 'bg-white shadow text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="7" y="2" width="10" height="20" rx="2"/><path d="M11 18h2"/></svg>
                            Móvil
                        </button>
                    </div>
                </div>

                <div class="flex justify-center bg-slate-100 rounded-2xl p-6 border border-slate-200 overflow-hidden">
                    <div class="transition-all duration-300 ease-out" :class="device === 'desktop' ? 'w-full max-w-3xl' : 'w-[310px]'">

                        <div x-show="device === 'desktop'" class="rounded-t-xl bg-slate-200 border border-b-0 border-gray-300 px-3 py-2 flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                            </div>
                            <div class="flex-1 mx-3 bg-white rounded-md px-3 py-0.5 text-[11px] text-gray-500 truncate border border-gray-200">
                                🔒 tucatalogolat.domcloud.dev/{{ auth()->user()->catalogo->name_handle ?? 'tu-catalogo' }}
                            </div>
                        </div>

                        <div x-show="device === 'mobile'" class="rounded-t-[1.5rem] bg-gray-900 h-6 flex items-center justify-center">
                            <div class="w-16 h-1.5 rounded-full bg-gray-700"></div>
                        </div>

                        <div class="border border-gray-300 overflow-hidden"
                             :class="device === 'desktop' ? 'rounded-b-xl' : 'rounded-b-[1.5rem] border-x-4 border-b-4 border-gray-900'"
                             style="background-color: {{ $selectedTheme->bg_color ?? '#F2F2F2' }};">

                            <div class="overflow-y-auto" :class="device === 'desktop' ? 'h-[300px]' : 'h-[390px]'">

                                {{-- Plantilla 1: catálogo clásico --}}
                                <div x-show="selectedTemplate == 1" class="min-h-full">
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

                                    <div class="pt-8 pb-3 px-4 text-center">
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

                                    <div class="grid px-4 gap-3 pb-6" :class="device === 'desktop' ? 'grid-cols-3' : 'grid-cols-2'">
                                        @foreach(range(1, 3) as $i)
                                            <div class="rounded-lg overflow-hidden shadow-sm" style="background-color: {{ $selectedTheme->secondary_color ?? '#eee' }};">
                                                <div class="h-12" style="background-color: {{ $selectedTheme->bg_color ?? '#f0f0f0' }};"></div>
                                                <div class="p-1.5">
                                                    <p class="text-[9px] font-semibold truncate" style="color: {{ $selectedTheme->secondary_font_color ?? '#333' }};">Producto {{ $i }}</p>
                                                    <p class="text-[9px] font-bold" style="color: {{ $selectedTheme->primary_color ?? '#4F46E5' }};">$99.000</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Plantilla 2: sidebar --}}
                                <div x-show="selectedTemplate == 2" class="flex min-h-full" style="min-height: 100%;">
                                    <div class="w-1/3 p-2 border-r border-gray-300" style="background-color: {{ $selectedTheme->secondary_color ?? '#f4f4f5' }}; color: {{ $selectedTheme->secondary_font_color ?? '#333' }};">
                                        <div class="flex items-center justify-center mb-3">
                                            @if($catalogo->logo_url)
                                                <img src="{{ asset('storage/' . $catalogo->logo_url) }}" class="w-9 h-9 rounded-full object-cover border border-white">
                                            @else
                                                <div class="w-9 h-9 rounded-full border border-white" style="background-color: {{ $selectedTheme->primary_color ?? '#4F46E5' }};"></div>
                                            @endif
                                        </div>
                                        <div class="space-y-2 text-[9px] font-semibold">
                                            <div class="rounded p-1.5" style="background-color: rgba(0,0,0,0.04);">Inicio</div>
                                            <div class="rounded p-1.5">Categorías</div>
                                            <div class="rounded p-1.5">Productos</div>
                                        </div>
                                    </div>
                                    <div class="flex-1 p-3">
                                        <div class="rounded-lg p-2 mb-2 text-[9px]" style="background-color: {{ $selectedTheme->secondary_color ?? '#f4f4f5' }}; color: {{ $selectedTheme->primary_font_color ?? '#333' }};">
                                            Buscar productos...
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach(range(1, 4) as $i)
                                                <div class="rounded-md overflow-hidden" style="background-color: {{ $selectedTheme->secondary_color ?? '#f4f4f5' }};">
                                                    <div class="h-12" style="background-color: {{ $selectedTheme->bg_color ?? '#fff' }};"></div>
                                                    <div class="p-2 text-[8px]" style="color: {{ $selectedTheme->secondary_font_color ?? '#666' }};">
                                                        <p class="font-bold">Prod {{ $i }}</p>
                                                        <p style="color: {{ $selectedTheme->primary_color ?? '#4F46E5' }};">$59</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Plantilla 3: restaurante / hero --}}
                                <div x-show="selectedTemplate == 3" class="min-h-full" style="background-color: {{ $selectedTheme->bg_color ?? '#fff' }};">
                                    <div class="p-3" style="background: linear-gradient(135deg, {{ $selectedTheme->primary_color ?? '#4F46E5' }}, {{ $selectedTheme->secondary_color ?? '#f0f0f0' }}); color: white;">
                                        <div class="flex items-center justify-between text-[9px] font-semibold">
                                            <span>{{ $name ?? $catalogo->name ?? 'Mi catálogo' }}</span>
                                            <span>Menú</span>
                                        </div>
                                        <div class="mt-4 rounded-2xl p-3" style="background-color: rgba(255,255,255,0.12);">
                                            <p class="text-[10px] uppercase tracking-[0.2em] opacity-80">Especialidades</p>
                                            <h4 class="mt-1 text-lg font-bold">{{ $name ?? $catalogo->name ?? 'Tu marca' }}</h4>
                                            <p class="mt-2 text-[9px] leading-4 opacity-90">{{ $description ?? $catalogo->description ?? 'Descripción breve de la categoría.' }}</p>
                                        </div>
                                    </div>
                                    <div class="p-3 space-y-2">
                                        @foreach(range(1, 3) as $i)
                                            <div class="flex items-center gap-2 rounded-xl p-2" style="background-color: {{ $selectedTheme->secondary_color ?? '#f5f5f5' }};">
                                                <div class="h-10 w-10 rounded-lg" style="background-color: {{ $selectedTheme->bg_color ?? '#fff' }};"></div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-[9px] font-bold truncate" style="color: {{ $selectedTheme->primary_font_color ?? '#333' }};">Producto {{ $i }}</p>
                                                    <p class="text-[8px] truncate" style="color: {{ $selectedTheme->secondary_font_color ?? '#666' }};">Descripción corta</p>
                                                </div>
                                                <span class="text-[9px] font-bold" style="color: {{ $selectedTheme->primary_color ?? '#4F46E5' }};">$79</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </div>

           <!-- Modal Editor Personalizado con Preview de Contraste en Vivo -->
        <div x-show="openCustom" 
            x-cloak
            x-data="{
                bg: @entangle('bg_custom').live,
                cardBg: @entangle('secondary_custom').live,
                primary: @entangle('primary_custom').live,
                fontMain: @entangle('primary_font_custom').live,
                fontCard: @entangle('secondary_font_custom').live,
                luminance(hex) {
                    hex = (hex || '#ffffff').replace('#','');
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
                badge(r) {
                    if (r >= 4.5) return { text: '✓ Excelente (' + r + ')', class: 'bg-green-100 text-green-700 border border-green-200' };
                    if (r >= 3) return { text: '⚠ Aceptable (' + r + ')', class: 'bg-yellow-100 text-yellow-700 border border-yellow-200' };
                    return { text: '✗ Ilegible (' + r + ')', class: 'bg-red-100 text-red-700 border border-red-200' };
                }
            }" 
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            
            <div @click.away="openCustom = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-center justify-between border-b pb-3 mb-4">
                    <h4 class="font-bold text-gray-800">Personalizar Paleta de Colores</h4>
                    <button type="button" @click="openCustom = false" class="text-gray-400 hover:text-gray-600 font-bold">✕</button>
                </div>

                <!-- Previews de Contraste en Vivo -->
                <div class="space-y-3 mb-6">
                    <div class="p-3.5 rounded-xl border flex items-center justify-between transition-colors shadow-sm" :style="'background-color: ' + bg + '; color: ' + fontMain">
                        <div>
                            <p class="text-xs font-bold">Texto Principal</p>
                            <p class="text-[10px] opacity-80">Vista previa sobre el fondo general</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full" :class="badge(ratio(bg, fontMain)).class" x-text="badge(ratio(bg, fontMain)).text"></span>
                    </div>

                    <div class="p-3.5 rounded-xl border flex items-center justify-between transition-colors shadow-sm" :style="'background-color: ' + cardBg + '; color: ' + fontCard">
                        <div>
                            <p class="text-xs font-bold">Texto de Tarjeta / Producto</p>
                            <p class="text-[10px] opacity-80">Vista previa sobre el fondo secundario</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full" :class="badge(ratio(cardBg, fontCard)).class" x-text="badge(ratio(cardBg, fontCard)).text"></span>
                    </div>
                </div>

                <!-- Selectores de Color -->
                <div class="space-y-3.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-700">Fondo General</label>
                        <input type="color" x-model="bg" class="w-8 h-8 rounded-lg border border-gray-300 cursor-pointer">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-700">Texto Principal</label>
                        <input type="color" x-model="fontMain" class="w-8 h-8 rounded-lg border border-gray-300 cursor-pointer">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-700">Fondo Tarjeta / Producto</label>
                        <input type="color" x-model="cardBg" class="w-8 h-8 rounded-lg border border-gray-300 cursor-pointer">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-700">Texto Tarjeta</label>
                        <input type="color" x-model="fontCard" class="w-8 h-8 rounded-lg border border-gray-300 cursor-pointer">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-700">Color Primario / Botones</label>
                        <input type="color" x-model="primary" class="w-8 h-8 rounded-lg border border-gray-300 cursor-pointer">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" @click="openCustom = false" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition shadow">
                        Listo
                    </button>
                </div>
            </div>
        </div>
        </div>

        <!-- Botón de Guardar Fijo/Persistente -->
        <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-lg shadow-md transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar Cambios
            </button>
        </div>

    </form>
</div>