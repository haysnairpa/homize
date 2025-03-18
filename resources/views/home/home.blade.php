<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homize - Home Services at Your Fingertips</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    <div class="relative">
                        <button id="kategoriButton" class="text-white hover:text-homize-orange px-3 py-2 text-sm font-medium flex items-center">
                            Kategori
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
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
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-white hover:text-homize-orange">
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1"
                                 style="display: none;">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="text-white hover:text-homize-orange px-3 py-2 text-sm font-medium">Masuk</a>
                        <a href="/register" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-homize-blue bg-homize-white hover:bg-gray-100">
                            Daftar
                        </a>
                    @endauth
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
        
        <!-- Kategori Dropdown (Full Width) -->
        <div id="kategoriDropdown" class="hidden absolute left-0 right-0 w-full bg-white shadow-lg z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Jasa Rumah Tangga -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Jasa Rumah Tangga</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Asisten Rumah Tangga</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Pengasuh Anak / Lansia</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Layanan Kebersihan</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Tukang Kebun/Tanaman</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Layanan Cuci Mobil/Motor</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Laundry Antar Jemput</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Security / Satpam Pribadi</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Tukang Jahit / Permak</a></li>
                        </ul>
                    </div>
                    
                    <!-- Jasa Pendidikan & Bimbingan -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Jasa Pendidikan & Bimbingan</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Les Akademik & Persiapan Ujian Privat</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Pengajar Al-Qur'an Privat</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Tutor Bahasa Asing</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Kursus Diri Diri</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Les Seni & Olahraga (Tari, Musik, Renang)</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Kursus Mengemudi</a></li>
                        </ul>
                    </div>
                    
                    <!-- Jasa Kreatif & Digital -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Jasa Kreatif & Digital</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Percetakan Digital</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Desain Grafis dan Ilustrasi</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Editing Foto / Video</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Konsultan Digital Marketing</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Software Engineer</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Konten Writing & Copywriting</a></li>
                        </ul>
                    </div>
                    
                    <!-- Jasa Penyewaan Barang -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Jasa Penyewaan Barang</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Sewa Kendaraan (Sepeda, Mobil, motor)</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Perlengkapan Acara / Konten</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">(Sound System, Proyektor, Kamera,dll)</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Sewa Wardrobe (Jas, Dress, Baju Adat)</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Sewa Peralatan Camping</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Sewa Peralatan Masak</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Sewa Console Game</a></li>
                        </ul>
                    </div>
                    
                    <!-- Jasa Perbaikan & Instalasi -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Jasa Perbaikan & Instalasi</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Ahli Servis Otomotif</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Teknisi Listrik</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Spesialis Saluran Air</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Konstruksi / Renovasi Rumah</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Servis AC, Kulkas, Televisi</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Servis Handphone/Laptop</a></li>
                        </ul>
                    </div>
                    
                    <!-- Jasa Kesehatan & Kecantikan -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Jasa Kesehatan & Kecantikan</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Medical Check Up Home-to-Home</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Perawat atau Pendamping Pasien</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Spesialis Fisioterapi atau Pijat</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Konsultan Psikologi</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Yoga & Fitness Trainer</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Facial Treatment</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Make-up Artists</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Pangkas Rambut Home-to-Home</a></li>
                        </ul>
                    </div>
                    
                    <!-- Jasa Event Organizer -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Jasa Event Organizer</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Dekorator Acara</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">MC / Host</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Band / Performer Musik</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Performer Dance / Tari</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-homize-blue">Photographer / Videographer</a></li>
                        </ul>
                    </div>
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

    <!-- Footer -->
    <footer class="bg-homize-blue py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-white text-sm">
                &copy; 2024 Homize. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriButton = document.getElementById('kategoriButton');
        const kategoriDropdown = document.getElementById('kategoriDropdown');
        
        // Toggle dropdown when button is clicked
        kategoriButton.addEventListener('click', function(e) {
            e.stopPropagation();
            kategoriDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!kategoriDropdown.contains(e.target) && e.target !== kategoriButton) {
                kategoriDropdown.classList.add('hidden');
            }
        });
        
        // Prevent dropdown from closing when clicking inside it
        kategoriDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    </script>
</body>
</html> 