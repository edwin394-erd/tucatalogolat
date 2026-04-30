<div class="px-5 my-5">
    {{-- <h1 class="text-2xl font-bold text-gray-700 ">Tus Productos</h1> <br>--}}
    
    <div class="p-0">

        @livewire('table', [
            'model' => 'User',
            'titulo' => __('messages.users'),
            'columns' => ['name', 'email', 'role', 'telephone', 'catalogo_name','subscription', 'fecha_de_corte'],
            'column_names' => [__('messages.name'), __('messages.email'), __('messages.role'), __('messages.telephone'), __('messages.catalog'), __('messages.subscription'), __('messages.cut_off_date')],
            'searching_exceptions' => ['subscription', 'fecha_de_corte'],
            'filter_field' => null,
            'filter_value' => null,
            'table_type' => __('messages.users'),
        ])

    </div>

</div>