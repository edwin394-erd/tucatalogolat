@php
    $greetingName = auth()->user()->name ?? null;
    $role = auth()->user()->role;
@endphp

<div class="mx-auto w-full max-w-7xl space-y-6 px-3 py-4 sm:px-5 lg:px-6">

    {{-- ===================== SALUDO ===================== --}}
    {{-- <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
        <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">
          Hola, {{ $greetingName }}. Bienvenido a tu panel de control.
        </h1>
        <p class="text-sm text-gray-500">Este es el resumen de tu actividad.</p>
    </div> --}}

   

    {{-- ===================== RESUMEN DEL CATÁLOGO (solo usuarios) ===================== --}}
    @if($role == 'user' && $catalogLink)
        <section aria-labelledby="catalog-stats-title" class="space-y-3">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 id="catalog-stats-title" class="text-lg font-bold text-gray-900">Resumen del catálogo</h2>
                    <p class="text-sm text-gray-500">Actividad reciente de tu tienda.</p>
                </div>
                <a href="{{ route('orders') }}" wire:navigate class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    Ver todos los pedidos →
                </a>
            </div>

            {{-- Tarjetas de métricas: un color de ícono distinto por tipo de dato --}}
            <div class="grid grid-cols-2 gap-2.5 lg:grid-cols-4">
                <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-gray-100 sm:p-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M6 6h15l-1.68 9.39a2 2 0 0 1-1.99 1.61H8.31a2 2 0 0 1-1.99-1.61L4.57 4H2"/><circle cx="9" cy="20" r="1"/><circle cx="16" cy="20" r="1"/></svg>
                        </span>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pedidos</p>
                    </div>
                    <p class="mt-2 text-2xl font-black text-gray-900">{{ $n_pedidos }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $n_pedidos_ultimos_7_dias }} últimos 7 días</p>
                </div>

                <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-gray-100 sm:p-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        </span>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pendientes</p>
                    </div>
                    <p class="mt-2 text-2xl font-black text-amber-600">{{ $n_pedidos_pendientes }}</p>
                    <p class="mt-1 text-xs text-gray-500">Por atender</p>
                </div>

                <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-gray-100 sm:p-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </span>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total vendido</p>
                    </div>
                    <p class="mt-2 truncate text-2xl font-black text-emerald-600">${{ number_format($total_pedidos, 2) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Importe registrado</p>
                </div>

                <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-gray-100 sm:p-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-sky-50 text-sky-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Visitas</p>
                    </div>
                    <p class="mt-2 text-2xl font-black text-sky-600">{{ $n_visitas_ultimos_7_dias }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $n_visitas }} acumuladas</p>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 rounded-2xl bg-white p-3 shadow-sm ring-1 ring-gray-100 sm:p-4">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-violet-50 text-violet-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Plan {{ $plan_name ?? 'Sin plan' }}</p>
                        @if($plan_expires_at)
                            <p class="mt-1 text-xs text-gray-500">Vence el {{ $plan_expires_at->format('d/m/Y') }}</p>
                        @else
                            <p class="mt-1 text-xs text-gray-500">Sin fecha de vencimiento</p>
                        @endif
                    </div>
                </div>
                <p class="shrink-0 text-right text-lg font-black {{ $plan_is_expired ? 'text-red-600' : 'text-violet-600' }}">
                    @if($plan_days_remaining === null)
                        --
                    @elseif($plan_is_expired)
                        Vencido
                    @elseif($plan_days_remaining === 0)
                        <span class="text-sm">Menos de 1 día</span>
                    @else
                        {{ $plan_days_remaining }} <span class="text-xs font-semibold">días</span>
                    @endif
                </p>
            </div>

            {{-- Listas: pedidos recientes + visitas recientes --}}
            <div class="grid grid-cols-1 gap-3 lg:grid-cols-2">
                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="font-bold text-gray-900">Pedidos recientes</h3>
                        <a href="{{ route('orders') }}" wire:navigate class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Ver todos</a>
                    </div>
                    <div class="mt-2 divide-y divide-gray-100">
                        @forelse($pedidos_recientes as $pedido)
                            <div class="flex items-center justify-between gap-3 py-2">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-600">
                                        {{ strtoupper(substr($pedido->customer_name ?? '?', 0, 2)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-800">{{ $pedido->customer_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0 space-y-1">
                                    <p class="text-sm font-bold text-gray-800">${{ number_format($pedido->total, 2) }}</p>
                                    <span class="inline-block rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $pedido->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ ucfirst($pedido->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="py-8 text-center text-sm text-gray-500">Aún no hay pedidos.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="font-bold text-gray-900">Visitas recientes</h3>
                        <span class="text-xs text-gray-400">Últimas entradas</span>
                    </div>
                    <div class="mt-2 divide-y divide-gray-100">
                        @forelse($visitas_recientes as $visita)
                            <div class="flex items-center justify-between gap-3 py-2">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sky-50 text-sky-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-800">Visita al catálogo</p>
                                        <p class="text-xs text-gray-500">{{ $visita->visited_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400 shrink-0">{{ $visita->ip_address ?? 'Visitante' }}</span>
                            </div>
                        @empty
                            <p class="py-8 text-center text-sm text-gray-500">Aún no hay visitas registradas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    @endif
     {{-- ===================== MÉTRICAS PRINCIPALES ===================== --}}
    <section aria-labelledby="overview-title" class="space-y-3">
        <h2 id="overview-title" class="text-lg font-bold text-gray-900">Vista general</h2>

        @if($role == 'admin')
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                <x-dashboard-card
                    title="{{ __('messages.users') }}"
                    :value="$n_usuarios"
                    content="Usuarios registrados en la plataforma"
                    :link="route('usuarios')"
                    icon="users" />
                <x-dashboard-card
                    title="{{ __('messages.subscriptions') }}"
                    :value="$n_suscripciones_activas"
                    content="Suscripciones activas actualmente"
                    :link="route('subscripciones')"
                    icon="subscriptions" />
                <x-dashboard-card
                    title="{{ __('messages.plans') }}"
                    :value="$n_planes"
                    content="Planes disponibles para contratar"
                    :link="route('planes')"
                    icon="plans" />
            </div>
        @elseif($role == 'user')
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <x-dashboard-card
                    title="{{ __('messages.products') }}"
                    :value="$n_productos"
                    content="Productos publicados en tu catálogo"
                    :link="route('products')"
                    icon="products" />
                <x-dashboard-card
                    title="{{ __('messages.categories') }}"
                    :value="$n_categorias"
                    content="Categorías creadas para organizar tu catálogo"
                    :link="route('categories')"
                    icon="categories" />
            </div>
        @endif
    </section>

    {{-- ===================== COMPARTIR CATÁLOGO ===================== --}}
    @if($catalogLink)
        <section aria-labelledby="share-catalog-title" class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100 sm:p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div class="max-w-2xl">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                                <path d="M8.6 13.5l6.8 3.9M15.4 6.6L8.6 10.5"/>
                            </svg>
                        </span>
                        <div>
                            <h2 id="share-catalog-title" class="text-lg font-bold text-gray-900">Comparte tu catálogo</h2>
                            <p class="text-sm text-gray-500">Con un enlace directo o un código QR para que tus clientes lo escaneen.</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 shrink-0 text-gray-400">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                        </svg>
                        <span class="min-w-0 flex-1 truncate text-sm text-gray-700">{{ $catalogLink }}</span>
                    </div>

                    <div class="mt-4">
                        <button x-data="{ copied: false }"
                                x-on:click="navigator.clipboard.writeText('{{ $catalogLink }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            <span x-text="copied ? '¡Copiado!' : 'Copiar enlace'"></span>
                        </button>
                    </div>
                </div>

                <div class="flex shrink-0 items-center justify-center rounded-2xl border border-gray-200 bg-white p-3">
                    {!! QrCode::size(128)->generate($catalogLink) !!}
                </div>
            </div>
        </section>
    @endif

</div>