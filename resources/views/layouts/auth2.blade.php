<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
       <link rel="icon" type="image/png" href="{{ asset('imgs/icono.ico') }}" />
       <link rel="shortcut icon" href="{{ asset('imgs/icono.ico') }}" />
       <link rel="apple-touch-icon" href="{{ asset('imgs/icono.ico') }}" />
    <title>TuCatalogo.Lat</title>
 @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>


<body class="bg-gradient-to-br from-yellow-50 to-indigo-100 inset-shadow-sm h-screen ">

  <x-alert alert_type="success" />

<button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
   <span class="sr-only">Abrir barra lateral</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

    <!-- Language switch -->
    <div class="hidden sm:block ml-4">
      <select id="language-select" class="bg-white/60 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 rounded-full px-3 py-1 text-sm text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
        <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>🇪🇸 Español</option>
        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇺🇸 English</option>
      </select>
    </div>


<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-white shadow shadow-xl tema1:bg-red-500 ">
      <div class="flex items-center pl-2.5 mb-5 rounded-xl w-fit px-2">
       

         <x-logo/>
      </div>
      <ul class="space-y-2 font-medium">
         
         @if(auth()->user()->subscriptions->last())
            @if(auth()->user()->subscriptions->last()->expires_at > now())
            
            <div class="bg-indigo-100 text-indigo-800 text-sm font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-indigo-200 dark:text-indigo-900 mb-4" role="alert">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                  <path  fill-rule="evenodd" d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z" clip-rule="evenodd" />
                  </svg>

               <span class="ml-2">Plan {{ auth()->user()->subscriptions->last()->plan->name }}</span>
            </div>
            @else
            <div class="bg-red-100 text-red-800 text-sm font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900 mb-4" role="alert">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                  </svg>

               <span class="ml-2">{{ __('messages.subscription_expired') }}</span>
            </div>
            @endif
         @endif
         <li class="  w-fit h-fit">
            <a href="{{ route('dashboard') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
               <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>



         <li class="  w-fit h-fit">
            <a href="{{ route('cuenta') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
               <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
               </svg>
               <span class="ms-3">{{ __('messages.account') }}</span>
            </a>
         </li>
          <li class="  w-fit h-fit">
            <a href="{{ route('planes') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
               <x-svg-plans/>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('messages.plans') }}</span>
            </a>
         </li>

         @if(auth()->user()->role == 'user')
         <li class="  w-fit h-fit">
            <a href="{{ route('products') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
              <x-svg-products/>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('messages.products') }}</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">{{ $productsCount }}</span>
            </a>
         </li>
        
         <li class="  w-fit h-fit">
            <a href="{{ route('categories') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
               <x-svg-categories/>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('messages.categories') }}</span>
            </a>
         </li>

         {{-- <li class="  w-fit h-fit">
            <a href="{{ route('descuentos') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
               <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                  <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Descuentos</span>
               <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full">Pro</span>
            </a>
         </li> --}}

         <li class="  w-fit h-fit">
            <a href="{{ route('configuracion') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
            <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd" />
            </svg>


               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('messages.personalize') }}</span>
            </a>
         </li>

         <li class="  w-fit h-fit">
            <a href="{{ route('catalogo', auth()->user()->catalogo->name) }}" wire:navigate.hover class="px-3 flex items-centtext-lg er p-2 text-gray-900 rounded-xl hover:bg-gray-100 group" wire:current='font-bold text-blue-500'>
             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
            <path d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 0 0 7.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 0 0 4.902-5.652l-1.3-1.299a1.875 1.875 0 0 0-1.325-.549H5.223Z" />
            <path fill-rule="evenodd" d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 0 0 9.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 0 0 2.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3Zm3-6a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75v-3Zm8.25-.75a.75.75 0 0 0-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-5.25a.75.75 0 0 0-.75-.75h-3Z" clip-rule="evenodd" />
            </svg>

               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('messages.catalog') }}</span>
            </a>
         </li>


         @elseif(auth()->user()->role == 'admin')
         <li class="  w-fit h-fit">
            <a href="{{ route('usuarios') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
               <x-svg-users/>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('messages.users') }}</span>
            </a>
         </li>
         <li class="  w-fit h-fit">
            <a href="{{ route('subscripciones') }}" wire:navigate.hover class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group" wire:current='font-bold text-lg text-blue-500'>
               <x-svg-subscriptions/>
               <span class="flex-1 ms-3 whitespace-nowrap">{{ __('messages.subscriptions') }}</span>
            </a>
         </li>
        

         @endif



         
        
         <li class="  w-fit h-fit">

            <button class="px-3 flex items-center p-2 text-gray-900 rounded-xl hover:bg-gray-200 group gap-3 w-full" data-modal-target="LogoutModal" data-modal-toggle="LogoutModal" type="button">
               <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
               </svg>
               <span class="ms-3">{{ __('messages.logout') }}</span>
            </button>
            
         </li>
      </ul>
   </div>
</aside>


<x-modal modalId="LogoutModal" modalTitle="{{ __('messages.logout') }}" modal translate="logout" class="group">

  <p class="text-lg text-gray-700 group-hover:text-gray-100">{{ __('messages.confirm_logout') }}</p>
  <div>
     <div class="flex justify-end gap-3 mt-6">
        <button type="button" class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 hover:text-white transition-colors" data-modal-hide="LogoutModal">
             {{ __('messages.cancel') }}
        </button>
        
        @livewire('logout')
        
     </div>
  </div>
 

</x-modal>

<div class="md:p-4 md:ml-64">

   
  @yield('content')


  
</div>
 


</body>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

<script>
(function() {
    // Elimina el listener anterior si existe
    window.removeEventListener('livewire:navigated', window._myNavigatedListener);

    // Nueva función robusta para inicializar Flowbite tras navegación SPA
    window._myNavigatedListener = function() {
        // Intenta inicializar Flowbite si existe el método (npm o import)
        if (window.Flowbite && typeof window.Flowbite.init === 'function') {
            window.Flowbite.init();
            console.log('Flowbite inicializado con window.Flowbite.init()');
        } else if (typeof initFlowbite === 'function') {
            // Para versiones antiguas o personalizadas
            initFlowbite();
            console.log('Flowbite inicializado con initFlowbite()');
        } else {
            // Para el CDN: fuerza el evento DOMContentLoaded
            document.dispatchEvent(new Event('DOMContentLoaded', {
                bubbles: true,
                cancelable: true,
            }));
            console.log('Flowbite inicializado forzando DOMContentLoaded');
        }
    };

    // Agrega el listener
    window.addEventListener('livewire:navigated', window._myNavigatedListener);
})();
</script>

<script>
   document.getElementById('theme-toggle').addEventListener('click', function() {
   document.documentElement.classList.toggle('dark');
   });

// language select handler
var langSelect = document.getElementById('language-select');
if(langSelect){
    langSelect.addEventListener('change', function() {
        var locale = this.value;
        window.location.href = "{{ url('lang') }}/" + locale;
    });
}

</script>

@livewireScripts

</html>