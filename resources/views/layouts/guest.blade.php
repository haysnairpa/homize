<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $title ?? 'Homize')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Head Content -->
    @stack('head')
    @isset($head)
        {{ $head }}
    @endisset
</head>

@hasSection('body')
    @yield('body')
@else
    <body class="font-sans antialiased {{ $bodyClass ?? 'bg-gray-50' }}">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </body>
@endif
</html>
