<div class="bg-white p-4 shadow-md sm:rounded-lg">

    <x-alert alert_type="success" />

    <!-- Header -->
    <div class="pb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">

        <!-- Search -->
        <div class="relative w-full md:w-80">
            <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input wire:model.live="search"
                type="text"
                placeholder="Buscar..."
                class="block w-full pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Add Button -->
        <a href="{{ route('create', ['model' => $model]) }}"
            wire:navigate
            class="bg-blue-600 hover:bg-blue-800 text-white rounded-lg px-4 py-2 text-center">
            Agregar
        </a>
    </div>

    <!-- Table Wrapper (scrollable on mobile) -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto min-w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    @foreach ($column_names as $index => $column)
                        <th wire:click="sortBy('{{ $column }}')"
                            class="px-4 py-3 cursor-pointer whitespace-normal break-words {{ $index === 0 ? 'rounded-tl-xl' : '' }}">
                            {{ Str::title(Str::replace('_', ' ', $column)) }}
                            @if ($sortBy === $column)
                                <span>{!! $sortDirection === 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                            @endif
                        </th>
                    @endforeach

                    <th class="px-4 py-3 rounded-tr-xl text-right whitespace-normal">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $index => $item)
                    <tr class="bg-white border-b hover:bg-gray-50">

                        @foreach ($columns as $column)
                            <td class="px-4 py-4 align-top whitespace-normal break-words max-w-[12rem]">
                                @if ($column === 'category_id')
                                    {{ $item->category->name ?? '' }}

                                @elseif ($column === 'descuento_id')
                                    {{ $item->descuento ? $item->descuento->name . ' (' . $item->descuento->amount . '%)' : 'Sin descuento' }}

                                @elseif ($column === 'foto')
                                    @if ($item->fotos->count() > 0)
                                        <img src="{{ asset('storage/' . $item->fotos->first()->url) }}"
                                            class="w-16 h-16 min-w-[4rem] min-h-[4rem] object-cover rounded">
                                    @else
                                        <span class="text-gray-500">No image</span>
                                    @endif

                                @else
                                    {{ $item->$column }}
                                @endif
                            </td>
                        @endforeach

                        <!-- Actions -->
                        <td class="px-4 py-4 flex flex-col sm:flex-row gap-2 justify-end whitespace-normal">

                            <!-- Edit -->
                            <a href="{{ route('edit', ['model' => $model, 'id' => $item->id]) }}"
                                wire:navigate.hover
                                class="bg-yellow-600 hover:bg-yellow-800 text-white rounded-lg px-3 py-2 w-full sm:w-auto text-center">
                                ✏️
                            </a>

                            <!-- Delete -->
                            <button wire:confirm="¿Estás seguro de que deseas eliminar este registro?"
                                wire:click="delete({{ $item->id }})"
                                class="bg-red-600 hover:bg-red-800 text-white rounded-lg px-3 py-2 w-full sm:w-auto">

                                <span wire:loading.remove wire:target="delete({{ $item->id }})">🗑️</span>

                                <svg wire:loading wire:target="delete({{ $item->id }})"
                                    class="animate-spin h-5 w-5 text-white inline-block"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 1 }}"
                            class="text-center py-4 rounded-b-xl">
                            No hay resultados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
