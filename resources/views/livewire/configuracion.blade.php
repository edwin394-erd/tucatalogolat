<div>


    <div class="mx-auto bg-white rounded-lg shadow-md p-6">
    <x-alert alert_type="success" />
    <h1 class="text-2xl font-bold text-gray-700  mb-5">Configura tu Catálogo</h1>
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Información del Catálogo</h2>

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
            <div class="w-full h-40 bg-gray-200 flex items-center justify-center rounded-t-lg text-gray-500">Sin banner</div>
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
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 border-4 border-white shadow">Sin logo</div>
                @endif
            </div>
        </div>
    </div>

 
    <br>

    <div class="">
        <form action="" wire:submit.prevent="saveChanges" enctype="multipart/form-data">
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Nombre del Catálogo:</label>
                <input type="text" wire:model="name" name="name" id="name" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="Nombre del Producto">
                <x-input-error for="name" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Descripción del Catálogo:</label>
                <textarea wire:model="description" id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-100 inset-shadow-sm rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="Escribe la descripción del catálogo aquí"></textarea>
                <x-input-error for="description" class="mt-2" />
            </div>
             <div class="col-span-2 sm:col-span-1 mb-4">
                <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Teléfono de Contacto:</label>
                <input type="text" wire:model="telefono_contacto" name="telefono" id="telefono" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-primary-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-primary-500 gg:focus:border-primary-500" placeholder="Número de teléfono">
                <x-input-error for="telefono" class="mt-2" />
            </div>
             <h2 class="text-xl font-semibold text-gray-700 mb-4">Apariencia</h2>
    <p>Selecciona la estructura del catálogo:</p><br>
<div x-data="{ selected: @entangle('plantilla_id') }" class="flex items-center mb-4">
    @foreach ($plantillas as $template)
        <div 
            wire:key="template-{{ $template->id }}"
            @click="selected = {{ $template->id }}" 
            :class="selected == {{ $template->id }} ? 'border-blue-500 shadow-lg bg-blue-50' : 'border-gray-300'"
            class="items-center mr-4 p-2 border rounded-lg cursor-pointer hover:shadow-lg transition">
            
            <div class="flex items-center space-x-2 p-2">
                <input type="radio" :checked="selected == {{ $template->id }}">
                <p class="mb-0">{{ $template->name }}</p>
            </div>
            
            <div class="ml-2 h-48 bg-gray-200 rounded-lg">
                <img src="{{ asset('imgs/'.$template->image_url) }}" class="max-w-full max-h-full object-contain">
            </div>
        </div>
    @endforeach
</div>
<p> Selecciona un tema de colores para tu catálogo:</p><br>
<div class="flex items-center space-x-4 mb-6">
</div>
            <div class="flex justify-end p-0 m-0">
                <button type="button" wire:click="saveChanges" class="px-4 py-2 bg-indigo-600 text-white rounded ransition">Guardar Cambios</button>
            </div>
        </form>
    </div>
   
<br>



</div>