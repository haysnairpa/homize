<div x-data="mainTabsComponent()" x-init="init()" class="mb-8">
    <!-- Tab Navigation -->
    <div class="border-b mb-4">
        <div class="flex space-x-4">
            <button @click="activeTab = 'services'"
                :class="{ 'border-b-2 border-homize-blue text-homize-blue': activeTab === 'services' }"
                class="px-4 py-2 font-medium">
                Layanan Tersedia
            </button>
            <button @click="activeTab = 'merchants'"
                :class="{ 'border-b-2 border-homize-blue text-homize-blue': activeTab === 'merchants' }"
                class="px-4 py-2 font-medium">
                Merchant Terpopuler
            </button>
            <button @click="activeTab = 'wishlist'"
                :class="{ 'border-b-2 border-homize-blue text-homize-blue': activeTab === 'wishlist' }"
                class="px-4 py-2 font-medium">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="font-medium">Filter:</span>
                </div>

                <div class="flex flex-1 flex-wrap gap-2">
    <!-- Script Alpine.js untuk filter -->
    <script>
        function mainTabsComponent() {
            return {
                activeTab: 'services',
                kategori_id: '',
                min_price: '',
                max_price: '',
                sort_by: 'newest',
                isLoading: false,
                init() {
                    // Optionally: auto-load if needed
                },
                applyFilter() {
                    this.isLoading = true;
                    fetch("{{ route('home.filter') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({
                            kategori_id: this.kategori_id,
                            min_price: this.min_price,
                            max_price: this.max_price,
                            sort_by: this.sort_by
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('layanan-grid').innerHTML = data.html;
                        this.isLoading = false;
                    });
                }
            }
        }
    </script>
                    <!-- Kategori Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50">
                            <span>Kategori</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10 border"
                            style="display: none;">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                                @click.prevent="$data.kategori_id = ''; applyFilter()"
                                :class="{ 'bg-homize-blue text-white': $data.kategori_id === '' }"
                            >Semua Kategori</a>
                            @foreach ($allKategori as $kat)
                                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                                    @click.prevent="$data.kategori_id = '{{ $kat->id }}'; applyFilter()"
                                    :class="{ 'bg-homize-blue text-white': $data.kategori_id === '{{ $kat->id }}' }"
                                >{{ $kat->nama }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Harga Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50">
                            <span>Harga</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 mt-2 w-80 bg-white rounded-md shadow-lg z-10 border p-4"
                            style="display: none;">
                            <p class="mb-2 font-medium">Rentang Harga (IDR)</p>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <input type="number" placeholder="Min" x-model="$data.min_price"
                                        class="w-[45%] border border-gray-300 rounded-lg focus:ring-homize-blue focus:border-homize-blue outline-none px-2 py-1">
                                    <span>-</span>
                                    <input type="number" placeholder="Max" x-model="$data.max_price"
                                        class="w-[45%] border border-gray-300 rounded-lg focus:ring-homize-blue focus:border-homize-blue outline-none px-2 py-1">
                                </div>
                                <button @click.prevent="applyFilter()"
                                    class="w-full bg-homize-blue text-white px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors">
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Urutkan Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50">
                            <span>Urutkan</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10 border"
                            style="display: none;">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                                @click.prevent="$data.sort_by = 'newest'; applyFilter()"
                                :class="{ 'bg-homize-blue text-white': $data.sort_by === 'newest' }"
                            >Terbaru</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                                @click.prevent="$data.sort_by = 'rating'; applyFilter()"
                                :class="{ 'bg-homize-blue text-white': $data.sort_by === 'rating' }"
                            >Rating Tertinggi</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                                @click.prevent="$data.sort_by = 'price_asc'; applyFilter()"
                                :class="{ 'bg-homize-blue text-white': $data.sort_by === 'price_asc' }"
                            >Harga: Rendah ke Tinggi</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
                                @click.prevent="$data.sort_by = 'price_desc'; applyFilter()"
                                :class="{ 'bg-homize-blue text-white': $data.sort_by === 'price_desc' }"
                            >Harga: Tinggi ke Rendah</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Grid -->
        <div id="layanan-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($layanan as $item)
                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="relative">
                        <img src="{{ $item ? asset('storage/' . $item->media_url) : asset('images/default-service.jpg') }}"
                            alt="{{ $item->nama_layanan }}" class="w-full h-48 object-scale-down">
                        <button class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        @if (isset($item->is_premium) && $item->is_premium)
                            <span class="absolute top-2 left-2 bg-amber-500 text-white text-xs px-2 py-1 rounded-md">
                                Premium
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium line-clamp-2 mb-1">{{ $item->nama_layanan }}</h3>
                        <p class="text-homize-blue font-bold">Rp
                            {{ number_format($item->harga, 0, ',', '.') }}</p>
                        <div class="flex items-center mt-2 text-sm">
                            <div class="flex items-center text-amber-500">
                                @php
                                    $rating = isset($item->rating_avg) ? $item->rating_avg : 0;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating)
                                        ★
                                    @elseif($i - 0.5 <= $rating)
                                        ☆
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-1 text-gray-600">
                                ({{ number_format($rating, 1) }})
                                • {{ $item->rating_count ?? 0 }} ulasan
                            </span>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t">
                        <a href="{{ route('layanan.detail', $item->id) }}"
                            class="block w-full bg-homize-blue text-white text-center px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div x-show="activeTab === 'merchants'" style="display: none;">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($popularMerchants as $merchant)
                <div
                    class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow h-[360px] flex flex-col">
                    <div class="p-6 flex-grow flex flex-col items-center text-center">
                        <!-- Merchant Profile Image -->
                        <div class="w-24 h-24 rounded-full bg-gray-200 mb-4 overflow-hidden flex-shrink-0">
                            <img src="{{ $merchant ? asset('storage/' . $merchant->profile_url) : asset('placeholder-merchant.jpg') }}"
                                alt="{{ $merchant->nama_usaha }}" class="h-full w-full object-scale-down">
                        </div>

                        <!-- Merchant Name -->
                        <h3 class="font-medium mb-2 text-lg line-clamp-1">{{ $merchant->nama_usaha }}</h3>

                        <!-- Rating -->
                        <div class="flex items-center text-amber-500 mb-2">
                            @php
                                $rating = round($merchant->rating_avg * 2) / 2;
                            @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($rating))
                                    <span>★</span>
                                @elseif($i - 0.5 == $rating)
                                    <span>★</span>
                                @else
                                    <span>☆</span>
                                @endif
                            @endfor
                            <span
                                class="text-gray-600 text-sm ml-1">({{ number_format($merchant->rating_avg, 1) }})</span>
                        </div>

                        <!-- Stats -->
                        <div class="flex justify-center gap-4 text-sm text-gray-600 mb-2">
                            <div class="flex flex-col items-center">
                                <span class="font-semibold">{{ number_format($merchant->followers_count) }}</span>
                                <span>Pengikut</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="font-semibold">{{ number_format($merchant->services_count) }}</span>
                                <span>Layanan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="px-4 py-3 bg-gray-50 border-t mt-auto">
                        <a href="{{ route('merchant.detail', $merchant->id) }}"
                            class="block w-full border border-homize-blue text-homize-blue text-center px-4 py-2 rounded-lg hover:bg-homize-blue hover:text-white transition-colors">
                            Lihat Profil
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div x-show="activeTab === 'wishlist'" style="display: none;">
        @if (count($wishlists) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($wishlists as $item)
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="relative">
                            <img src="{{ asset('placeholder.jpg') }}" alt="{{ $item->nama_layanan }}"
                                class="w-full h-48 object-cover">
                            <button
                                class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium line-clamp-2 mb-1">{{ $item->nama_layanan }}</h3>
                            <p class="text-homize-blue font-bold">Rp
                                {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $item->nama_usaha }}</p>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 border-t">
                            <a href="{{ route('layanan.detail', $item->id_layanan) }}"
                                class="block w-full bg-homize-blue text-white text-center px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg p-8 text-center">
                <p class="text-gray-500">Belum ada layanan yang ditambahkan ke favorit</p>
                <a href="#layanan-section" @click="activeTab = 'services'"
                    class="inline-block mt-4 bg-homize-blue text-white px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors">
                    Jelajahi Layanan
                </a>
            </div>
        @endif
    </div>
</div>
