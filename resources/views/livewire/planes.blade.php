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
    <h1 class="text-2xl font-bold text-gray-700 mb-5">{{ __('messages.subscriptions') }}</h1>
    @if($currentSubscription)
        @php
            $remainingPlanDays = $currentSubscription->expires_at
                ? (int) ceil(now()->diffInDays($currentSubscription->expires_at))
                : null;
        @endphp
        <div class="mb-5 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-indigo-800">
            <p class="font-semibold">Plan actual: {{ $currentSubscription->plan->name }}</p>
            <p class="text-sm">
                @if($currentSubscription->expires_at)
                    Te quedan {{ $remainingPlanDays }} días de suscripción.
                @else
                    Tu suscripción no tiene fecha de vencimiento.
                @endif
            </p>
        </div>
    @endif
    
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
                @if($currentSubscription?->plan_id === $plan->id)
                    <span class="mt-4 inline-flex w-full cursor-not-allowed flex-col items-center justify-center rounded-lg bg-gray-200 px-4 py-2 text-gray-500" aria-label="Ya tienes este plan">
                        <span>Plan actual</span>
                        <span class="text-xs">
                            @if($currentSubscription->expires_at)
                                {{ $remainingPlanDays }} días restantes
                            @else
                                Sin vencimiento
                            @endif
                        </span>
                    </span>
                @else
                    <div x-data="{ open: false }" class="relative mt-4">
                        <button type="button" @click="open = !open" @click.outside="open = false" :aria-expanded="open.toString()" class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                            Solicitar Suscripción
                        </button>
                        <div x-show="open" x-transition class="absolute z-10 mt-2 w-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg" style="display: none;">
                            <a href="https://wa.me/584246054544?text={{ rawurlencode($whatsappMessage) }}" target="_blank" rel="noopener noreferrer" @click="open = false" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                Solicitar por WhatsApp
                            </a>
                            <a href="https://www.instagram.com/tucatalogolat.lat/" target="_blank" rel="noopener noreferrer" @click="open = false" class="block border-t border-gray-100 px-4 py-3 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700">
                                Solicitar por Instagram
                            </a>
                        </div>
                    </div>
                @endif
              
            </div>
        @endforeach
    </div>
</div>
@endif
</div>