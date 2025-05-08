<x-guest-layout>
    <x-slot name="title">{{ $kategoriData->nama }} Services - Homize</x-slot>
    
    <x-slot name="head">
        <link rel="icon" href="{{ asset('images/homizeicon.png') }}">
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </x-slot>

    @include('components.navigation')

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-homize-blue to-blue-600 pt-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
            <div class="text-center relative z-10">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow-md">{{ $kategoriData->nama }}</h1>
                <p class="text-xl text-white mb-6 max-w-2xl mx-auto drop-shadow-md">Temukan berbagai layanan {{ strtolower($kategoriData->nama) }} terbaik</p>
                <div class="inline-block bg-white/10 backdrop-blur-sm px-6 py-3 rounded-full border border-white/20">
                    <p class="text-gray font-medium">{{ $services->count() }} layanan tersedia untuk Anda</p>
                </div>
            </div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-black/5 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 right-0 mt-32">
            <svg class="fill-current text-gray-50" viewBox="0 0 1440 120">
                <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-gray-50">
        <!-- Filters -->
        <form action="{{ route('service', $kategoriData->id) }}" method="GET" id="filterForm" class="mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm">
                <div class="flex flex-wrap items-center gap-4">
                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = !open" class="flex items-center gap-2 px-4 py-2 border rounded-lg hover:border-homize-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            Filter
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-64 bg-white rounded-lg shadow-lg p-4">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rating</label>
                                    <select name="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-homize-blue focus:ring focus:ring-homize-blue focus:ring-opacity-50" onchange="document.getElementById('filterForm').submit()">
                                        <option value="" {{ ($filters['rating'] ?? '') == '' ? 'selected' : '' }}>Semua Rating</option>
                                        <option value="4" {{ ($filters['rating'] ?? '') == '4' ? 'selected' : '' }}>4+ Bintang</option>
                                        <option value="3" {{ ($filters['rating'] ?? '') == '3' ? 'selected' : '' }}>3+ Bintang</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                                    <select name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-homize-blue focus:ring focus:ring-homize-blue focus:ring-opacity-50" onchange="document.getElementById('filterForm').submit()">
                                        <option value="" {{ ($filters['price'] ?? '') == '' ? 'selected' : '' }}>Semua Harga</option>
                                        <option value="low" {{ ($filters['price'] ?? '') == 'low' ? 'selected' : '' }}>< Rp 100.000</option>
                                        <option value="medium" {{ ($filters['price'] ?? '') == 'medium' ? 'selected' : '' }}>Rp 100.000 - Rp 500.000</option>
                                        <option value="high" {{ ($filters['price'] ?? '') == 'high' ? 'selected' : '' }}>> Rp 500.000</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <select name="sort" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-homize-blue" onchange="document.getElementById('filterForm').submit()">
                        <option value="newest" {{ ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="rating" {{ ($filters['sort'] ?? '') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="price_low" {{ ($filters['sort'] ?? '') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="price_high" {{ ($filters['sort'] ?? '') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                    </select>
                    @if(!empty($filters['rating']) || !empty($filters['price']) || ($filters['sort'] ?? '') != 'newest')
                        <a href="{{ route('service', $kategoriData->id) }}" class="px-4 py-2 text-sm text-gray-700 hover:text-homize-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                </div>
                <p class="text-gray-600">{{ $services->count() }} layanan tersedia</p>
            </div>
        </form>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($services as $service)
                <a href="{{ route('layanan.detail', $service->id) }}" class="group">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-lg">
                        <div class="relative">
                            <img src="{{ $service->media_url ? asset('storage/' . $service->media_url) : asset('images/service-default.jpg') }}" alt="{{ $service->nama_usaha }}" class="w-full h-48 object-scale-down">
                            <div class="absolute top-3 right-3">
                                <div class="flex items-center gap-1 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-medium">{{ number_format($service->rating_avg, 1) }}</span>
                                </div>
                            </div>
                            <div class="absolute bottom-3 left-3">
                                <span class="px-3 py-1 bg-white/80 backdrop-blur-sm text-homize-blue text-sm font-medium rounded-full">
                                    {{ $service->kategori_nama }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-lg text-gray-800 line-clamp-1">{{ $service->nama_layanan }}</h3>
                            </div>
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $service->deskripsi_layanan }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $service->nama_usaha }}</span>
                                <span class="font-bold text-homize-blue">
                                    Rp {{ number_format($service->harga, 0, ',', '.') }}
                                    <span class="text-sm font-normal text-gray-500">/{{ $service->satuan }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    @include('components.footer')
</x-guest-layout>