<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $kategoriData->nama }} - Homize</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-homize-gray min-h-screen relative overflow-x-hidden">
    <div class="min-h-screen flex flex-col">
        @include('components.navigation')
        <div class="flex-1">
            <!-- Hero Section -->
    <div class="relative bg-homize-blue pt-32 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">{{ $kategoriData->nama }}</h1>
                <p class="text-xl text-white/80">Temukan layanan terbaik untuk kebutuhan Anda</p>
            </div>
        </div>
        <div class="absolute -bottom-1 left-0 right-0 h-16 bg-homize-gray" style="clip-path: polygon(0 0, 100% 100%, 100% 100%, 0% 100%);"></div>
    </div>

    <!-- Filter & Sort Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('jasa', $kategoriData->id) }}" method="GET" id="filterForm">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="px-4 py-2 bg-white rounded-lg shadow-sm border border-gray-200 flex items-center gap-2 hover:border-homize-blue transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-64 bg-white rounded-lg shadow-lg p-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                <div class="space-y-2">
                                    @foreach(range(5, 1) as $rating)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="rating[]" value="{{ $rating }}" class="rounded border-gray-300 text-homize-blue focus:ring-homize-blue"
                                                {{ in_array($rating, $filters['rating'] ?? []) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">{{ $rating }} Bintang</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="price" value="lowest" class="text-homize-blue focus:ring-homize-blue"
                                            {{ ($filters['price'] ?? '') == 'lowest' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Termurah</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="price" value="highest" class="text-homize-blue focus:ring-homize-blue"
                                            {{ ($filters['price'] ?? '') == 'highest' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Termahal</span>
                                    </label>
                                </div>
                            </div>
                            <div class="pt-2">
                                <button type="submit" class="w-full px-4 py-2 bg-homize-blue text-white rounded-lg hover:bg-homize-blue-dark transition-colors">
                                    Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <select name="sort" class="px-4 py-2 bg-white rounded-lg shadow-sm border border-gray-200 text-gray-700 focus:outline-none focus:ring-2 focus:ring-homize-blue" onchange="document.getElementById('filterForm').submit()">
                        <option value="newest" {{ ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="rating" {{ ($filters['sort'] ?? '') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="price_low" {{ ($filters['sort'] ?? '') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="price_high" {{ ($filters['sort'] ?? '') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                    </select>
                </div>

                <p class="text-gray-600">Menampilkan {{ $jasa->count() }} layanan</p>
            </div>
        </form>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($jasa as $service)
                <a href="{{ route('layanan.detail', $service->id) }}" class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ $service->profile_url }}" alt="{{ $service->nama_usaha }}" class="w-full h-48 object-cover">
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-white/80 backdrop-blur-sm text-homize-blue text-sm font-medium rounded-full">
                                {{ $kategoriData->nama }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2 line-clamp-1">{{ $service->nama_layanan }}</h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $service->deskripsi_layanan }}</p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm text-gray-600">{{ number_format($service->rating_avg, 1) }}</span>
                                <span class="text-sm text-gray-500">({{ $service->rating_count }})</span>
                            </div>
                            <span class="font-bold text-homize-blue">
                                Rp {{ number_format($service->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ \App\Helpers\HariHelper::formatHari($service->hari) }}<br>
                            {{ $service->jam_buka }} - {{ $service->jam_tutup }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

        </div>
        @include('components.footer')
    </div>
</body>
</html>
