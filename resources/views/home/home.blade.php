<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homize - Solusi Layanan Rumah Tangga</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<div class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
    @include('components.navigation')
</div>

<body class="font-sans antialiased bg-homize-gray">
    @auth
        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 my-5 pt-24 md:pt-20 pb-16">
            <!-- Welcome Banner -->
            <div class="mb-6 mt-4 md:mt-8">
                <div class="bg-gradient-to-r from-homize-blue to-blue-600 rounded-xl overflow-hidden">
                    <div class="relative px-6 py-8 md:px-10 md:py-12">
                        <div class="max-w-lg relative z-10">
                            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                Selamat Datang Kembali, {{ Auth::user()->nama }}👋
                            </h1>
                            <p class="text-white/90 mb-4">
                                Temukan layanan terbaik untuk kebutuhan rumah Anda hari ini.
                            </p>
                            <a href="#layanan-section"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-homize-blue bg-white hover:bg-gray-100 shadow-md transition-colors">
                                Jelajahi Layanan
                            </a>
                        </div>
                        <div class="absolute right-0 bottom-0 opacity-20">
                            <svg class="w-48 h-48 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 14c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2z">
                                </path>
                                <path
                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Scrollable -->
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-4">Kategori Populer</h2>
                <div class="flex overflow-x-auto pb-2 gap-3 hide-scrollbar">
                    @foreach ($kategori as $index => $kat)
                        @if ($index < 6)
                            <a href="{{ route('jasa', $kat->id) }}"
                                class="flex-shrink-0 bg-white rounded-lg shadow-sm border border-gray-100 p-4 w-[140px] text-center hover:shadow-md transition-shadow">
                                <div
                                    class="w-12 h-12 bg-homize-blue/10 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-homize-blue" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $kat->nama }}</p>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Main Tabs -->
            <x-main-tabs  />
        </main>

        <!-- Bottom Navigation for Mobile -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t z-30">
            <div class="flex justify-around">
                <a href="{{ route('home') }}" class="flex-1 flex flex-col items-center py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-homize-blue" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-xs mt-1">Beranda</span>
                </a>
                <a href="{{ route('transactions') }}" class="flex-1 flex flex-col items-center py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs mt-1">Pesanan</span>
                </a>
                <a href="{{ route('dashboard') }}" class="flex-1 flex flex-col items-center py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-xs mt-1">Profil</span>
                </a>
            </div>
        </div>
    @else
        <!-- Homepage untuk user yang belum login -->
        <div class="relative bg-homize-blue z-10 pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="md:w-1/2 text-center md:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                            Solve your problem,<br>
                            From your <span class="text-homize-orange">home</span>
                        </h1>
                        <p class="mt-4 text-xl text-white opacity-90">
                            Professional home services at your fingertips. Book trusted service providers for all your
                            household needs.
                        </p>
                        <div class="mt-8">
                            <a href="#services"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-homize-white bg-homize-orange hover:bg-amber-500 shadow-md">
                                Explore Services
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-1 w-full h-16 bg-homize-white"
                style="clip-path: polygon(0 100%, 100% 100%, 100% 0);"></div>
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
    @endauth

    <!-- Footer -->
    @include('components.footer')

    @auth
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const applyFilter = document.getElementById('apply-filter');
                const layananGrid = document.getElementById('layanan-grid');

                // Variables to store filter values
                let selectedKategoriId = '';
                let selectedSortBy = 'newest';

                // Handle kategori selection
                document.querySelectorAll('[data-kategori-id]').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        selectedKategoriId = this.getAttribute('data-kategori-id');
                        this.closest('[x-data]').__x.$data.open = false;
                        updateLayanan();
                    });
                });

                // Handle sort selection
                document.querySelectorAll('[data-sort]').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        selectedSortBy = this.getAttribute('data-sort');
                        this.closest('[x-data]').__x.$data.open = false;
                        updateLayanan();
                    });
                });

                // Apply price filter
                if (applyFilter) {
                    applyFilter.addEventListener('click', function() {
                        updateLayanan();
                    });
                }

                function updateLayanan() {
                    const minPrice = document.getElementById('min-price').value;
                    const maxPrice = document.getElementById('max-price').value;

                    const data = {
                        kategori_id: selectedKategoriId,
                        sort_by: selectedSortBy,
                        min_price: minPrice,
                        max_price: maxPrice
                    };

                    fetch('{{ route('home.filter') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (layananGrid) {
                                layananGrid.innerHTML = data.html;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        </script>
    @endauth
</body>

</html>
