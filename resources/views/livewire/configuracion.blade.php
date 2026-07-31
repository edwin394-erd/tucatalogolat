<div>


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
<div x-data="{ selectedTheme: @entangle('tema_id'), openCustom: false }" class="mb-6">
    <p>{{ __('messages.select_theme') }}</p><br>
    <div class="flex flex-wrap gap-4">
        @foreach ($themes as $theme)
            <div wire:key="theme-{{ $theme->id }}" wire:click="selectTheme({{ $theme->id }})" @click="selectedTheme = {{ $theme->id }}" 
                :class="selectedTheme == {{ $theme->id }} ? ' bg-indigo-50 border-indigo-500' : 'bg-white'"
                class="w-50 h-20 rounded-lg cursor-pointer transition flex flex-col items-center justify-center p-2 shadow-sm border border-gray-300">
                <p class="text-xs font-bold mb-2 text-center">{{ $theme->name }}</p>
                <div class="flex space-x-2">
                    <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $theme->primary_color }};"></div>
                    <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $theme->bg_color }};"></div>
                    <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $theme->secondary_color }};"></div>
                    <p>|</p>
                     <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $theme->primary_font_color }};"></div>
                    <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $theme->secondary_font_color }};"></div>
                </div>
            </div>
        @endforeach

        <div wire:click="selectCustomTheme()" @click="selectedTheme = 'custom';" 
            :class="selectedTheme == 'custom' ? 'ring-1 ring-indigo-500 bg-indigo-50 border-indigo-500' : 'bg-white'"
            class="w-auto h-20 rounded-lg cursor-pointer transition flex flex-col items-center justify-center p-2 shadow-sm border group relative">
            <p class="text-xs font-bold mb-2 text-center">{{ __('messages.custom') }}</p>
            <button @click.stop="openCustom = true" type="button" class="absolute -top-2 -right-2 bg-indigo-600 text-white rounded-full p-1.5 shadow-lg hover:bg-indigo-700 transition opacity-0 group-hover:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
            </button>
           
                <div class="flex space-x-2">
                    <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $primary_custom ?? '#000000' }};"></div>
                    <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $bg_custom ?? '#ffffff' }};"></div>
                    <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $secondary_custom ?? '#cccccc' }};"></div>
                    <p>|</p>
                     <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $primary_font_custom ?? '#000000' }};"></div>
                    <div class="w-6 h-6 rounded-full shadow-md" style="background-color: {{ $secondary_font_custom ?? '#000000' }};"></div>
                </div>
           
        </div>
    </div>

    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Vista previa en vivo</h3>
        <div class="grid gap-4 lg:grid-cols-[1.6fr_1fr]">
            <div class="rounded-3xl overflow-hidden border border-gray-200 shadow-sm">
                <div class="p-6" style="background-color: {{ $selectedTheme->secondary_color ?? '#ffffff' }}; color: {{ $selectedTheme->primary_font_color ?? '#111827' }};">
                    <h4 class="text-2xl font-bold mb-2" style="color: {{ $selectedTheme->primary_font_color ?? '#1f2937' }};">{{ __('messages.color_preview') }}</h4>
                    <div class="space-y-3">
                        <div class="rounded-xl p-4" style="background-color: {{ $selectedTheme->bg_color ?? '#f8fafc' }}; color: {{ $selectedTheme->primary_font_color ?? '#111827' }};">
                            <p class="font-medium">Ejemplo de texto</p>
                            <p class="text-sm">Aquí se ve cómo quedaría tu catálogo con los colores actuales.</p>
                        </div>
                        <div class="flex gap-3 flex-wrap">
                            <span class="inline-flex items-center justify-center h-9 px-4 rounded-full" style="background-color: {{ $selectedTheme->primary_color ?? '#4338ca' }}; color: {{ $selectedTheme->primary_font_color ?? '#ffffff' }};">Botón principal</span>
                            <span class="inline-flex items-center justify-center h-9 px-4 rounded-full border" style="border-color: {{ $selectedTheme->primary_color ?? '#4338ca' }}; color: {{ $selectedTheme->primary_font_color ?? '#4338ca' }};">Botón secundario</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <h4 class="text-lg font-semibold mb-4">Colores seleccionados</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl p-4" style="background-color: {{ $selectedTheme->primary_color ?? '#4338ca' }}; color: {{ $selectedTheme->secondary_font_color ?? '#ffffff' }};">
                        <p class="text-sm font-semibold">Primario</p>
                        <p class="text-xs">{{ $selectedTheme->primary_color ?? '#4338ca' }}</p>
                    </div>
                    <div class="rounded-2xl p-4" style="background-color: {{ $selectedTheme->secondary_color ?? '#f3f4f6' }}; color: {{ $selectedTheme->primary_font_color ?? '#111827' }};">
                        <p class="text-sm font-semibold">Secundario</p>
                        <p class="text-xs">{{ $selectedTheme->secondary_color ?? '#f3f4f6' }}</p>
                    </div>
                    <div class="rounded-2xl p-4" style="background-color: {{ $selectedTheme->bg_color ?? '#ffffff' }}; color: {{ $selectedTheme->primary_font_color ?? '#111827' }};">
                        <p class="text-sm font-semibold">Fondo</p>
                        <p class="text-xs">{{ $selectedTheme->bg_color ?? '#ffffff' }}</p>
                    </div>
                    <div class="rounded-2xl p-4" style="background-color: {{ $selectedTheme->primary_font_color ?? '#111827' }}; color: {{ $selectedTheme->bg_color ?? '#ffffff' }};">
                        <p class="text-sm font-semibold">Texto primario</p>
                        <p class="text-xs">{{ $selectedTheme->primary_font_color ?? '#111827' }}</p>
                    </div>
                    <div class="rounded-2xl p-4" style="background-color: {{ $selectedTheme->secondary_font_color ?? '#6b7280' }}; color: {{ $selectedTheme->bg_color ?? '#ffffff' }};">
                        <p class="text-sm font-semibold">Texto secundario</p>
                        <p class="text-xs">{{ $selectedTheme->secondary_font_color ?? '#6b7280' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="openCustom" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-100/50" x-cloak>
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md" @click.away="openCustom = false">
            <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('messages.configure_colors') }}</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium">{{ __('messages.primary_color') }}</label>
                    <input type="color" wire:model.live="primary_custom" class="h-10 w-20 rounded cursor-pointer">
                </div>
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium">{{ __('messages.bg_color') }}</label>
                    <input type="color" wire:model.live="bg_custom" class="h-10 w-20 rounded cursor-pointer">
                </div>
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium">{{ __('messages.secondary_color') }}</label>
                    <input type="color" wire:model.live="secondary_custom" class="h-10 w-20 rounded cursor-pointer">
                </div>
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium">{{ __('messages.primary_font_color') }}</label>
                    <input type="color" wire:model.live="primary_font_custom" class="h-10 w-20 rounded cursor-pointer">
                </div>
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium">{{ __('messages.secondary_font_color') }}</label>
                    <input type="color" wire:model.live="secondary_font_custom" class="h-10 w-20 rounded cursor-pointer">
                </div>
                
            </div>
            <button @click="openCustom = false;" type="button" class="mt-6 w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold">{{ __('messages.done') }}</button>
        </div>
    </div>


</div>
</div>
            <div class="flex justify-end p-0 m-0">
                <button type="button" wire:click="saveChanges" class="px-4 py-2 bg-indigo-600 text-white rounded ransition">{{ __('messages.save_changes') }}</button>
            </div>
        </form>
    </div>
   
<br>



</div>