<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-indigo-100 inset-shadow-sm text-gray-800">
	

	<main class="pt-10">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<!-- Hero content -->
			<section class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center py-12">
				<div>
					<h2 class="text-4xl font-extrabold leading-tight">{{ __('messages.home_hero_title') }}</h2>
					<p class="mt-4 text-lg text-gray-600">{{ __('messages.home_hero_subtitle') }}</p>
					<div class="mt-6 flex flex-wrap gap-3">
						<a href="{{ route('register') }}" class="inline-flex items-center px-5 py-3 bg-indigo-600 text-white rounded-md shadow">{{ __('messages.home_start_now') }}</a>
						<a href="#pricing" class="inline-flex items-center px-5 py-3 border border-gray-200 rounded-md text-gray-700">{{ __('messages.home_view_plans') }}</a>
					</div>
					<ul class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-600">
						<li class="flex items-start gap-2"><svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>{{ __('messages.home_feature_templates') }}</li>
						<li class="flex items-start gap-2"><svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>{{ __('messages.home_feature_management') }}</li>
						<li class="flex items-start gap-2"><svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>{{ __('messages.home_feature_share') }}</li>
						<li class="flex items-start gap-2"><svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>{{ __('messages.home_feature_stats') }}</li>
					</ul>
				</div>
				<div>
					<!-- Imagen o mockup -->
					<img src="{{ asset('imgs/plantilla1.png') }}" alt="Mockup" class="rounded-lg shadow-lg w-full w-80"/>
				</div>
			</section>

            <!-- Features -->
            <section id="features" class="py-12">
                <h3 class="text-2xl font-bold text-center">{{ __('messages.home_features_title') }}</h3>
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 bg-indigo-50 p-2 rounded-md text-indigo-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">{{ __('messages.home_easy_learning') }}</h4>
                                <p class="mt-2 text-sm text-gray-600">{{ __('messages.home_easy_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 bg-indigo-50 p-2 rounded-md text-indigo-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 20l8-8 4 4 8-8"></path>
                                    <path d="M14 6l4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">{{ __('messages.home_customized') }}</h4>
                                <p class="mt-2 text-sm text-gray-600">{{ __('messages.home_customized_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 bg-indigo-50 p-2 rounded-md text-indigo-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M10 2v4"></path>
                                    <path d="M14 2v4"></path>
                                    <path d="M4 11v5a3 3 0 003 3h0a3 3 0 003-3v-1"></path>
                                    <path d="M20 11v5a3 3 0 01-3 3h0a3 3 0 01-3-3v-1"></path>
                                    <path d="M12 11v6"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">{{ __('messages.home_integrations') }}</h4>
                                <p class="mt-2 text-sm text-gray-600">{{ __('messages.home_integrations_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 bg-indigo-50 p-2 rounded-md text-indigo-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 15v-1a8 8 0 0116 0v1"></path>
                                    <path d="M12 19v.01"></path>
                                    <path d="M8 15v3a1 1 0 001 1h6a1 1 0 001-1v-3"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">{{ __('messages.home_support') }}</h4>
                                <p class="mt-2 text-sm text-gray-600">{{ __('messages.home_support_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

			<!-- Pricing -->
			{{-- <section id="pricing" class="py-12">
				<h3 class="text-2xl font-bold text-center">Planes de suscripción</h3>
				<p class="text-center text-gray-600 mt-2">Planes pensados para pymes. Cambia o cancela en cualquier momento.</p>
				<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
					<div class="bg-white p-6 rounded-lg shadow">
						<h4 class="text-xl font-semibold">Básico</h4>
						<p class="mt-2 text-gray-600">Ideal para comenzar</p>
						<div class="mt-4 text-3xl font-bold">$9<span class="text-base font-medium text-gray-600">/mes</span></div>
						<ul class="mt-4 text-sm text-gray-600 space-y-2">
							<li>1 catálogo</li>
							<li>Plantillas limitadas</li>
							<li>Soporte por email</li>
						</ul>
						<a href="{{ route('register') }}" class="mt-6 inline-block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded">Comenzar</a>
					</div>

					<div class="bg-indigo-50 p-6 rounded-lg shadow-lg border-2 border-indigo-600">
						<h4 class="text-xl font-semibold">Profesional</h4>
						<p class="mt-2 text-gray-600">Para negocios en crecimiento</p>
						<div class="mt-4 text-3xl font-bold">$29<span class="text-base font-medium text-gray-600">/mes</span></div>
						<ul class="mt-4 text-sm text-gray-600 space-y-2">
							<li>Hasta 5 catálogos</li>
							<li>Plantillas premium</li>
							<li>Estadísticas completas</li>
						</ul>
						<a href="{{ route('register') }}" class="mt-6 inline-block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded">Comenzar</a>
					</div>
				</div>
			</section> --}}

			<section id="pricing" class="py-12">
				<h3 class="text-2xl font-bold text-center">{{ __('messages.home_pricing_title') }}</h3>
				<p class="text-center text-gray-600 mt-2">{{ __('messages.home_pricing_subtitle') }}</p>
				<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
					@foreach ($plans as $plan)
						<div class="bg-white p-6 rounded-lg shadow">
							<h4 class="text-xl font-semibold">{{ $plan->name }}</h4>
							<p class="mt-2 text-gray-600">{{ $plan->description }}</p>
							<div class="mt-4 text-3xl font-bold">${{ number_format($plan->price) }}<span class="text-base font-medium text-gray-600">/mes</span></div>
							<ul class="mt-4 text-sm text-gray-600 space-y-2">
								@foreach (explode(';', $plan->features) as $feature)
									<li>{{ $feature }}</li>
								@endforeach
							</ul>
							
							
							<a href="{{ route('register') }}" class="mt-6 inline-block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded">{{ __('messages.home_start_now') }}</a>
						</div>
						
					@endforeach
				</div>
			</section>

			<!-- CTA -->
			<section class="py-12 bg-gradient-to-r from-indigo-50 to-white rounded-lg">
				<div class="max-w-4xl mx-auto text-center">
					<h3 class="text-2xl font-bold">{{ __('messages.home_cta_title') }}</h3>
					<p class="mt-2 text-gray-600">{{ __('messages.home_cta_subtitle') }}</p>
					<a href="{{ route('register') }}" class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white rounded-md">{{ __('messages.home_start_now_cta') }}</a>
				</div>
			</section>

			<!-- Contact / Footer -->
			<footer id="contact" class="mt-12 border-t pt-8 pb-12">
				<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
					<div>
						<h4 class="font-bold">{{ __('messages.home_brand') }}</h4>
						<p class="mt-2 text-sm text-gray-600">{{ __('messages.home_brand_desc') }}</p>
					</div>
					<div>
						<h5 class="font-semibold">{{ __('messages.home_contact') }}</h5>
						<p class="mt-2 text-sm text-gray-600">{{ __('messages.home_email') }}</p>
						<p class="mt-1 text-sm text-gray-600">{{ __('messages.home_phone') }}</p>
					</div>
					<div>
						<h5 class="font-semibold">{{ __('messages.home_follow_us') }}</h5>
						<div class="mt-2 flex space-x-3">
							<a href="#" class="text-gray-600 hover:text-gray-800">{{ __('messages.home_twitter') }}</a>
							<a href="#" class="text-gray-600 hover:text-gray-800">{{ __('messages.home_facebook') }}</a>
						</div>
					</div>
				</div>
				<div class="mt-8 text-center text-xs text-gray-500">{{ __('messages.home_copyright', ['year' => date('Y')]) }}</div>
			</footer>
		</div>
	</main>
</div>
