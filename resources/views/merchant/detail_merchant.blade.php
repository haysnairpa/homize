<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $merchant->nama_usaha }} - Homize</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-homize-gray">
    @include('components.navigation')

    <!-- Hero Section -->
    <div class="relative bg-homize-blue pt-32 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">{{ $merchant->nama_usaha }}</h1>
                <p class="text-xl text-white/80">Temukan layanan terbaik dari {{ $merchant->nama_usaha }}</p>
            </div>
        </div>
        <div class="absolute -bottom-1 left-0 right-0 h-16 bg-homize-gray"
            style="clip-path: polygon(0 0, 100% 100%, 100% 100%, 0% 100%);"></div>
    </div>

    <!-- Merchant Info Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-start gap-6">
                <!-- Merchant Image -->
                <div class="flex-shrink-0">
                    <img src="{{ $merchant->profile_url }}" alt="{{ $merchant->nama_usaha }}"
                        class="w-32 h-32 rounded-full object-cover border-4 border-homize-blue">
                </div>

                <!-- Merchant Details -->
                <div class="flex-grow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $merchant->nama_usaha }}</h2>
                            <p class="text-gray-600 mt-1">{{ $merchant->alamat }}</p>
                        </div>
                        <button
                            class="px-6 py-2 bg-homize-blue text-white rounded-lg hover:bg-homize-blue-second transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Ikuti
                        </button>
                    </div>

                    <!-- Merchant Stats -->
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $merchant->rating_avg ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-gray-600">{{ number_format($merchant->rating_avg, 1) }}</span>
                            <span class="text-gray-500">({{ $merchant->rating_count }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-gray-600">{{ $merchant->follower_count }} Pengikut</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="text-gray-600">{{ $merchant->transaction_count }} Transaksi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Sort Section -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="px-4 py-2 bg-white rounded-lg shadow-sm border border-gray-200 flex items-center gap-2 hover:border-homize-blue transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-2 w-48 bg-white rounded-lg shadow-lg p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="space-y-2">
                                @foreach (range(5, 1) as $rating)
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-homize-blue focus:ring-homize-blue">
                                        <span class="ml-2 text-sm text-gray-600">{{ $rating }} Bintang</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="price"
                                        class="text-homize-blue focus:ring-homize-blue">
                                    <span class="ml-2 text-sm text-gray-600">Termurah</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price"
                                        class="text-homize-blue focus:ring-homize-blue">
                                    <span class="ml-2 text-sm text-gray-600">Termahal</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <select
                    class="px-4 py-2 bg-white rounded-lg shadow-sm border border-gray-200 text-gray-700 focus:outline-none focus:ring-2 focus:ring-homize-blue">
                    <option>Terbaru</option>
                    <option>Rating Tertinggi</option>
                    <option>Harga: Rendah ke Tinggi</option>
                    <option>Harga: Tinggi ke Rendah</option>
                </select>
            </div>

            <p class="text-gray-600">Menampilkan {{ count($layanan) }} layanan</p>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach ($layanan as $index => $service)
                <a href="{{ route('layanan.detail', $service->id) }}"
                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="relative">
                        <img src="{{ $merchant->profile_url }}" alt="{{ $service->nama_layanan }}"
                            class="w-full h-48 object-cover">
                        <div class="absolute top-3 left-3">
                            <span
                                class="px-3 py-1 bg-white/80 backdrop-blur-sm text-homize-blue text-sm font-medium rounded-full">
                                {{ $service->nama_sub_kategori }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2 line-clamp-1">{{ $service->nama_layanan }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $service->deskripsi_layanan }}</p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z" />
                                </svg>
                                <span
                                    class="text-sm text-gray-600">{{ number_format($service->rating_avg, 1) }}</span>
                                <span class="text-sm text-gray-500">({{ $service->rating_count }})</span>
                            </div>
                            <span class="font-bold text-homize-blue">
                                Rp {{ number_format($service->harga, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>{{ $service->transaction_count }} terjual</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    @include('components.footer')
</body>

</html>
