<section class="bg-gradient-to-br from-yellow-50 to-indigo-100 inset-shadow-sm md:px-10 h-screen  ">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
                <x-logo /><br>
            <div class="w-full bg-white rounded-lg shadow border border-gray-300 md:mt-0 sm:max-w-md xl:p-0">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                                    {{ __('messages.login_title') }}
                            </h1>
                            <form class="space-y-4 md:space-y-6" wire:submit.prevent="login" novalidate>
                                    <div>
                                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.your_email') }}</label>
                                            <input type="email" wire:model.blur="email" name="email" id="email" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5" placeholder="name@company.com" required="">
                                            @error('email')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    <div x-data="{ showPassword: false }">
                                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.password') }}</label>
                                        <div class="relative">
                                                <input x-bind:type="showPassword ? 'text' : 'password'" wire:model.blur="password" name="password" id="password" placeholder="••••••••" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 pr-10" required="">
                                                <button type="button" x-on:click="showPassword = !showPassword" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.249-2.383A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.043 5.197M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                                        </svg>
                                                </button>
                                        </div>
                                            @error('password')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                    </div>
                                    <div class="flex items-center justify-between">
                                            <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-100 inset-shadow-sm focus:ring-3 focus:ring-gray-300" required="">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="remember" class="text-gray-500">{{ __('messages.remember_me') }}</label>
                                                    </div>
                                            </div>
                                            <a href="#" class="text-sm font-medium text-gray-600 hover:underline">{{ __('messages.forgot_password') }}</a>
                                    </div>
                                    <button type="submit" class="w-full text-white bg-indigo-600  focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">{{ __('messages.login') }}</button>
                                    <p class="text-sm font-light text-gray-500">
                                            {{ __('messages.no_account') }} <a href="{{ route('register') }}" wire:navigate class="font-medium text-gray-600 hover:underline">{{ __('messages.register') }}</a>
                                    </p>
                            </form>
                    </div>
            </div>
    </div>
</section>