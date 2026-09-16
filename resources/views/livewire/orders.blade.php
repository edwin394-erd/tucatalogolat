<div class="px-5 my-5">
    <div class="p-0">
        @livewire('table', [
            'model' => 'Order',
            'titulo' => 'Pedidos',
            'columns' => ['customer_name', 'customer_phone', 'total', 'status', 'created_at'],
            'column_names' => ['Cliente', 'Teléfono', 'Total', 'Estado', 'Fecha'],
            'filter_field' => 'catalogo_id',
            'filter_value' => auth()->user()->catalogo?->id,
            'searching_exceptions' => [],
            'table_type' => 'Pedidos',
            'status_filter' => '',
        ])
    </div>
</div>
