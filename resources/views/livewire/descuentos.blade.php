<div class="px-5 my-5">
    {{-- <h1 class="text-2xl font-bold text-gray-700">Tus Descuentos</h1> <br> --}}
   

    <div class="p-0">
        
        @livewire('table', [
            'model' => 'Descuento',
            'columns' => ['name', 'amount','type' , 'product_id', 'start_date', 'end_date'],
            'column_names' => [__('messages.name'), __('messages.amount'), __('messages.type'), __('messages.product'), __('messages.start_date'), __('messages.end_date')],
            'filter_field' => 'catalogo_id',
            'filter_value' => auth()->user()->catalogo->id,
            'table_type' => __('messages.discounts')
        ])
    </div>

</div>

    