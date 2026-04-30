<div class="flex flex-col">
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
    <a href="{{ route($route_name) }}" wire:navigate class="text-blue-500 hover:underline inline-block">
        &larr; {{ __('messages.back') }}
    </a>

<div class="p-4 md:p-5 bg-white md:m-5  rounded-lg md:w-1/2" >

    @if($model == 'Product')

        @livewire('product-form')
    @elseif($model == 'Category')
        @livewire('category-form')
    @elseif($model == 'Descuento')

        @livewire('descuento-form')
    @elseif($model == 'Subscription')
        @livewire('subscription-form')
    @elseif($model == 'Plan')
        @livewire('plan-form')
    @endif
</div>
    
</div>
