<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homize - Home Services at Your Fingertips</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-homize-gray">
    <!-- Navigation -->
    <nav class="bg-homize-blue shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Navigation Bar -->
            <div class="flex h-16 gap-x-5 items-center">
                <!-- Left Section -->
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-auto" src="{{ asset('images/homizelogo.png') }}" alt="Homize">
                    </div>
                    <button class="text-white hover:text-homize-orange px-3 py-2 text-sm font-medium">
                        Kategori
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-4">
                    <div class="relative">
                        <input type="text" class="w-full bg-white rounded-md py-2 pl-4 pr-10 text-sm" placeholder="Cari di Homize">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-white hover:fill-homize-orange" viewBox="0 0 24 24"><path d="M16 2H8a3.003 3.003 0 0 0-3 3v16.5a.5.5 0 0 0 .75.434l6.25-3.6l6.25 3.6A.5.5 0 0 0 19 21.5V5a3.003 3.003 0 0 0-3-3zm2 18.635l-5.75-3.312a.51.51 0 0 0-.5 0L6 20.635V5a2.003 2.003 0 0 1 2-2h8a2.003 2.003 0 0 1 2 2v15.635z"/></svg>
                </div>

                <div class="h-[55%] w-[1px] bg-homize-white"></div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <a href="/login" class="text-white hover:text-homize-orange px-3 py-2 text-sm font-medium">Masuk</a>
                    <a href="/register" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-homize-blue bg-homize-white hover:bg-gray-100">
                        Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation Links -->
        <div class="border-t border-homize-blue-second">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-8 py-2 text-sm">
                    <a href="#" class="text-white hover:text-homize-orange">Samsung Note 10</a>
                    <a href="#" class="text-white hover:text-homize-orange">Charger Mobil</a>
                    <a href="#" class="text-white hover:text-homize-orange">Samsung A73</a>
                    <a href="#" class="text-white hover:text-homize-orange">Pull Up Bar</a>
                    <a href="#" class="text-white hover:text-homize-orange">Hdd 1tb</a>
                    <a href="#" class="text-white hover:text-homize-orange">Xbox Series X</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-homize-blue">
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
    <div class="py-16 bg-homize-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Browse by Category
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Find the perfect service for your needs
                </p>
            </div>

            <div class="mt-12 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <!-- Laundry Category -->
                <div class="group">
                    <div class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 overflow-hidden">
                        <div class="flex items-center justify-center h-full bg-homize-blue bg-opacity-10 group-hover:bg-opacity-20 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <h3 class="text-sm font-medium text-gray-900">Laundry</h3>
                        <p class="text-xs text-gray-500">24 services</p>
                    </div>
                </div>

                <!-- Sewing Category -->
                <div class="group">
                    <div class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 overflow-hidden">
                        <div class="flex items-center justify-center h-full bg-homize-blue bg-opacity-10 group-hover:bg-opacity-20 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <h3 class="text-sm font-medium text-gray-900">Sewing</h3>
                        <p class="text-xs text-gray-500">18 services</p>
                    </div>
                </div>

                <!-- Massage Category -->
                <div class="group">
                    <div class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 overflow-hidden">
                        <div class="flex items-center justify-center h-full bg-homize-blue bg-opacity-10 group-hover:bg-opacity-20 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <h3 class="text-sm font-medium text-gray-900">Massage</h3>
                        <p class="text-xs text-gray-500">15 services</p>
                    </div>
                </div>

                <!-- Tutoring Category -->
                <div class="group">
                    <div class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 overflow-hidden">
                        <div class="flex items-center justify-center h-full bg-homize-blue bg-opacity-10 group-hover:bg-opacity-20 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <h3 class="text-sm font-medium text-gray-900">Tutoring</h3>
                        <p class="text-xs text-gray-500">32 services</p>
                    </div>
                </div>

                <!-- Cleaning Category -->
                <div class="group">
                    <div class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 overflow-hidden">
                        <div class="flex items-center justify-center h-full bg-homize-blue bg-opacity-10 group-hover:bg-opacity-20 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <h3 class="text-sm font-medium text-gray-900">Cleaning</h3>
                        <p class="text-xs text-gray-500">27 services</p>
                    </div>
                </div>

                <!-- Repair Category -->
                <div class="group">
                    <div class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 overflow-hidden">
                        <div class="flex items-center justify-center h-full bg-homize-blue bg-opacity-10 group-hover:bg-opacity-20 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <h3 class="text-sm font-medium text-gray-900">Repair</h3>
                        <p class="text-xs text-gray-500">21 services</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Services Section -->
    <div id="services" class="py-16 bg-homize-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Featured Services
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Discover our most popular services
                </p>
            </div>

            <div class="mt-12 grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Service Card 1 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ asset('images/service-laundry.jpg') }}" alt="Premium Laundry Service" class="w-full h-48 object-cover">
                        <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                            Laundry
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900">Premium Laundry Service</h3>
                        <div class="flex items-center mt-2">
                            <img src="{{ asset('images/avatar-1.jpg') }}" alt="Seller" class="w-8 h-8 rounded-full">
                            <span class="ml-2 text-sm text-gray-600">Clean Express</span>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex items-center text-homize-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-sm">4.9 (128)</span>
                            </div>
                            <span class="text-homize-blue font-semibold">Rp 75.000/kg</span>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-homize-blue hover:bg-homize-blue-second">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Service Card 2 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ asset('images/service-sewing.jpg') }}" alt="Custom Tailoring" class="w-full h-48 object-cover">
                        <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                            Sewing
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900">Custom Tailoring</h3>
                        <div class="flex items-center mt-2">
                            <img src="{{ asset('images/avatar-2.jpg') }}" alt="Seller" class="w-8 h-8 rounded-full">
                            <span class="ml-2 text-sm text-gray-600">Fashion Fix</span>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex items-center text-homize-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-sm">4.8 (95)</span>
                            </div>
                            <span class="text-homize-blue font-semibold">Rp 150.000/item</span>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-homize-blue hover:bg-homize-blue-second">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Service Card 3 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ asset('images/service-massage.jpg') }}" alt="Relaxing Massage" class="w-full h-48 object-cover">
                        <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                            Massage
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900">Relaxing Massage</h3>
                        <div class="flex items-center mt-2">
                            <img src="{{ asset('images/avatar-3.jpg') }}" alt="Seller" class="w-8 h-8 rounded-full">
                            <span class="ml-2 text-sm text-gray-600">Wellness Spa</span>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex items-center text-homize-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-sm">4.9 (210)</span>
                            </div>
                            <span class="text-homize-blue font-semibold">Rp 250.000/hour</span>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-homize-blue hover:bg-homize-blue-second">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second shadow-md">
                    View All Services
                </a>
            </div>
        </div>
    </div>

    <!-- Popular Services Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Popular Services
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Services loved by our customers
                </p>
            </div>

            <div class="mt-12 grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Service Card 1 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ asset('images/service-tutoring.jpg') }}" alt="Math Tutoring" class="w-full h-40 object-cover">
                        <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                            Tutoring
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">Math Tutoring</h3>
                        <div class="flex items-center mt-2">
                            <img src="{{ asset('images/avatar-4.jpg') }}" alt="Seller" class="w-6 h-6 rounded-full">
                            <span class="ml-2 text-xs text-gray-600">Smart Academy</span>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <div class="flex items-center text-homize-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-xs">4.7 (85)</span>
                            </div>
                            <span class="text-homize-blue font-semibold text-sm">Rp 200.000/hour</span>
                        </div>
                    </div>
                </div>

                <!-- Service Card 2 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ asset('images/service-cleaning.jpg') }}" alt="Home Cleaning" class="w-full h-40 object-cover">
                        <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                            Cleaning
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">Home Cleaning</h3>
                        <div class="flex items-center mt-2">
                            <img src="{{ asset('images/avatar-5.jpg') }}" alt="Seller" class="w-6 h-6 rounded-full">
                            <span class="ml-2 text-xs text-gray-600">CleanHome Pro</span>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <div class="flex items-center text-homize-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-xs">4.8 (120)</span>
                            </div>
                            <span class="text-homize-blue font-semibold text-sm">Rp 300.000/visit</span>
                        </div>
                    </div>
                </div>

                <!-- Service Card 3 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ asset('images/service-repair.jpg') }}" alt="Appliance Repair" class="w-full h-40 object-cover">
                        <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                            Repair
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">Appliance Repair</h3>
                        <div class="flex items-center mt-2">
                            <img src="{{ asset('images/avatar-6.jpg') }}" alt="Seller" class="w-6 h-6 rounded-full">
                            <span class="ml-2 text-xs text-gray-600">FixIt Solutions</span>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <div class="flex items-center text-homize-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-xs">4.6 (92)</span>
                            </div>
                            <span class="text-homize-blue font-semibold text-sm">Rp 150.000/hour</span>
                        </div>
                    </div>
                </div>

                <!-- Service Card 4 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ asset('images/service-plumbing.jpg') }}" alt="Plumbing Service" class="w-full h-40 object-cover">
                        <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                            Plumbing
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">Plumbing Service</h3>
                        <div class="flex items-center mt-2">
                            <img src="{{ asset('images/avatar-7.jpg') }}" alt="Seller" class="w-6 h-6 rounded-full">
                            <span class="ml-2 text-xs text-gray-600">WaterWorks</span>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <div class="flex items-center text-homize-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-xs">4.7 (78)</span>
                            </div>
                            <span class="text-homize-blue font-semibold text-sm">Rp 175.000/hour</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second shadow-md">
                    Explore More
                </a>
            </div>
        </div>
    </div>

    <!-- Top Rated Providers Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Top Rated Service Providers
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Meet our highest-rated professionals
                </p>
            </div>

            <div class="mt-12 grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Provider Card 1 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <img class="h-16 w-16 rounded-full" src="{{ asset('images/avatar-1.jpg') }}" alt="Provider">
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900">Clean Express</h3>
                                <div class="flex text-homize-orange">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-1 text-sm">(128)</span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-sm text-gray-600 bg-homize-blue bg-opacity-10 px-2 py-1 rounded-full">Laundry</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600">
                            "Premium laundry service with pickup and delivery. We take care of your clothes with premium detergents."
                        </p>
                        <div class="mt-4">
                            <a href="#" class="text-homize-blue hover:text-homize-blue-second font-medium">View Services &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Provider Card 2 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <img class="h-16 w-16 rounded-full" src="{{ asset('images/avatar-3.jpg') }}" alt="Provider">
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900">Wellness Spa</h3>
                                <div class="flex text-homize-orange">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-1 text-sm">(210)</span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-sm text-gray-600 bg-homize-blue bg-opacity-10 px-2 py-1 rounded-full">Massage</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600">
                            "Professional massage therapists bring relaxation to your home. Various techniques available for your comfort."
                        </p>
                        <div class="mt-4">
                            <a href="#" class="text-homize-blue hover:text-homize-blue-second font-medium">View Services &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Provider Card 3 -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <img class="h-16 w-16 rounded-full" src="{{ asset('images/avatar-4.jpg') }}" alt="Provider">
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900">Smart Academy</h3>
                                <div class="flex text-homize-orange">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-1 text-sm">(85)</span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-sm text-gray-600 bg-homize-blue bg-opacity-10 px-2 py-1 rounded-full">Tutoring</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600">
                            "Qualified tutors for all subjects and grade levels. Personalized learning plans for your educational needs."
                        </p>
                        <div class="mt-4">
                            <a href="#" class="text-homize-blue hover:text-homize-blue-second font-medium">View Services &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-homize-blue">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Ready to get started?</span>
                <span class="block text-homize-orange">Book a service today.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-homize-blue bg-homize-white hover:bg-gray-100">
                        Get Started
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-homize-orange hover:bg-amber-500">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center">
                        <img class="h-12 w-auto" src="{{ asset('images/homizelogo.png') }}" alt="Homize">
                    </div>
                    <p class="mt-4 text-base text-gray-300">
                        Making home services accessible to everyone.
                    </p>
                    <div class="mt-6 flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="col-span-1">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Categories</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Laundry</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Sewing</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Massage</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Tutoring</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Cleaning</a>
                        </li>
                    </ul>
                </div>

                <div class="col-span-1">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Company</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">About</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Careers</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Blog</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Partners</a>
                        </li>
                    </ul>
                </div>

                <div class="col-span-1">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Support</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Help Center</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Contact Us</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">Terms of Service</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 border-t border-gray-700 pt-8">
                <p class="text-base text-gray-400 text-center">
                    &copy; {{ date('Y') }} Homize. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
</html>