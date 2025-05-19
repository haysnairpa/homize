<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Seller - Homize</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation untuk Merchant -->
        <nav x-data="{ open: false }" class="bg-homize-blue shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo dan Brand -->
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('merchant.dashboard') }}">
                                <img src="{{ asset('images/homizelogo.png') }}" alt="Homize Logo" class="h-8">
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('merchant.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('merchant.dashboard') ? 'border-homize-orange text-white' : 'border-transparent text-gray-300 hover:text-white' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                Dashboard
                            </a>
                            <a href="{{ route('merchant.services') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('merchant.services') ? 'border-homize-orange text-white' : 'border-transparent text-gray-300 hover:text-white' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                Layanan
                            </a>
                            <a href="{{ route('merchant.orders') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('merchant.orders') ? 'border-homize-orange text-white' : 'border-transparent text-gray-300 hover:text-white' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                Pesanan
                            </a>
                            <a href="{{ route('merchant.penarikan') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('merchant.penarikan') ? 'border-homize-orange text-white' : 'border-transparent text-gray-300 hover:text-white' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                Penarikan
                            </a>
                            <a href="{{ route('merchant.profile') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('merchant.profile') ? 'border-homize-orange text-white' : 'border-transparent text-gray-300 hover:text-white' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                Profil
                            </a>
                            <a href="{{ route('merchant.analytics') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('merchant.analytics') ? 'border-homize-orange text-white' : 'border-transparent text-gray-300 hover:text-white' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                Analitik
                            </a>
                        </div>
                    </div>

                    <!-- User Menu & Navigation -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Dashboard User Button -->
                        <a href="{{ route('dashboard') }}" class="mr-4 px-4 py-2 bg-white text-homize-blue rounded-md hover:bg-gray-100 transition-colors duration-200 text-sm font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard User
                        </a>
                        
                        <!-- User Menu -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="flex items-center text-white hover:text-homize-orange transition-colors">
                                    <span class="mr-2">{{ Auth::user()->nama }}</span>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" style="display: none;">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-homize-orange hover:bg-homize-blue-second focus:outline-none focus:bg-homize-blue-second transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('merchant.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('merchant.dashboard') ? 'border-homize-orange text-white bg-homize-blue-second' : 'border-transparent text-gray-300 hover:text-white hover:bg-homize-blue-second' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        Dashboard
                    </a>
                    <a href="{{ route('merchant.services') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('merchant.services') ? 'border-homize-orange text-white bg-homize-blue-second' : 'border-transparent text-gray-300 hover:text-white hover:bg-homize-blue-second' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        Layanan
                    </a>
                    <a href="{{ route('merchant.orders') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('merchant.orders') ? 'border-homize-orange text-white bg-homize-blue-second' : 'border-transparent text-gray-300 hover:text-white hover:bg-homize-blue-second' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        Pesanan
                    </a>
                    <a href="{{ route('merchant.penarikan') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('merchant.penarikan') ? 'border-homize-orange text-white bg-homize-blue-second' : 'border-transparent text-gray-300 hover:text-white hover:bg-homize-blue-second' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        Penarikan
                    </a>
                    <a href="{{ route('merchant.profile') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('merchant.profile') ? 'border-homize-orange text-white bg-homize-blue-second' : 'border-transparent text-gray-300 hover:text-white hover:bg-homize-blue-second' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        Profil
                    </a>
                    <a href="{{ route('merchant.analytics') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('merchant.analytics') ? 'border-homize-orange text-white bg-homize-blue-second' : 'border-transparent text-gray-300 hover:text-white hover:bg-homize-blue-second' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        Analitik
                    </a>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-homize-blue-second">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-base text-white">{{ Auth::user()->nama }}</div>
                            <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <!-- Dashboard User Button (Mobile) -->
                    <div class="mt-3 px-4">
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full px-4 py-2 bg-white text-homize-blue rounded-md hover:bg-gray-100 transition-colors duration-200 text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard User
                        </a>
                    </div>

                    <div class="mt-3 space-y-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-300 hover:text-white hover:bg-homize-blue-second text-base font-medium focus:outline-none transition duration-150 ease-in-out w-full text-left">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html> 