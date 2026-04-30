<div class="px-5 lg:my-5">
    {{-- <h1 class="text-2xl font-bold text-gray-700">Tus Categorías</h1><br> --}}
    

    <div class="p-0">
        
        @livewire('table', [
            'model' => 'Category',
            'titulo' => __('messages.your_categories'),
            'columns' => ['name', 'description'],
            'column_names' => [__('messages.name'), __('messages.description')],
            'filter_field' => 'catalogo_id',
            'filter_value' => auth()->user()->catalogo->id,
            'searching_exceptions' => [],
            'table_type' => __('messages.categories')

        ])
    </div>

</div>