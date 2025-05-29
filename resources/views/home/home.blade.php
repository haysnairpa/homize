<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homize - Solusi Layanan Rumah Tangga</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        
        /* Skeleton loading animation */
        .skeleton-pulse {
            animation: skeleton-pulse 0.5s infinite ease-in-out;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
        }
        
        @keyframes skeleton-pulse {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }
        
        /* Lazy loading image transitions */
        .lazy-image {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .lazy-image.opacity-100 {
            opacity: 1;
        }
    </style>
</head>
<div class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
    @include('components.navigation')
</div>

<body class="font-sans antialiased bg-homize-gray" x-data="{ dataLoaded: false }" x-init="setTimeout(() => { dataLoaded = true }, 1000)">
    @auth
        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 my-5 pt-24 md:pt-20 pb-16">
            <!-- Welcome Banner -->
            <div class="mb-6 mt-4 md:mt-8">
                <!-- Skeleton for Welcome Banner -->
                <template x-if="!dataLoaded">
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm">
                        <div class="relative px-6 py-8 md:px-10 md:py-12">
                            <div class="max-w-lg relative z-10">
                                <div class="h-8 w-3/4 skeleton-pulse rounded mb-2"></div>
                                <div class="h-4 w-full skeleton-pulse rounded mb-4"></div>
                                <div class="flex gap-3">
                                    <div class="h-12 w-40 skeleton-pulse rounded"></div>
                                    <div class="h-12 w-40 skeleton-pulse rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                
                <!-- Actual Welcome Banner -->
                <div x-show="dataLoaded" x-cloak class="bg-gradient-to-r from-homize-blue to-blue-600 rounded-xl overflow-hidden">
                    <div class="relative px-6 py-8 md:px-10 md:py-12">
                        <div class="max-w-lg relative z-10">
                            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                Selamat Datang Kembali, {{ Auth::user()->nama }}👋
                            </h1>
                            <p class="text-white/90 mb-4">
                                Temukan layanan terbaik untuk kebutuhan rumah Anda hari ini.
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <a href="#layanan-section"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-homize-blue bg-white hover:bg-gray-100 shadow-md transition-colors">
                                    Jelajahi Layanan
                                </a>

                                @if (Auth::user()->merchant)
                                    <a href="{{ route('merchant') }}"
                                        class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-blue-600 shadow-md transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Cek Dashboard Merchant
                                    </a>
                                @endif
                            </div>
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

            @if (Auth::user()->merchant)
                <!-- Merchant Info Banner -->
                <div class="mb-6">
                    <!-- Skeleton for Merchant Banner -->
                    <template x-if="!dataLoaded">
                        <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 skeleton-pulse rounded-full mr-4"></div>
                                    <div>
                                        <div class="h-5 w-40 skeleton-pulse rounded mb-2"></div>
                                        <div class="h-4 w-60 skeleton-pulse rounded"></div>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="h-10 w-10 skeleton-pulse rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Actual Merchant Banner -->
                    <div x-show="dataLoaded" x-cloak class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-homize-blue/10 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Anda terdaftar sebagai Merchant</h3>
                                    <p class="text-gray-600">Pantau pesanan dan kelola layanan Anda melalui dashboard
                                        merchant.</p>
                                </div>
                                <div class="ml-auto">
                                    <a href="{{ route('merchant') }}"
                                        class="inline-flex items-center px-4 py-2 bg-homize-blue border border-transparent rounded-md font-medium text-sm text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                        Buka Dashboard
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Categories Scrollable -->
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-4">Kategori Populer</h2>
                
                <!-- Skeleton for Categories -->
                <template x-if="!dataLoaded">
                    <div class="flex overflow-x-auto pb-2 gap-3 hide-scrollbar">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="flex-shrink-0 w-32 h-24 rounded-lg skeleton-pulse"></div>
                        @endfor
                    </div>
                </template>
                
                <!-- Actual Categories -->
                <div x-show="dataLoaded" x-cloak class="flex overflow-x-auto pb-2 gap-3 hide-scrollbar">
                    @foreach ($kategori as $index => $kat)
                        @if ($index < 6)
                            <a href="{{ route('service', $kat->id) }}"
                                class="flex-shrink-0 bg-white rounded-lg shadow-sm border border-gray-100 p-4 w-[140px] text-center hover:shadow-md transition-shadow">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 bg-white shadow-sm">
                                    @switch($index)
                                        @case(0)
                                            {{-- Jasa Rumah Tangga Icon --}}
                                            <svg class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22L12 3Z" />
                                            </svg>
                                        @break

                                        @case(1)
                                            {{-- Jasa Perbaikan & Instalasi Icon --}}
                                            <svg class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M22.7 19L13.6 9.9C14.5 7.6 14 4.9 12.1 3C10.1 1 7.1 0.6 4.7 1.7L9 6L6 9L1.6 4.7C0.4 7.1 0.9 10.1 2.9 12.1C4.8 14 7.5 14.5 9.8 13.6L18.9 22.7C19.3 23.1 19.9 23.1 20.3 22.7L22.6 20.4C23.1 20 23.1 19.3 22.7 19Z" />
                                            </svg>
                                        @break

                                        @case(2)
                                            {{-- Jasa Pendidikan & Bimbingan Icon --}}
                                            <svg class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M19 2L14 6.5V17.5L19 13V2M6.5 5C4.55 5 2.45 5.4 1 6.5V21.16C1 21.41 1.25 21.66 1.5 21.66C1.6 21.66 1.65 21.59 1.75 21.59C3.1 20.94 5.05 20.5 6.5 20.5C8.45 20.5 10.55 20.9 12 22C13.35 21.15 15.8 20.5 17.5 20.5C19.15 20.5 20.85 20.81 22.25 21.56C22.35 21.61 22.4 21.59 22.5 21.59C22.75 21.59 23 21.34 23 21.09V6.5C22.4 6.05 21.75 5.75 21 5.5V19C19.9 18.65 18.7 18.5 17.5 18.5C15.8 18.5 13.35 19.15 12 20V6.5C10.55 5.4 8.45 5 6.5 5Z" />
                                            </svg>
                                        @break

                                        @case(3)
                                            {{-- Jasa Kesehatan & Kecantikan Icon --}}
                                            <svg class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                                            </svg>
                                        @break

                                        @case(4)
                                            {{-- Jasa Kreatif & Digital Icon --}}
                                            <svg class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M21,16H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10V20H8V22H16V20H14V18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z" />
                                            </svg>
                                        @break

                                        @case(5)
                                            {{-- Jasa Event Organizer Icon --}}
                                            <svg class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z" />
                                            </svg>
                                        @break

                                        @default
                                            {{-- Default/Fallback Icon (Star) --}}
                                            <svg class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M17,18C15.89,18 15,18.89 15,20A2,2 0 0,0 17,22A2,2 0 0,0 19,20C19,18.89 18.1,18 17,18M1,2V4H3L6.6,11.59L5.24,14.04C5.09,14.32 5,14.65 5,15A2,2 0 0,0 7,17H19V15H7.42A0.25,0.25 0 0,1 7.17,14.75C7.17,14.7 7.18,14.66 7.2,14.63L8.1,13H15.55C16.3,13 16.96,12.58 17.3,11.97L20.88,5.5C20.95,5.34 21,5.17 21,5A1,1 0 0,0 20,4H5.21L4.27,2M7,18C5.89,18 5,18.89 5,20A2,2 0 0,0 7,22A2,2 0 0,0 9,20C9,18.89 8.1,18 7,18Z" />
                                            </svg>
                                    @endswitch
                                </div>
                                <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $kat->nama }}</p>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Main Tabs -->
            <x-main-tabs />
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
                            Selesaikan masalah anda,<br>
                            Dari <span class="text-homize-orange">rumah</span>
                        </h1>
                        <p class="mt-4 text-xl text-white opacity-90">
                            Layanan rumahan professional dari jari anda. Pesan layanan terpercaya untuk kebutuhan rumah
                            anda.
                        </p>
                        <div class="mt-8">
                            <a href="#services"
                                onclick="document.getElementById('layanan_section').scrollIntoView({ behavior: 'smooth' });"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-homize-white bg-homize-orange hover:bg-amber-500 shadow-md">
                                Jelajahi Layanan
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
        <div class="py-16 bg-homize-white" id="layanan_section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Layanan Terpopuler</h2>
                    <p class="text-gray-600">Temukan layanan terbaik untuk kebutuhan Anda</p>
                </div>

                <!-- Skeleton for Service Cards -->
                <template x-if="!dataLoaded">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @for ($i = 0; $i < 8; $i++)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                                <div class="relative">
                                    <div class="h-48 w-full skeleton-pulse"></div>
                                    <div class="absolute top-3 left-3">
                                        <div class="h-6 w-24 bg-white/80 skeleton-pulse rounded-full"></div>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="h-6 w-3/4 skeleton-pulse rounded mb-2"></div>
                                    <div class="h-4 w-full skeleton-pulse rounded mb-1"></div>
                                    <div class="h-4 w-2/3 skeleton-pulse rounded mb-4"></div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-1">
                                            <div class="h-4 w-4 skeleton-pulse rounded-full"></div>
                                            <div class="h-4 w-8 skeleton-pulse rounded"></div>
                                        </div>
                                        <div class="h-6 w-20 skeleton-pulse rounded"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </template>
                
                <!-- Actual Service Cards -->
                <div x-show="dataLoaded" x-cloak>
                    <x-service-card :layanan="$layanan" />
                </div>
            </div>
        </div>
    @endauth

    <!-- Footer -->
    @include('components.footer')

    @auth
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Lazy load images
                function lazyLoadImages() {
                    const lazyImages = document.querySelectorAll('img.lazy-image');
                    
                    if ('IntersectionObserver' in window) {
                        const imageObserver = new IntersectionObserver(function(entries, observer) {
                            entries.forEach(function(entry) {
                                if (entry.isIntersecting) {
                                    const img = entry.target;
                                    img.src = img.dataset.src;
                                    img.classList.add('opacity-0');
                                    observer.unobserve(img);
                                }
                            });
                        });
                        
                        lazyImages.forEach(function(image) {
                            imageObserver.observe(image);
                        });
                    } else {
                        // Fallback for browsers that don't support IntersectionObserver
                        lazyImages.forEach(function(img) {
                            img.src = img.dataset.src;
                        });
                    }
                }
                
                // Call lazy load after content is loaded
                setTimeout(lazyLoadImages, 100);
                // Add x-cloak directive style for Alpine.js
                document.head.insertAdjacentHTML('beforeend', `
                    <style>
                        [x-cloak] { display: none !important; }
                    </style>
                `);
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
