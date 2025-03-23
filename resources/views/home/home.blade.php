<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homize - Home Services at Your Fingertips</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-homize-gray overflow-x-hidden relative min-h-screen min-w-screen">
    <!-- Navigation -->
    @include('components.navigation')

    <!-- Hero Section -->
    <div class="relative bg-homize-blue z-10 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="md:flex md:items-center md:justify-between">
                <div class="md:w-1/2 text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                        Solve your problem,<br>
                        From your <span class="text-homize-orange">home</span>
                    </h1>
                    <p class="mt-4 text-xl text-white opacity-90">
                        Professional home services at your fingertips. Book trusted service providers for all your household needs.
                    </p>
                    <div class="mt-8">
                        <a href="#services" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-homize-white bg-homize-orange hover:bg-amber-500 shadow-md">
                            Explore Services
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute -bottom-1 w-full h-16 bg-homize-white" style="clip-path: polygon(0 100%, 100% 100%, 100% 0);"></div>
    </div>

    <!-- Categories Section -->
    @include('components.category-browse')

    <!-- Layanan Section -->
    <div class="py-16 bg-homize-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Layanan Terpopuler</h2>
                <p class="text-gray-600">Temukan layanan terbaik untuk kebutuhan Anda</p>
            </div>

            <x-service-card :layanan="$layanan" />
        </div>
    </div>

    <!-- Featured Services Section -->
    {{-- <x-featured-services :featuredServices="$featuredServices" /> --}}

    <!-- Popular Services Section -->
    {{-- <x-popular-services :popularServices="$popularServices" /> --}}

    <!-- Footer -->
    @include('components.footer')

</body>
</html> 