<div>
@if(auth()->user()->role === 'admin')
<div class="px-5 my-5">
    {{-- <h1 class="text-2xl font-bold text-gray-700 ">Tus Productos</h1> <br>--}}
    
    <div class="p-0">

        @livewire('table', [
            'model' => 'Plan',
            'titulo' => __('messages.plans'),
            'columns' => ['name', 'description', 'features', 'price', 'max_products', 'duration_in_days'],
            'column_names' => [__('messages.name'), __('messages.description'), __('messages.features') ?? 'Características', __('messages.price'), __('messages.max_products'), __('messages.duration_days')],
            'filter_field' => null,
            'filter_value' => null,
            'searching_exceptions' => [],
            'table_type' => __('messages.plans'),
        ])

    </div>
</div>
    
@else
<div x-data="{ toast: { show: false, type: 'success', message: '' }, toastTimer: null }"
     @alert.window="toast = { show: true, type: $event.detail?.type || 'info', message: $event.detail?.message || '' }; clearTimeout(toastTimer); toastTimer = setTimeout(() => toast.show = false, 5000)">
    <div x-show="toast.show" x-cloak x-transition class="fixed right-5 top-5 z-[110] w-[calc(100%-2.5rem)] max-w-sm" role="alert">
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-white p-4 shadow-2xl shadow-slate-900/10">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <svg x-show="toast.type === 'success'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>
                <svg x-show="toast.type !== 'success'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M10.3 3.4 2.8 17a2 2 0 0 0 1.75 3h14.9a2 2 0 0 0 1.75-3L13.7 3.4a2 2 0 0 0-3.5 0Z"/></svg>
            </div>
            <p class="flex-1 text-sm font-semibold leading-5 text-slate-800" x-text="toast.message"></p>
            <button type="button" @click="toast.show = false" class="text-slate-400 hover:text-slate-700" aria-label="Cerrar alerta">&times;</button>
        </div>
    </div>

<div class="mx-auto max-w-7xl px-5 py-8 lg:px-8">
    <div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
        <div>
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">Tu cuenta</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('messages.subscriptions') }}</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Elige un plan, realiza el pago por Binance y envía tu comprobante. Revisaremos tu solicitud personalmente.</p>
        </div>
        <div class="hidden rounded-2xl border border-slate-200 bg-white px-4 py-3 text-right shadow-sm sm:block">
            <p class="text-xs font-medium text-slate-500">Pago seguro</p>
            <p class="mt-1 text-sm font-bold text-slate-800">Binance · Revisión manual</p>
        </div>
    </div>

    @if($currentSubscription)
        @php
            $remainingPlanDays = $currentSubscription->expires_at
                ? (int) ceil(now()->diffInDays($currentSubscription->expires_at))
                : null;
        @endphp
        <div class="mb-6 flex flex-col gap-3 rounded-2xl border border-indigo-200 bg-indigo-50 px-5 py-4 text-indigo-900 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Plan activo</p>
                <p class="mt-1 font-bold">{{ $currentSubscription->plan->name }}</p>
            </div>
            <p class="text-sm">
                @if($currentSubscription->expires_at)
                    Te quedan {{ $remainingPlanDays }} días de suscripción.
                @else
                    Tu suscripción no tiene fecha de vencimiento.
                @endif
            </p>
        </div>
    @endif

    @if($latestPaymentRequest && $latestPaymentRequest->payment_status === 'pending')
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-950">
            <div class="mt-0.5 rounded-full bg-amber-100 p-2 text-amber-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M10.3 3.4 2.8 17a2 2 0 0 0 1.75 3h14.9a2 2 0 0 0 1.75-3L13.7 3.4a2 2 0 0 0-3.5 0Z"/></svg></div>
            <div><p class="font-bold">Comprobante en revisión</p><p class="mt-1 text-sm text-amber-800">Tu solicitud para {{ $latestPaymentRequest->plan->name }} fue recibida. Te avisaremos cuando termine la revisión.</p></div>
        </div>
    @elseif($latestPaymentRequest && $latestPaymentRequest->payment_status === 'rejected')
        <div x-data="{ show: localStorage.getItem('subscription-rejected-{{ $latestPaymentRequest->id }}') !== 'dismissed' }" x-show="show" x-cloak class="mb-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-950">
            <div class="mt-0.5 rounded-full bg-rose-100 p-2 text-rose-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8 8 8 8M16 8l-8 8"/></svg></div>
            <div class="flex-1"><p class="font-bold">No pudimos validar el comprobante</p><p class="mt-1 text-sm text-rose-800">Puedes realizar el pago nuevamente y enviar un comprobante legible.</p></div>
            <button type="button" @click="show = false; localStorage.setItem('subscription-rejected-{{ $latestPaymentRequest->id }}', 'dismissed')" class="rounded-lg p-1 text-rose-500 hover:bg-rose-100 hover:text-rose-800" aria-label="Cerrar notificación">&times;</button>
        </div>
    @endif
    
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
        @foreach(\App\Models\Plan::where('is_active', 1)->get() as $plan)
            <div x-data="{ open: false }" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-950/10">
                <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>
                <div class="flex flex-1 flex-col p-6">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-xl font-bold text-slate-900">{{ $plan->name }}</h2>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-600">{{ $plan->duration_in_days }} días</span>
                    </div>
                    <p class="mt-3 min-h-12 text-sm leading-6 text-slate-500">{{ $plan->description }}</p>
                    <p class="mt-5 text-3xl font-black tracking-tight text-indigo-600">${{ number_format((float) $plan->price, 2) }}<span class="text-sm font-medium text-slate-400"> / plan</span></p>
                    <div class="mt-5 flex items-center gap-2 border-t border-slate-100 pt-4 text-sm text-slate-600"><svg class="h-5 w-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>Hasta {{ $plan->max_products }} productos</div>
                @if($currentSubscription?->plan_id === $plan->id)
                    <span class="mt-6 inline-flex w-full cursor-not-allowed flex-col items-center justify-center rounded-xl bg-slate-100 px-4 py-3 text-slate-500" aria-label="Ya tienes este plan">
                        <span class="font-semibold">Plan actual</span><span class="text-xs">
                            @if($currentSubscription->expires_at)
                                {{ $remainingPlanDays }} días restantes
                            @else
                                Sin vencimiento
                            @endif
                        </span>
                        </span></span>
                @else
                    <button type="button" @click="open = true" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-100"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>Elegir este plan</button>

                    <div x-show="open" x-cloak x-transition.opacity @keydown.escape.window="open = false" class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-sm sm:items-center" @click.self="open = false">
                        <div class="my-auto max-h-[calc(100vh-2rem)] w-full max-w-lg overflow-y-auto rounded-3xl bg-white shadow-2xl" @click.stop>
                            <div class="flex items-start justify-between border-b border-slate-100 p-6">
                                <div><p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Paso 1 de 2</p><h3 class="mt-1 text-xl font-bold text-slate-900">Paga y envía tu comprobante</h3><p class="mt-1 text-sm text-slate-500">{{ $plan->name }} · ${{ number_format((float) $plan->price, 2) }}</p></div>
                                <button type="button" @click="open = false" class="rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Cerrar modal">&times;</button>
                            </div>
                            <div class="space-y-5 p-6">
                                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-indigo-600 shadow-sm"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h.01M11 15h2m-9 5h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2-2Z"/></svg></div><div><p class="text-sm font-bold text-indigo-950">Pago con Binance Pay</p><p class="text-xs text-indigo-700">Escanea el QR o usa el Pay ID</p></div></div><div class="mt-4 flex justify-center rounded-xl bg-white p-3"><img src="{{ asset($binance['qr_image']) }}" alt="Código QR de Binance Pay" class="h-40 w-40 object-contain"></div><div class="mt-3 flex items-center gap-2 rounded-xl bg-white px-3 py-2"><span class="min-w-0 flex-1 truncate font-mono text-xs text-slate-700">{{ $binance['pay_id'] ?: 'Configura BINANCE_PAY_ID' }}</span><button type="button" x-data x-on:click="navigator.clipboard.writeText(@js($binance['pay_id']))" class="shrink-0 rounded-lg bg-indigo-100 px-2.5 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-200">Copiar</button></div><p class="mt-2 text-xs text-indigo-700">Confirma el monto y envía el comprobante después del pago.</p></div>
                                <form x-data="{ preview: null, fileName: '' }" wire:submit.prevent="subscribe({{ $plan->id }})" class="space-y-4" @alert.window="if ($event.detail?.type === 'success') open = false">
                                    <div><label for="proof-{{ $plan->id }}" class="mb-2 block text-sm font-bold text-slate-800">Comprobante de pago</label><label for="proof-{{ $plan->id }}" class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 px-4 py-6 text-center transition hover:border-indigo-400 hover:bg-indigo-50"><template x-if="preview"><img :src="preview" alt="Vista previa del comprobante" class="mb-3 max-h-36 max-w-full rounded-xl object-contain shadow-sm"></template><svg x-show="!preview" class="h-8 w-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4 4 4M4 16.5v1A2.5 2.5 0 0 0 6.5 20h11a2.5 2.5 0 0 0 2.5-2.5v-1"/></svg><span class="mt-2 text-sm font-semibold text-slate-700" x-text="fileName || 'Seleccionar imagen o PDF'"></span><span class="mt-1 text-xs text-slate-400">JPG, PNG o PDF · máximo 5 MB</span><input x-ref="proof" id="proof-{{ $plan->id }}" type="file" wire:model="proof" accept=".jpg,.jpeg,.png,.pdf" class="sr-only" @change="fileName = $event.target.files[0]?.name || ''; preview = $event.target.files[0]?.type?.startsWith('image/') ? URL.createObjectURL($event.target.files[0]) : null"></label><x-input-error for="proof" /></div>
                                    <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"><button type="button" @click="open = false" class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100">Cancelar</button><button type="submit" wire:loading.attr="disabled" wire:target="subscribe({{ $plan->id }})" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white hover:bg-indigo-700 disabled:cursor-wait disabled:opacity-60"><span wire:loading.remove wire:target="subscribe({{ $plan->id }})">Enviar comprobante</span><span wire:loading wire:target="subscribe({{ $plan->id }})">Enviando...</span></button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
@endif
</div>