<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index,follow">
    <meta name="description" content="@yield('description', 'Descubre productos, servicios y tiendas en TuCatalogolat.Lat.')">
    <meta name="keywords" content="@yield('keywords', 'catalogo, tienda, productos, ecommerce, negocios, tucatalogo')">
    <meta name="author" content="TuCatalogo.Lat">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TuCatalogo.Lat">
    <meta property="og:title" content="@yield('og_title', 'Catalogo')">
    <meta property="og:description" content="@yield('description', 'Descubre productos, servicios y tiendas en TuCatalogolat.Lat.')">
    <meta property="og:image" content="@yield('og_image', asset('imgs/icono.ico'))">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Catalogo')">
    <meta name="twitter:description" content="@yield('description', 'Descubre productos, servicios y tiendas en TuCatalogo.Lat.')">
    <meta name="twitter:image" content="@yield('og_image', asset('imgs/icono.ico'))">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    @php
        $catalogoFavicon = isset($catalogo) && $catalogo?->logo_url
            ? asset('storage/' . $catalogo->logo_url)
            : asset('imgs/icono.ico');
        $catalogoFaviconVersion = isset($catalogo) && $catalogo?->updated_at
            ? '?v=' . $catalogo->updated_at->timestamp
            : '';
    @endphp
    <link rel="icon" href="{{ $catalogoFavicon . $catalogoFaviconVersion }}" />
    <link rel="shortcut icon" href="{{ $catalogoFavicon . $catalogoFaviconVersion }}" />
    <link rel="apple-touch-icon" href="{{ $catalogoFavicon . $catalogoFaviconVersion }}" />
    <title>@yield('title', 'Catalogo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>


<body class="bg-gray-100 ">
    <x-alert alert_type="success" />
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
