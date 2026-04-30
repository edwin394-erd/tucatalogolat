<div class="px-5 my-5">
    {{-- <h1 class="text-2xl font-bold text-gray-700 ">Tus Productos</h1> <br>--}}
    
    <div class="p-0">

        @livewire('table', [
            'model' => 'Product',
            'titulo' => __('messages.your_products'),
            'columns' => ['foto','name', 'description', 'category_id','price', 'descuento_id', 'stock'],
            'column_names' => [__('messages.photo'), __('messages.name'), __('messages.description'), __('messages.category'), __('messages.price'), __('messages.discount'), __('messages.stock')],
            'filter_field' => 'catalogo_id',
            'filter_value' => auth()->user()->catalogo->id,
            'searching_exceptions' => ['foto'],
            'table_type' => __('messages.products'),
        ])


       {{-- <x-table 
            :model="'Product'" 
            :columns="['foto','name', 'description', 'category_id','price', 'descuento_id', 'stock']" 
            :column_names="[__('messages.photo'), __('messages.name'), __('messages.description'), __('messages.category'), __('messages.price'), __('messages.discount'), __('messages.stock')]" 
            :filter_field="'catalogo_id'" 
            :filter_value="auth()->user()->catalogo->id" 
            :table_type="'Productos'"
            :route_name="'products'"
        /> --}}
    </div>

</div>