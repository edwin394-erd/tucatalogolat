<div class="mx-auto w-full max-w-5xl">
    @php
        if ($model == 'Descuento') {
            $route_name = 'descuentos';
        }elseif ($model == 'Category') {
            $route_name = 'categories';
        } elseif ($model == 'Product') {
            $route_name = 'products';
        } elseif( $model == 'Subscription'){
            $route_name = 'subscripciones';
        } elseif ($model == 'Plan') {
            $route_name = 'planes';
        }
    @endphp
<div class="relative w-full rounded-xl bg-white p-3 shadow-sm sm:p-5 lg:p-6">
    <a href="{{ route($route_name) }}" wire:navigate title="{{ __('messages.back') }}" aria-label="{{ __('messages.back') }}" class="absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-full text-lg text-blue-500 transition hover:bg-blue-50 hover:text-blue-700">
        <span aria-hidden="true">&larr;</span>
    </a>

    @if($model == 'Product')
        
        @livewire('product-form', ['ItemId' => $id])
    @elseif($model == 'Category')
        
        @livewire('category-form', ['ItemId' => $id])
    @elseif($model == 'Descuento')
        @livewire('descuento-form', ['ItemId' => $id])
    @elseif($model == 'Subscription')
        @livewire('subscription-form', ['ItemId' => $id])
    @elseif($model == 'Plan')
        @livewire('plan-form', ['ItemId' => $id])
    @endif
    
</div>

</div>
