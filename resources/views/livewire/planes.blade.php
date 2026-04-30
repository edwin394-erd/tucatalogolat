<div>
@if(auth()->user()->role === 'admin')
<div class="px-5 my-5">
    {{-- <h1 class="text-2xl font-bold text-gray-700 ">Tus Productos</h1> <br>--}}
    
    <div class="p-0">

        @livewire('table', [
            'model' => 'Plan',
            'titulo' => __('messages.plans'),
            'columns' => ['name', 'description', 'price', 'max_products', 'duration_in_days'],
            'column_names' => [__('messages.name'), __('messages.description'), __('messages.price'), __('messages.max_products'), __('messages.duration_days')],
            'filter_field' => null,
            'filter_value' => null,
            'searching_exceptions' => [],
            'table_type' => __('messages.plans'),
        ])

    </div>
</div>
    
@else
<div class="px-5 my-5">
    <h1 class="text-2xl font-bold text-gray-700 mb-5">{{ __('messages.plans') }}</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach(\App\Models\Plan::where('is_active', 1)->get() as $plan)
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <h2 class="text-xl font-semibold text-gray-800">{{ $plan->name }}</h2>
                <p class="text-gray-600 mt-2">{{ $plan->description }}</p>
                <p class="text-2xl font-bold text-indigo-600 mt-4">${{ $plan->price }}</p>
                <p class="text-sm text-gray-500">Máx productos: {{ $plan->max_products }}</p>
                <p class="text-sm text-gray-500">Duración: {{ $plan->duration_in_days }} días</p>
                @php
                    $whatsappMessage = "Solicitud de suscripción al plan {$plan->name}:\n\nUsuario: " . auth()->user()->name . " (" . auth()->user()->email . ")\nPlan: {$plan->name}\nPrecio: {$plan->price}\nDescripción: {$plan->description}";
                @endphp
                <a href="https://wa.me/584246054544?text={{ rawurlencode($whatsappMessage) }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex w-full justify-center bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700">
                    Solicitar Suscripción
                </a>
              
            </div>
        @endforeach
    </div>
</div>
@endif
</div>