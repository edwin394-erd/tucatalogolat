<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Catalogo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>


<body class="bg-gray-100 ">
    @vite('resources/js/app.js')
    {{-- Banner and profile pic --}}
    
    <!-- Fixed cart button (global) Livewire component -->
    <div class="fixed top-4 right-4 z-50">
        @livewire('cart-badge')
    </div>


@yield('content')

        
    

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
 <script>
    // Elimina el listener anterior si existe
    window.removeEventListener('livewire:navigated', window._myNavigatedListener);
    // Define un nuevo listener y guárdalo en una propiedad global
    window._myNavigatedListener = function() {
        // Aquí puedes colocar cualquier código que necesites ejecutar después de la navegación
        console.log('Navegación completada');
    };
    window.addEventListener('livewire:navigated', window._myNavigatedListener); 
    </script>
    @livewireScripts
</body>
</html>
