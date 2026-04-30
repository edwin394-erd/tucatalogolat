<div>
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
    <a href="{{ route($route_name) }}" wire:navigate class="text-blue-500 hover:underline mb-4 inline-block">
        &larr; {{ __('messages.back') }}
    </a>

<div class="p-4 md:p-5 bg-white md:m-10 rounded-lg md:w-1/2" >

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
