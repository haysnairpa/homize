<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $layanan->nama_layanan }} - Homize</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-sans antialiased bg-homize-gray overflow-x-hidden relative min-h-screen min-w-screen">
    <!-- Navigation -->
    @include('components.navigation')

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-28">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Image Gallery -->
                <div class="md:w-1/2">
                    <div class="relative h-96">
                        @php
                            $aset = App\Models\Aset::where('id_layanan', $layanan->id)->first();
                        @endphp
                        <img src="{{ $aset ? $aset->media_url : asset('images/default-service.jpg') }}"
                            alt="{{ $layanan->nama_layanan }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Service Info -->
                <div class="md:w-1/2 p-8">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $layanan->nama_layanan }}</h1>

                    <div class="mt-4 flex items-center">
                        <div class="flex items-center">
                            <span
                                class="text-3xl font-bold text-gray-900">{{ number_format($layanan->rating_avg, 1) }}</span>
                            <div class="ml-2">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $layanan->rating_avg ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-500">{{ $layanan->rating_count }} reviews</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900">Tentang Layanan</h3>
                        <p class="mt-2 text-gray-600">{{ $layanan->deskripsi_layanan }}</p>
                    </div>

                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pengalaman</p>
                                <p class="text-lg font-semibold">{{ $layanan->pengalaman }} Tahun</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Harga</p>
                                <p class="text-lg font-semibold text-homize-blue">
                                    Rp {{ number_format($layanan->harga, 0, ',', '.') }} / {{ $layanan->satuan }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900">Jam Operasional</h3>
                        <p class="mt-2 text-gray-600">
                            {{ $layanan->jam_buka }} - {{ $layanan->jam_tutup }}
                        </p>
                    </div>

                    @php
                        $revisi = App\Models\Revisi::find($layanan->id_revisi ?? 1);
                    @endphp

                    @if($revisi && $revisi->id != 1 && $revisi->harga > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Revisi</h3>
                        <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">Harga Revisi</p>
                                    <p class="text-lg font-semibold text-homize-orange">
                                        Rp {{ number_format($revisi->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Durasi Revisi</p>
                                    <p class="text-lg font-semibold">
                                        {{ $revisi->durasi }} {{ $revisi->tipe_durasi }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Merchant Info -->
            <div class="border-t border-gray-200 px-8 py-12">
                <div class="flex items-start gap-8 mb-12">
                    <div class="w-24 h-24 rounded-full overflow-hidden flex-shrink-0">
                        <img src="{{ $layanan->profile_url }}" alt="{{ $layanan->nama_usaha }}"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $layanan->nama_usaha }}</h2>
                        <p class="text-gray-600 mt-2">Professional Home Service Provider</p>
                        <div class="flex items-center gap-4 mt-4">
                            @php
                                $isFollowing = Auth::check()
                                    ? App\Models\TokoFavorit::where('id_user', Auth::id())
                                        ->where('id_merchant', $layanan->id_merchant)
                                        ->exists()
                                    : false;

                                $isWishlisted = Auth::check()
                                    ? App\Models\Wishlist::where('id_user', Auth::id())
                                        ->where('id_layanan', $layanan->id)
                                        ->exists()
                                    : false;
                            @endphp

                            <button id="followBtn" data-merchant-id="{{ $layanan->id_merchant }}"
                                class="px-6 py-2 border-2 border-homize-blue {{ $isFollowing ? 'bg-homize-blue text-white' : 'text-homize-blue' }} hover:bg-homize-blue hover:text-white rounded-full transition duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $isFollowing ? 'M5 13l4 4L19 7' : 'M12 4v16m8-8H4' }}" />
                                </svg>
                                {{ $isFollowing ? 'Following' : 'Follow' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <button
                        class="w-full bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-4 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Pesan Sekarang
                    </button>

                    <button id="wishlistBtn" data-layanan-id="{{ $layanan->id }}"
                        class="w-full border-2 {{ $isWishlisted ? 'border-homize-orange text-homize-orange' : 'border-gray-200 text-gray-700' }} hover:border-homize-orange hover:bg-homize-orange/5 hover:text-homize-orange font-medium py-4 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6 {{ $isWishlisted ? 'fill-current' : '' }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        {{ $isWishlisted ? 'Wishlisted' : 'Add to Wishlist' }}
                    </button>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="border-t border-gray-200 px-8 py-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Ulasan Pelanggan</h2>

                <div class="bg-white rounded-lg p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="text-4xl font-bold text-homize-blue">
                            {{ number_format($layanan->rating_avg, 1) }}
                            <span class="text-lg text-gray-500">/5.0</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-lg mb-1">
                                {{ $layanan->rating_count > 0 ? round(($ratingStats[5] / $layanan->rating_count) * 100) : 0 }}% pembeli merasa puas
                            </p>
                            <p class="text-gray-500">{{ $layanan->rating_count }} rating • {{ array_sum($ratingStats) }} ulasan</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-12 gap-8">
                        <!-- Rating Statistics -->
                        <div class="md:col-span-4">
                            <div class="space-y-4">
                                @foreach (range(5, 1) as $star)
                                    <div class="flex items-center">
                                        <span class="w-8 text-sm text-gray-600">{{ $star }}</span>
                                        <div class="flex-1 h-2 mx-2 bg-gray-200 rounded">
                                            @php
                                                $percentage =
                                                    $layanan->rating_count > 0
                                                        ? ($ratingStats[$star] / $layanan->rating_count) * 100
                                                        : 0;
                                            @endphp
                                            <div class="h-2 bg-yellow-400 rounded"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="w-8 text-sm text-gray-600">{{ $ratingStats[$star] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Review List -->
                        <div class="md:col-span-8">
                            <div class="space-y-8">
                                @foreach ($ratings as $rating)
                                    <div class="border-b border-gray-100 pb-8">
                                        <div class="flex items-center mb-4">
                                            <div
                                                class="w-10 h-10 bg-homize-blue rounded-full flex items-center justify-center text-white font-semibold">
                                                {{ substr($rating->user_name ?? 'Anonymous', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <p class="font-semibold">{{ $rating->user_name ?? 'Anonymous' }}</p>
                                                <div class="flex items-center mt-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $rating->rate ? 'text-yellow-400' : 'text-gray-300' }}"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        @if ($rating->message)
                                            <p class="text-gray-600">{{ $rating->message }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <script>
        const followBtn = document.getElementById('followBtn');
        const wishlistBtn = document.getElementById('wishlistBtn');

        // Setup CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                    this.classList.add('bg-homize-blue', 'text-white');
                    this.innerHTML =
                        '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Following';
                } else {
                    this.classList.remove('bg-homize-blue', 'text-white');
                    this.innerHTML =
                        '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Follow';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // Add this new function to fetch wishlist content
        async function updateWishlistDropdown() {
            try {
                const response = await fetch('/wishlist/content');
                const html = await response.text();
                const wishlistDropdown = document.getElementById('wishlistDropdown');
                if (wishlistDropdown) {
                    wishlistDropdown.innerHTML = html;
                }
            } catch (error) {
                console.error('Error updating wishlist:', error);
            }
        }

        wishlistBtn.addEventListener('click', async function() {
            if (!@json(Auth::check())) {
                window.location.href = '/login';
                return;
            }

            try {
                const response = await fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        layanan_id: this.dataset.layananId
                    })
                });

                const data = await response.json();

                if (data.status === 'added') {
                    this.classList.add('text-homize-orange', 'border-homize-orange');
                    this.querySelector('svg').classList.add('fill-current');
                    this.querySelector('svg').nextSibling.textContent = 'Wishlisted';
                    // Update the wishlist dropdown
                    await updateWishlistDropdown();
                } else {
                    this.classList.remove('text-homize-orange', 'border-homize-orange');
                    this.querySelector('svg').classList.remove('fill-current');
                    this.querySelector('svg').nextSibling.textContent = 'Add to Wishlist';
                    // Update the wishlist dropdown
                    await updateWishlistDropdown();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>

</html>
