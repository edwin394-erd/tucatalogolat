<div class="px-5 my-5">
    <div class="p-0">
        @livewire('table', [
            'model' => 'Product',
            'titulo' => __('messages.your_products'),
            'columns' => ['foto', 'name', 'description', 'category_id', 'price', 'precio_descuento'],
            'column_names' => [__('messages.photo'), __('messages.name'), __('messages.description'), __('messages.category'), __('messages.price'), __('messages.discount')],
            'filter_field' => 'catalogo_id',
            'filter_value' => auth()->user()->catalogo->id,
            'searching_exceptions' => ['foto'],
            'table_type' => __('messages.products'),
        ])
    </div>
</div>
