<div class="px-5 my-5">
    {{-- <h1 class="text-2xl font-bold text-gray-700 ">Tus Productos</h1> <br>--}}
    
    <div class="p-0">

        @livewire('table', [
            'model' => 'Subscription',
            'titulo' => 'Subscripciones',
            'columns' => ['user_id', 'plan_id', 'status', 'starts_at','expires_at'],
            'column_names' => ['Usuario', 'Plan', 'Estado', 'Fecha de Inicio', 'Fecha de Corte'],
            'filter_field' => null,
            'filter_value' => null,
            'searching_exceptions' => [],
            'table_type' => 'Subscripciones',
        ])

    </div>

</div>
