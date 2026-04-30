<div class="relative overflow-x-auto bg-white p-4 shadow-md rounded-lg md:rounded-xl">

    <x-alert alert_type="success" />

    <div class="pb-4 bg-white  text-center  md:flex md:justify-between">



        @if ($titulo)
            <h2 class="text-2xl font-bold text-gray-700 mb-2">{{ $titulo }}</h2>
        @endif

        <label for="table-search" class="sr-only">Buscar</label>
        <div class="relative mt-1">
            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input wire:model.live="search" type="text" placeholder="{{ __('messages.search') }}"  id="table-search" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-100 focus:ring-gray-500 inset-shadow-sm focus:border-gray-500 w-full md:w-80" placeholder="Search for items">
        </div>
        <br>

    <div>

<a
    href="{{ route('create', ['model' => $model]) }}"
    wire:navigate
    class="bg-indigo-600 shadow shadow-xl  focus:ring-blue-300 text-white rounded-lg px-4 py-2">
    
    {{ __('messages.add') }}
</a>
</div>


    </div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr class="">
                @foreach ($column_names as $index => $column)
                    <th 
                        wire:click="sortBy('{{ $column }}')" 
                        class="px-6 py-3 {{ $index === 0 ? 'rounded-tl-xl' : '' }}"
                    >
                        {{ Str::title(Str::replace('_', ' ', $column)) }}
                        @if ($sortBy === $column)
                            <span>{!! $sortDirection === 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                        @endif
                    </th>
                @endforeach
                <th class="px-6 py-3 rounded-tr-xl text-right">{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        @forelse ($items as $index => $item)
            <tr class="bg-white border-b border-gray-200 hover:bg-gray-100">
                
                @foreach ($columns as $column)
                    <td class="px-6 py-4">
                        @if ($column === 'category_id')
                            {{ $item->category ? $item->category->name : '' }}
                        @elseif ($column === 'descuento_id')
                            {{ $item->descuento ? $item->descuento->name . ' (' . $item->descuento->amount . '%)' : 'Sin descuento' }}

                        @elseif ($column === 'foto')
                            @if($item->fotos->count() > 0)
                                <img src="{{ asset('storage/' . $item->fotos->first()->url) }}" alt="Foto" class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-gray-500">No image</span>
                            @endif
                        @elseif ($column === 'subscription')
                           {{ $item->subscriptions->isNotEmpty() ? $item->subscriptions->last()->plan->name : 'Sin suscripción' }}
                        @elseif ($column === 'user_id')
                            {{ $item->user ? $item->user->name : 'N/A' }}
                        @elseif ($column === 'plan_id')
                            {{ $item->plan ? $item->plan->name : 'N/A' }}
                         
                        @elseif ($column === 'fecha_de_corte')
                        <div class="rounded-lg p-1 text-center @if($item->subscriptions->isNotEmpty() && $item->subscriptions->last()->expires_at < now()) bg-red-100 text-red-700 @else bg-green-100 text-green-700 @endif">

                            {{ $item->subscriptions->isNotEmpty() ? $item->subscriptions->last()->expires_at->format('d/m/Y') : 'N/A' }}
                        </div>
                        @elseif ($column === 'status')
                            <div class="rounded-lg p-1 text-center @if($item->status == 'active') bg-green-100 text-green-700 @elseif($item->status == 'expired') bg-red-100 text-red-700 @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst($item->status) }}
                            </div>
                        @elseif ($column === 'catalogo_name')
            
                        <a href="{{ $item->catalogo ? route('catalogo', ['name' => $item->catalogo->name]) : '#' }}" class="text-blue-600 hover:underline" target="_blanck">

                            {{ $item->catalogo ? $item->catalogo->name : 'N/A' }}
                        </a>
                        @else
                            {{ $item->$column }}
                        @endif
                    </td>
                @endforeach

            <td class="px-6 py-4 flex gap-2 justify-end {{ $index === count($items) - 1 ? 'rounded-br-xl' : '' }}">
                    <a
                        href="{{ route('edit', ['model' => $model, 'id' => $item->id]) }}"
                        wire:navigate.hover
                        class="bg-white hover:bg-gray-100 focus:ring-green-300 text-yellow-700 border border-gray-300 shadow-sm rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>

                    </a>

                    <button wire:confirm="¿Estás seguro de que deseas eliminar este registro?" wire:click="delete({{ $item->id }})" class="bg-white border border-gray-300 hover:bg-gray-100 focus:ring-red-300 text-red-700 shadow-sm rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" wire:loading.remove wire:target="delete({{ $item->id }})" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>

                            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="delete({{ $item->id }})" class="animate-spin h-5 w-5 text-white inline-block" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>

                    </button>
                
                </td>
            </tr>

            @empty
                <tr>
                    <td colspan="{{ count($columns) + 1 }}" class="text-center py-4 rounded-b-xl">No hay resultados</td>
                </tr>
            @endforelse
           
            
        </tbody>
    </table>
    
    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
