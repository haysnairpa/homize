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
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-sans antialiased bg-homize-gray">
    @include('components.navigation')

    <!-- Hero Section -->
    <div class="relative bg-homize-blue pt-20 md:pt-32 pb-12 md:pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 md:mb-4">{{ $merchant->nama_usaha }}</h1>
                <p class="text-base md:text-xl text-white/80">Temukan layanan terbaik dari {{ $merchant->nama_usaha }}
                </p>
            </div>
        </div>
    </div>

    <!-- Merchant Info Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 mb-6 md:mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-4 md:gap-6">
                <!-- Merchant Image -->
                <div class="flex-shrink-0">
                    <img src="{{ $merchant->profile_url }}" alt="{{ $merchant->nama_usaha }}"
                        class="w-24 h-24 md:w-32 md:h-32 rounded-full object-contain border-4 border-homize-blue">
                </div>

                <!-- Merchant Details -->
                <div class="flex-grow w-full">
                    <div
                        class="flex flex-col md:flex-row items-center md:items-start md:justify-between gap-3 md:mb-4 text-center md:text-left">
                        <div>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $merchant->nama_usaha }}</h2>
                            <p class="text-gray-600 mt-1 text-sm md:text-base">{{ $merchant->alamat }}</p>
                        </div>
                        @php
                            $isFollowing = Auth::check()
                                ? App\Models\TokoFavorit::where('id_user', Auth::id())
                                    ->where('id_merchant', $merchant->id)
                                    ->exists()
                                : false;
                        @endphp
                        <button id="followBtn" data-merchant-id="{{ $merchant->id }}"
                            class="px-4 md:px-6 py-2 mt-2 md:mt-0 {{ $isFollowing ? 'bg-homize-blue text-homize-white' : 'bg-white text-homize-blue border-2 border-homize-blue' }} rounded-lg hover:bg-homize-blue-second hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $isFollowing ? 'M5 13l4 4L19 7' : 'M12 4v16m8-8H4' }}" />
                            </svg>
                            {{ $isFollowing ? 'Mengikuti' : 'Ikuti' }}
                        </button>
                    </div>

                    <!-- Merchant Stats -->
                    <div
                        class="flex flex-wrap justify-center md:justify-start items-center gap-4 md:gap-8 mt-3 md:mt-0">
                        <div class="flex items-center gap-1 md:gap-2">
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 md:w-5 md:h-5 {{ $i <= $merchant->rating_avg ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span
                                class="text-sm md:text-base text-gray-600">{{ number_format($merchant->rating_avg, 1) }}</span>
                            <span class="text-xs md:text-sm text-gray-500">({{ $merchant->rating_count }})</span>
                        </div>
                        <div class="flex items-center gap-1 md:gap-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm md:text-base text-gray-600"
                                id="followerCount">{{ $merchant->follower_count }}
                                Pengikut</span>
                        </div>
                        <div class="flex items-center gap-1 md:gap-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="text-sm md:text-base text-gray-600">{{ $merchant->transaction_count }}
                                Transaksi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Sort Section -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6 md:mb-8">
            <div class="w-full sm:w-auto">
                <select id="sortSelect"
                    class="w-full sm:w-auto px-4 sm:px-8 py-2 bg-white rounded-lg shadow-sm border border-gray-200 text-gray-700 focus:outline-none focus:ring-2 focus:ring-homize-blue">
                    <option value="newest">Terbaru</option>
                    <option value="highest_rating">Terbaik</option>
                    <option value="price_asc">Termurah</option>
                    <option value="price_desc">Termahal</option>
                </select>
            </div>

            <p class="text-sm md:text-base text-gray-600">Menampilkan {{ count($layanan) }} layanan</p>
        </div>

        <!-- Services Grid -->
        <div id="servicesGrid"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
            @include('merchant.partials.layanan-grid', ['layanan' => $layanan])
        </div>
    </div>

    @include('components.footer')

    <script>
        const followBtn = document.getElementById('followBtn');
        const followerCount = document.getElementById('followerCount');
        const sortSelect = document.getElementById('sortSelect');
        const servicesGrid = document.getElementById('servicesGrid');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Sort functionality
        sortSelect.addEventListener('change', async function() {
            try {
                const response = await fetch(`/merchant/{{ $merchant->id }}/sort?sort=${this.value}`);
                const data = await response.json();

                // Update the services grid with new HTML
                servicesGrid.innerHTML = data.html;
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // Follow button functionality
        followBtn.addEventListener('click', async function() {
            if (!@json(Auth::check())) {
                window.location.href = '/login';
                return;
            }

            try {
                const response = await fetch('/toko-favorit/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        merchant_id: this.dataset.merchantId
                    })
                });

                const data = await response.json();

                if (data.status === 'followed') {
                    this.classList.add('bg-homize-blue', 'text-homize-white');
                    this.classList.remove('border-2', 'border-homize-blue');
                    this.innerHTML =
                        '<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Mengikuti';
                    // Update follower count
                    const currentCount = parseInt(followerCount.textContent);
                    followerCount.textContent = `${currentCount + 1} Pengikut`;
                } else {
                    this.classList.remove('bg-homize-blue', 'text-homize-white');
                    this.classList.add('border-2', 'border-homize-blue');
                    this.innerHTML =
                        '<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Ikuti';
                    // Update follower count
                    const currentCount = parseInt(followerCount.textContent);
                    followerCount.textContent = `${currentCount - 1} Pengikut`;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>

</html>
