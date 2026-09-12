<div class="px-5 my-5">

<div class="px-5 my-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @if(auth()->user()->role == 'admin')

        <x-dashboard-card 
            title="{{ __('messages.users') }}" 
            content="Total de usuarios: {{ $n_usuarios }}"
            :link="route('usuarios')" />
        <x-dashboard-card
          title="{{ __('messages.subscriptions') }}" 
          content="Total de suscripciones activas: {{ $n_suscripciones_activas }}"
          :link="route('subscripciones')" />
        
        {{-- <x-dashboard-card
          title="{{ __('messages.expired_subscriptions') }}"
          content="Total de suscripciones expiradas: {{ $n_suscripciones_expiradas }}" />
        
        <x-dashboard-card
          title="{{ __('messages.pending_subscriptions') }}"
          content="Total de suscripciones pendientes: {{ $n_suscripciones_pendientes }}" /> --}}
        
        <x-dashboard-card
          title="{{ __('messages.plans') }}"
          content="Total de planes: {{ $n_planes }}"
          :link="route('planes')" />
{{--         
        <x-dashboard-card
          title="Suscripciones activas en los últimos 7 días" 
          content="Total de suscripciones activas en los últimos 7 días: {{ $n_suscripciones_activas_ultimos_7_dias }}" />

        <x-dashboard-card
          title="Suscripciones expiradas en los últimos 7 días"
          content="Total de suscripciones expiradas en los últimos 7 días: {{ $n_suscripciones_expiradas_ultimos_7_dias }}" /> --}}
      
      @elseif(auth()->user()->role == 'user')
        <x-dashboard-card 
          title="{{ __('messages.products') }}" 
          content="{{ __('messages.total_products') }}: {{ $n_productos }}"
          :link="route('products')"
          icon="products" />

        <x-dashboard-card 
          title="{{ __('messages.categories') }}"
          content="{{ __('messages.total_categories') }}: {{ $n_categorias }}"
          :link="route('categories')"
          icon="categories" />
      @endif
    </div>

    @if($catalogLink)
        <div class="mt-6 px-5">
            <div class="shadow-xl rounded-3xl bg-white p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                                <svg width="18" height="18" viewBox="0 0 16 16" preserveAspectRatio="xMidYMid meet" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.05025 1.53553C8.03344 0.552348 9.36692 0 10.7574 0C13.6528 0 16 2.34721 16 5.24264C16 6.63308 15.4477 7.96656 14.4645 8.94975L12.4142 11L11 9.58579L13.0503 7.53553C13.6584 6.92742 14 6.10264 14 5.24264C14 3.45178 12.5482 2 10.7574 2C9.89736 2 9.07258 2.34163 8.46447 2.94975L6.41421 5L5 3.58579L7.05025 1.53553Z" />
                                    <path d="M7.53553 13.0503L9.58579 11L11 12.4142L8.94975 14.4645C7.96656 15.4477 6.63308 16 5.24264 16C2.34721 16 0 13.6528 0 10.7574C0 9.36693 0.552347 8.03344 1.53553 7.05025L3.58579 5L5 6.41421L2.94975 8.46447C2.34163 9.07258 2 9.89736 2 10.7574C2 12.5482 3.45178 14 5.24264 14C6.10264 14 6.92742 13.6584 7.53553 13.0503Z" />
                                    <path d="M5.70711 11.7071L11.7071 5.70711L10.2929 4.29289L4.29289 10.2929L5.70711 11.7071Z" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Opciones de catálogo</h2>
                                <p class="mt-2 text-gray-600">Comparte tu catálogo con un enlace directo o genera un código QR para que tus clientes lo escaneen.</p>
                            </div>
                        </div>
                        <div class="mt-6 rounded-2xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700 break-words">{{ $catalogLink }}</div>
                        <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                            <button x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText('{{ $catalogLink }}').then(() => copied = true)" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-4 py-3 text-white shadow transition hover:bg-indigo-700 focus:outline-none">
                               
                                <span x-text="copied ? 'Copiado' : 'Copiar enlace'"></span>
                            </button>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-gray-200 bg-gray-50 p-6 flex items-center justify-center">
                        {!! QrCode::size(180)->generate($catalogLink) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

       
    </div>
    
{{-- <div class="bg-white dark:bg-gray-800 rounded-lg px-6 py-8 ring shadow-xl ring-gray-900/5">
  <div>
    <span class="inline-flex items-center justify-center rounded-md bg-indigo-500 p-2 shadow-lg">
      <svg class="h-6 w-6 stroke-white" ...>
        <!-- ... -->
      </svg>
    </span>
  </div>
  <h3 class="text-gray-900 dark:text-white mt-5 text-base font-medium tracking-tight ">Writes upside-down</h3>
  <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm ">
    The Zero Gravity Pen can be used to write in any orientation, including upside-down. It even works in outer space.
  </p>
</div> --}}
   

</div>