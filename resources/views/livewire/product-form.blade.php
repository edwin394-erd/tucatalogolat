
  <div>
        <h2 class="text-2xl font-bold text-gray-700 mb-4">{{ $ItemId ? __('messages.edit_product') : __('messages.create_product') }}</h2>
        @if($maximoProductos && !$ItemId && $productosActuales >= $maximoProductos) 
            <div class="bg-red-100 text-red-700 text-sm font-medium inline-flex items-center px-2.5 py-2 rounded dark:bg-red-200 dark:text-red-900 " role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 me-1">
                    <path fill-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12z" clip-rule="evenodd"/>
                </svg>
                {{ __('messages.max_products_reached') }}
            </div>
        @endif
        <hr class="border-gray-400 my-4">
       @if($categories->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                <p class="font-bold">{{ __('messages.attention') }}</p>
                <p>{{ __('messages.product_needs_category') }} <a href="{{ route('create', ['model' => 'Category']) }}" class="text-blue-600 underline">{{ __('messages.create_category') }}</a></p>
            </div>
        @endif
        <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="col-span-2 sm:col-span-1">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.product_name') }}</label>
                <input type="text" wire:model="name" name="name" id="name" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="{{ __('messages.product_name_placeholder') }}">
                <x-input-error for="name" class="mt-2" />
            </div>
              <div class="col-span-2 sm:col-span-1">
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.category') }}</label>
                <select name="category" id="category" wire:model="category" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500">
                    <option value="">{{ __('messages.select_category') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="category" class="mt-2" />
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.price') }}</label>
                <input type="number" wire:model="price" name="price" id="price" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="$2999">
                <x-input-error for="price" class="mt-2" />
            </div>
        

            <div class="col-span-2 sm:col-span-1">
                <div class="flex" x-data="{ checked: false }">
                    <div class="col-span-1 sm:col-span-1 flex items-center">
                        <label for="precio_descuento" class="block mb-2 mr-2 text-sm font-medium text-gray-900">{{ __('messages.discount_price_optional') }}</label>
                        
                       
                    </div>
                </div>
                
                <input type="number" wire:model='precio_descuento' id="precio_descuento" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="$2000">
                {{-- <input type="number" wire:model="precio_descuento" name="precio_descuento" id="precio_descuento" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="$2999"> --}}
                <x-input-error for="precio_descuento" class="mt-2" />
            </div>
            

            
            <div class="col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.product_description') }}</label>
                <textarea id="description" rows="4" wire:model="description" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-100 inset-shadow-sm rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="{{ __('messages.product_description_placeholder') }}"></textarea>
                <x-input-error for="description" class="mt-2" />
            </div>

           

           
           
        </div>
                <label for="Imagen" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.product_image') }}</label>

        <div class="flex flex-wrap items-center justify-center w-full mb-2">
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-100 inset-shadow-sm gg:hover:bg-gray-800 gg:bg-gray-700 hover:bg-gray-100 gg:border-gray-600 gg:hover:border-gray-500 gg:hover:bg-gray-600">
                <div class="flex flex-col items-center justify-center p-6">
                    @if(count($existingImages) > 0 || count($images) > 0)
                        <div class="flex flex-wrap items-center justify-center gap-4 mb-2">
                            {{-- Imágenes ya guardadas --}}
                            @foreach($existingImages as $foto)
                                <div class="relative w-24 h-24">
                                    <img src="{{ asset('storage/' . $foto['url']) }}" alt="Foto guardada" class="w-full h-full object-cover rounded" />
                                    <button type="button" wire:click="markImageForDeletion({{ $foto['id'] }})" class="absolute top-0 right-0 p-1 text-white bg-red-500 rounded-full hover:bg-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            
                            {{-- Imágenes nuevas (no guardadas aún) --}}
                            @foreach(array_keys($images) as $index)
                                <div class="relative w-24 h-24">
                                    <img src="{{ $images[$index]->temporaryUrl() }}" alt="Foto temporal" class="w-full h-full object-cover rounded" />
                                    <button type="button" wire:click="removeNewImage({{ $index }})" class="absolute top-0 right-0 p-1 text-white bg-red-500 rounded-full hover:bg-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                      <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="#6b6b6b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8 22.0002H16C18.8284 22.0002 20.2426 22.0002 21.1213 21.1215C22 20.2429 22 18.8286 22 16.0002V15.0002C22 12.1718 22 10.7576 21.1213 9.8789C20.3529 9.11051 19.175 9.01406 17 9.00195M7 9.00195C4.82497 9.01406 3.64706 9.11051 2.87868 9.87889C2 10.7576 2 12.1718 2 15.0002L2 16.0002C2 18.8286 2 20.2429 2.87868 21.1215C3.17848 21.4213 3.54062 21.6188 4 21.749" stroke="#6b6b6b" stroke-width="1.5" stroke-linecap="round"></path> </g></svg><br>
                        <p class="mb-2 text-sm text-gray-500 gg:text-gray-400"><span class="font-semibold">{{ __('messages.click_to_upload') }}</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 gg:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                    @endif
                </div>
                <input id="dropzone-file" type="file" class="hidden" wire:model="images" multiple />
            </label>
        </div>

        <x-input-error for="images" class="mt-2" />
        <x-input-error for="images.*" class="mt-2" />
        <br>

        <!-- Variantes -->
        <div class="col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900">Variantes (opcional)</label>
            <button type="button" wire:click="addVariant" class="mb-2 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1">
                Agregar Variante
            </button>
            <div class="space-y-2">
                @foreach($variants as $index => $variant)
                    <div class="flex items-center space-x-2 bg-gray-50 p-2 rounded">
                        <input type="text" wire:model="variants.{{ $index }}.size" placeholder="Talla (ej. M)" class="flex-1 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                        <input type="text" wire:model="variants.{{ $index }}.color" placeholder="Color (ej. Rojo)" class="flex-1 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                        <input type="number" wire:model="variants.{{ $index }}.price_adjustment" placeholder="Ajuste precio (ej. 10)" step="0.01" class="flex-1 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                        <input type="number" wire:model="variants.{{ $index }}.stock" placeholder="Stock" min="0" class="flex-1 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                        <button type="button" wire:click="removeVariant({{ $index }})" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        <br>
      
         
            <div class="col-span-1 sm:col-span-1 flex items-center mt-2">
                <input type="checkbox" id="visible" wire:model="visible"  class="w-4 h-4 text-gray-600 bg-gray-100 border-gray-300 rounded focus:ring-gray-500 focus:ring-2" checked="true">
                <label for="visible" class="ml-2 text-sm font-medium text-gray-900 gg:text-white" checked>{{ __('messages.visible') }}</label>
                <x-input-error for="visible" class="ml-4" />
            </div>
       
        <div class="{{ $maximoProductos && !$ItemId && $productosActuales >= $maximoProductos ? ' justify-end pointer-events-none' : '' }} flex justify-end">

            @if($maximoProductos && !$ItemId && $productosActuales >= $maximoProductos)

            <button
                class="text-white inline-flex items-center bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center gg:bg-gray-600 gg:hover:bg-gray-700 gg:focus:ring-gray-800"
                wire:loading.attr="disabled"
                wire:target="images"
                @disabled($errors->has('images') || $errors->has('images.*'))
            >
                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        
                <span wire:target="save">{{ __('messages.save') }}</span>
            </button>
            @else

            <button
                 wire:click="save"
                 class="text-white inline-flex items-center bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center gg:bg-gray-600 gg:hover:bg-gray-700 gg:focus:ring-gray-800"
                 wire:loading.attr="disabled"
                 wire:target="images"
                 @disabled($errors->has('images') || $errors->has('images.*'))
             >
                 <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                 <span wire:loading wire:target="save" class="hidden">{{ __('messages.saving') }}</span>
                 <span wire:loading.remove wire:target="save">{{ __('messages.save') }}</span>
             </button>
            @endif
        </div>
</div>

    
