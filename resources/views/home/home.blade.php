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
        .nav-spacer{
            height: 100px
        }
    </style>
</head>
<body class="font-sans antialiased bg-homize-gray">
    @auth
        <div class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
            @include('components.navigation')
        </div>

        <div class="nav-spacer"></div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 pt-24 md:pt-20 pb-16">
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
                            <a href="#layanan-section" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-homize-blue bg-white hover:bg-gray-100 shadow-md transition-colors">
                                Jelajahi Layanan
                            </a>
                        </div>
                        <div class="absolute right-0 bottom-0 opacity-20">
                            <svg class="w-48 h-48 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2z"></path>
                                <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Scrollable -->
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-4">Kategori Populer</h2>
                <div class="flex overflow-x-auto pb-2 gap-3 hide-scrollbar">
                    @foreach($kategori as $index => $kat)
                        @if($index < 6)
                            <a 
                                href="{{ route('jasa', $kat->id) }}" 
                                class="flex-shrink-0 bg-white rounded-lg shadow-sm border border-gray-100 p-4 w-[140px] text-center hover:shadow-md transition-shadow"
                            >
                                <div class="w-12 h-12 bg-homize-blue/10 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-homize-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $kat->nama }}</p>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Main Tabs -->
            <div x-data="{ activeTab: 'services' }" class="mb-8">
                <!-- Tab Navigation -->
                <div class="border-b mb-4">
                    <div class="flex space-x-4">
                        <button 
                            @click="activeTab = 'services'" 
                            :class="{ 'border-b-2 border-homize-blue text-homize-blue': activeTab === 'services' }"
                            class="px-4 py-2 font-medium"
                        >
                            Layanan Tersedia
                        </button>
                        <button 
                            @click="activeTab = 'merchants'" 
                            :class="{ 'border-b-2 border-homize-blue text-homize-blue': activeTab === 'merchants' }"
                            class="px-4 py-2 font-medium"
                        >
                            Merchant Terpopuler
                        </button>
                        <button 
                            @click="activeTab = 'wishlist'" 
                            :class="{ 'border-b-2 border-homize-blue text-homize-blue': activeTab === 'wishlist' }"
                            class="px-4 py-2 font-medium"
                        >
                            Favorit Saya
                        </button>
                    </div>
                </div>
                
                <!-- Tab Content -->
                <div x-show="activeTab === 'services'" id="layanan-section">
                    <!-- Filter Bar -->
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <span class="font-medium">Filter:</span>
                            </div>
                            
                            <div class="flex flex-1 flex-wrap gap-2">
                                <!-- Kategori Dropdown -->
                                <div x-data="{ open: false }" class="relative">
                                    <button 
                                        @click="open = !open" 
                                        class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50"
                                    >
                                        <span>Kategori</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div 
                                        x-show="open" 
                                        @click.away="open = false" 
                                        class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10 border"
                                        style="display: none;"
                                    >
                                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" data-kategori-id="">Semua Kategori</a>
                                        @foreach($allKategori as $kat)
                                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" data-kategori-id="{{ $kat->id }}">{{ $kat->nama }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Harga Dropdown -->
                                <div x-data="{ open: false }" class="relative">
                                    <button 
                                        @click="open = !open" 
                                        class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50"
                                    >
                                        <span>Harga</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div 
                                        x-show="open" 
                                        @click.away="open = false" 
                                        class="absolute left-0 mt-2 w-80 bg-white rounded-md shadow-lg z-10 border p-4"
                                        style="display: none;"
                                    >
                                        <p class="mb-2 font-medium">Rentang Harga</p>
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <input 
                                                    type="number" 
                                                    id="min-price" 
                                                    placeholder="Min" 
                                                    class="w-[45%] border border-gray-300 rounded-lg focus:ring-homize-blue focus:border-homize-blue"
                                                >
                                                <span>-</span>
                                                <input 
                                                    type="number" 
                                                    id="max-price" 
                                                    placeholder="Max" 
                                                    class="w-[45%] border border-gray-300 rounded-lg focus:ring-homize-blue focus:border-homize-blue"
                                                >
                                            </div>
                                            <button 
                                                id="apply-filter" 
                                                class="w-full bg-homize-blue text-white px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors"
                                            >
                                                Terapkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Urutkan Dropdown -->
                                <div x-data="{ open: false }" class="relative">
                                    <button 
                                        @click="open = !open" 
                                        class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50"
                                    >
                                        <span>Urutkan</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div 
                                        x-show="open" 
                                        @click.away="open = false" 
                                        class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10 border"
                                        style="display: none;"
                                    >
                                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" data-sort="newest">Terbaru</a>
                                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" data-sort="rating">Rating Tertinggi</a>
                                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" data-sort="price_asc">Harga: Rendah ke Tinggi</a>
                                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" data-sort="price_desc">Harga: Tinggi ke Rendah</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Services Grid -->
                    <div id="layanan-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($layanan as $item)
                            <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="relative">
                                    <img 
                                        src="{{ asset('placeholder.jpg') }}" 
                                        alt="{{ $item->nama_layanan }}"
                                        class="w-full h-48 object-cover"
                                    >
                                    <button class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                    @if(isset($item->is_premium) && $item->is_premium)
                                        <span class="absolute top-2 left-2 bg-amber-500 text-white text-xs px-2 py-1 rounded-md">
                                            Premium
                                        </span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-medium line-clamp-2 mb-1">{{ $item->nama_layanan }}</h3>
                                    <p class="text-homize-blue font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    <div class="flex items-center mt-2 text-sm">
                                        <div class="flex items-center text-amber-500">
                                            @php
                                                $rating = isset($item->rating_avg) ? $item->rating_avg : 0;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating)
                                                    ★
                                                @elseif($i - 0.5 <= $rating)
                                                    ☆
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-1 text-gray-600">
                                            ({{ number_format($rating, 1) }}) • {{ $item->rating_count ?? 0 }} ulasan
                                        </span>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 border-t">
                                    <a 
                                        href="{{ route('layanan.detail', $item->id) }}" 
                                        class="block w-full bg-homize-blue text-white text-center px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors"
                                    >
                                        Pesan Sekarang
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div x-show="activeTab === 'merchants'" style="display: none;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Merchant cards - Replace placeholders with actual data -->
                        @for($i = 0; $i < 4; $i++)
                            <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-6 flex flex-col items-center text-center">
                                    <div class="h-20 w-20 rounded-full bg-gray-200 mb-4 overflow-hidden">
                                        <img src="{{ asset('placeholder-merchant.jpg') }}" alt="Merchant" class="h-full w-full object-cover">
                                    </div>
                                    <h3 class="font-medium mb-1">Merchant Name {{ $i + 1 }}</h3>
                                    <div class="flex items-center text-amber-500 mb-1">★★★★☆</div>
                                    <p class="text-sm text-gray-600">12 layanan tersedia</p>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 border-t">
                                    <a 
                                        href="#" 
                                        class="block w-full border border-gray-300 text-center px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    >
                                        Lihat Profil
                                    </a>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                
                <div x-show="activeTab === 'wishlist'" style="display: none;">
                    @if(count($wishlists) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($wishlists as $item)
                                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                    <div class="relative">
                                        <img 
                                            src="{{ asset('placeholder.jpg') }}" 
                                            alt="{{ $item->nama_layanan }}"
                                            class="w-full h-48 object-cover"
                                        >
                                        <button class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-medium line-clamp-2 mb-1">{{ $item->nama_layanan }}</h3>
                                        <p class="text-homize-blue font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $item->nama_usaha }}</p>
                                    </div>
                                    <div class="px-4 py-3 bg-gray-50 border-t">
                                        <a 
                                            href="{{ route('layanan.detail', $item->id_layanan) }}" 
                                            class="block w-full bg-homize-blue text-white text-center px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors"
                                        >
                                            Pesan Sekarang
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-lg p-8 text-center">
                            <p class="text-gray-500">Belum ada layanan yang ditambahkan ke favorit</p>
                            <a 
                                href="#layanan-section" 
                                @click="activeTab = 'services'" 
                                class="inline-block mt-4 bg-homize-blue text-white px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors"
                            >
                                Jelajahi Layanan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <!-- Bottom Navigation for Mobile -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t z-30">
            <div class="flex justify-around">
                <a href="{{ route('home') }}" class="flex-1 flex flex-col items-center py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-xs mt-1">Beranda</span>
                </a>
                <a href="#" class="flex-1 flex flex-col items-center py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="text-xs mt-1">Cari</span>
                </a>
                <a href="#" class="flex-1 flex flex-col items-center py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs mt-1">Pesanan</span>
                </a>
                <a href="#" class="flex-1 flex flex-col items-center py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                
                fetch('{{ route("home.filter") }}', {
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

