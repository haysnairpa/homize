<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Pencarian - Homize</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-homize-gray">
    <!-- Navigation -->
    @include('components.navigation')

    <!-- Hero Section -->
    <div class="relative bg-homize-blue pt-32 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Hasil Pencarian</h1>
                <p class="text-xl text-white/80">"{{ $query }}"</p>
            </div>
        </div>
        <div class="absolute -bottom-1 left-0 right-0 h-16 bg-homize-gray" style="clip-path: polygon(0 0, 100% 100%, 100% 100%, 0% 100%);"></div>
    </div>

    <!-- Results Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Merchants Section -->
        @if($merchants->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Merchant</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($merchants as $merchant)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="relative">
                        <img src="{{ $merchant->profile_url ?? asset('images/default-merchant.png') }}" 
                             alt="{{ $merchant->nama_usaha }}"
                             class="w-full h-48 object-cover rounded-t-xl">
                        <div class="absolute bottom-2 right-2 bg-white px-2 py-1 rounded-full text-sm font-medium">
                            ⭐ {{ number_format($merchant->rating, 1) }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-900">{{ $merchant->nama_usaha }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $merchant->alamat }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{ $merchant->service_count }} Layanan</span>
                            <span class="text-sm text-gray-500">{{ $merchant->review_count }} Review</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Services Section -->
        @if($services->count() > 0)
        <div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Layanan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900">{{ $service->nama_layanan }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $service->nama_usaha }}</p>
                            </div>
                            <div class="flex items-center">
                                <span class="text-yellow-400">⭐</span>
                                <span class="ml-1 text-sm font-medium">{{ number_format($service->rating, 1) }}</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $service->kategori }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ $service->deskripsi_layanan }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-lg font-semibold text-homize-blue">
                                Rp {{ number_format($service->harga, 0, ',', '.') }}
                                <span class="text-sm text-gray-500">/{{ $service->satuan }}</span>
                            </div>
                            <a href="{{ route('layanan.detail', $service->id) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-homize-blue hover:bg-blue-700">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($merchants->count() == 0 && $services->count() == 0)
        <div class="text-center py-12">
            <div class="text-gray-500">Tidak ada hasil yang ditemukan</div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>
</html>
