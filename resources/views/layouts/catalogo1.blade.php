<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <link rel="icon" type="image/png" href="{{ asset('imgs/icono.ico') }}" />
       <link rel="shortcut icon" href="{{ asset('imgs/icono.ico') }}" />
       <link rel="apple-touch-icon" href="{{ asset('imgs/icono.ico') }}" />
    <title>@yield('title', 'Catalogo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
</head>


<body class="bg-gray-100 ">
    {{-- scripts already included via @vite above --}}
    {{-- Banner and profile pic --}}
    
    <!-- Cart badge moved into the redes component so it sits above social icons in the bottom-right spot -->


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
