<div class="min-h-screen bg-gradient-to-br from-yellow-50 via-white to-indigo-100 text-gray-800 overflow-x-hidden">

	<main class="pt-10 relative">

		<!-- Decorative animated blobs (background only, no interaction) -->
		<div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 bg-indigo-300/30 rounded-full blur-3xl blob-anim"></div>
		<div class="pointer-events-none absolute top-40 -right-24 w-[28rem] h-[28rem] bg-purple-300/30 rounded-full blur-3xl blob-anim" style="animation-delay:-4s"></div>

		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

			<!-- Hero content -->
			<section class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center py-16">
				<div data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out">
					<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold tracking-wide">
						🚀 {{ __('messages.home_hero_badge') ?? 'Prueba gratis 14 días' }}
					</span>
					<h2 class="mt-4 text-4xl sm:text-5xl font-extrabold leading-tight bg-gradient-to-r from-indigo-700 via-purple-600 to-indigo-500 bg-clip-text text-transparent">
						{{ __('messages.home_hero_title') }}
					</h2>
					<p class="mt-4 text-lg text-gray-600 max-w-xl">{{ __('messages.home_hero_subtitle') }}</p>
					<div class="mt-6 flex flex-wrap gap-3">
						<a href="{{ route('login') }}"
						   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 hover:bg-indigo-700 transition-all duration-300">
							{{ __('messages.home_start_now') }}
						</a>
						<a href="#pricing"
						   class="inline-flex items-center px-6 py-3 border border-gray-200 bg-white/70 backdrop-blur rounded-xl text-gray-700 hover:border-indigo-300 hover:text-indigo-700 hover:-translate-y-0.5 transition-all duration-300">
							{{ __('messages.home_view_plans') }}
						</a>
					</div>
					<ul class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-600">
						<li class="flex items-start gap-2"><svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>{{ __('messages.home_feature_templates') }}</li>
						<li class="flex items-start gap-2"><svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>{{ __('messages.home_feature_management') }}</li>
						<li class="flex items-start gap-2"><svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>{{ __('messages.home_feature_share') }}</li>
						<li class="flex items-start gap-2"><svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>{{ __('messages.home_feature_stats') }}</li>
					</ul>
				</div>

				<div data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out delay-150">
					<!-- Imagen o mockup, con anillo decorativo y flotación suave -->
					<div class="relative">
						<div class="absolute inset-0 bg-gradient-to-tr from-indigo-400/30 to-purple-400/30 blur-2xl rounded-3xl"></div>
						<img src="{{ asset('imgs/plantilla1.png') }}" alt="Mockup"
						     class="relative rounded-2xl shadow-2xl w-full float-anim"/>
					</div>
				</div>
			</section>

			<!-- Features -->
			<section id="features" class="py-16">
				<h3 data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out text-2xl sm:text-3xl font-bold text-center">
					{{ __('messages.home_features_title') }}
				</h3>
				<div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

					<div data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
						<div class="flex items-start gap-3">
							<div class="flex-shrink-0 bg-indigo-50 p-2.5 rounded-xl text-indigo-600">
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

					<div data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out delay-100 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
						<div class="flex items-start gap-3">
							<div class="flex-shrink-0 bg-indigo-50 p-2.5 rounded-xl text-indigo-600">
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

					<div data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out delay-200 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
						<div class="flex items-start gap-3">
							<div class="flex-shrink-0 bg-indigo-50 p-2.5 rounded-xl text-indigo-600">
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

					<div data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out delay-300 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
						<div class="flex items-start gap-3">
							<div class="flex-shrink-0 bg-indigo-50 p-2.5 rounded-xl text-indigo-600">
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
			<section id="pricing" class="py-16">
				<h3 data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out text-2xl sm:text-3xl font-bold text-center">
					{{ __('messages.home_pricing_title') }}
				</h3>
				<p data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out text-center text-gray-600 mt-2">
					{{ __('messages.home_pricing_subtitle') }}
				</p>
				<div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
					@foreach ($plans as $plan)
						<div data-animate
						     style="transition-delay: {{ $loop->index * 100 }}ms"
						     class="opacity-0 translate-y-6 transition-all duration-700 ease-out relative bg-white p-6 rounded-2xl border {{ $loop->index === 1 ? 'border-indigo-600 shadow-xl ring-1 ring-indigo-600' : 'border-gray-100 shadow-sm' }} hover:shadow-xl hover:-translate-y-1 transition-all">
							@if ($loop->index === 1)
								<span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded-full shadow">
									{{ __('messages.home_most_popular') ?? 'Más popular' }}
								</span>
							@endif
							<h4 class="text-xl font-semibold">{{ $plan->name }}</h4>
							<p class="mt-2 text-gray-600">{{ $plan->description }}</p>
							<div class="mt-4 text-3xl font-bold">${{ number_format($plan->price) }}<span class="text-base font-medium text-gray-600">/mes</span></div>
							<ul class="mt-4 text-sm text-gray-600 space-y-2">
								@foreach (explode(';', $plan->features) as $feature)
									<li class="flex items-start gap-2">
										<svg class="h-4 w-4 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7.629 13.37 4.26 10l1.061-1.06 2.308 2.308 5.642-5.642L14.58 7.9z"/></svg>
										{{ $feature }}
									</li>
								@endforeach
							</ul>
							<a href="{{ route('login') }}"
							   class="mt-6 inline-block w-full text-center px-4 py-2.5 rounded-xl text-white transition-all duration-300 hover:-translate-y-0.5 {{ $loop->index === 1 ? 'bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30' : 'bg-gray-800 hover:bg-gray-900' }}">
								{{ __('messages.home_start_now') }}
							</a>
						</div>
					@endforeach
				</div>
			</section>

			<!-- CTA -->
			<section data-animate class="opacity-0 translate-y-6 transition-all duration-700 ease-out py-14 relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl my-12">
				<div class="pointer-events-none absolute -bottom-16 -left-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
				<div class="pointer-events-none absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
				<div class="max-w-4xl mx-auto text-center relative px-4">
					<h3 class="text-2xl sm:text-3xl font-bold text-white">{{ __('messages.home_cta_title') }}</h3>
					<p class="mt-2 text-indigo-100">{{ __('messages.home_cta_subtitle') }}</p>
					<a href="{{ route('login') }}"
					   class="mt-6 inline-block px-8 py-3 bg-white text-indigo-700 font-semibold rounded-xl shadow-lg hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300">
						{{ __('messages.home_start_now_cta') }}
					</a>
				</div>
			</section>

			<!-- Contact / Footer -->
			<footer id="contact" class="mt-4 border-t pt-8 pb-12">
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
							<a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">{{ __('messages.home_twitter') }}</a>
							<a href="#" class="text-gray-600 hover:text-indigo-600 transition-colors">{{ __('messages.home_facebook') }}</a>
						</div>
					</div>
				</div>
				<div class="mt-8 text-center text-xs text-gray-500">{{ __('messages.home_copyright', ['year' => date('Y')]) }}</div>
			</footer>
		</div>
	</main>
</div>

<style>
	@keyframes blobFloat {
		0%, 100% { transform: translate(0, 0) scale(1); }
		33% { transform: translate(20px, -30px) scale(1.05); }
		66% { transform: translate(-15px, 15px) scale(0.97); }
	}
	.blob-anim { animation: blobFloat 14s ease-in-out infinite; }

	@keyframes floatY {
		0%, 100% { transform: translateY(0px); }
		50% { transform: translateY(-10px); }
	}
	.float-anim { animation: floatY 5s ease-in-out infinite; }

	@media (prefers-reduced-motion: reduce) {
		.blob-anim, .float-anim { animation: none; }
		[data-animate] { transition: none !important; opacity: 1 !important; transform: none !important; }
	}
</style>

<script>
	// Scroll-reveal: fades/slides elements in once they enter the viewport.
	document.addEventListener('DOMContentLoaded', function () {
		const targets = document.querySelectorAll('[data-animate]');
		if (!('IntersectionObserver' in window)) {
			targets.forEach(el => el.classList.remove('opacity-0', 'translate-y-6'));
			return;
		}
		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.classList.remove('opacity-0', 'translate-y-6');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.15 });
		targets.forEach(el => observer.observe(el));
	});
</script>