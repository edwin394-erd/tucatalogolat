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
          :link="route('products')" />

        <x-dashboard-card 
          title="{{ __('messages.categories') }}"
          content="{{ __('messages.total_categories') }}: {{ $n_categorias }}"
          :link="route('categories')" />
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