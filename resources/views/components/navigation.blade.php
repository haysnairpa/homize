<nav class="bg-homize-blue shadow-md fixed top-0 z-[100] w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Top Navigation Bar -->
        <div class="flex h-16 items-center justify-between lg:justify-start lg:gap-x-5">
            <!-- Mobile menu button -->
            <div class="flex items-center lg:hidden">
                <button id="mobileMenuButton" type="button" class="text-white hover:text-homize-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Left Section -->
            <div class="flex items-center space-x-4">
                <a href="/home" class="flex-shrink-0">
                    <img class="h-8 w-auto" src="{{ asset('images/homizelogo.png') }}" alt="Homize">
                </a>
                <div class="relative hidden lg:block">
                    <button id="kategoriButton"
                        class="text-white hover:text-homize-orange px-3 py-2 text-sm font-medium flex items-center">
                        Kategori
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search Bar - Hidden on mobile, shown on medium screens and up -->
            <div class="hidden md:flex flex-1 max-w-2xl mx-4">
                <form action="{{ route('search') }}" method="GET" class="w-full">
                    <div class="relative">
                        <input type="text" name="query" value="{{ request('query') }}"
                            class="w-full bg-white rounded-md py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-homize-blue"
                            placeholder="Cari layanan atau merchant...">

                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Section - Icons and Auth -->
            <div class="flex items-center space-x-4 h-full">

                <!-- Bookmark Icon -->
                <div id="wishlistButton" class="relative group hidden sm:block cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 fill-white hover:fill-homize-orange cursor-pointer" viewBox="0 0 24 24">
                        <path
                            d="M16 2H8a3.003 3.003 0 0 0-3 3v16.5a.5.5 0 0 0 .75.434l6.25-3.6l6.25 3.6A.5.5 0 0 0 19 21.5V5a3.003 3.003 0 0 0-3-3zm2 18.635l-5.75-3.312a.51.51 0 0 0-.5 0L6 20.635V5a2.003 2.003 0 0 1 2-2h8a2.003 2.003 0 0 1 2 2v15.635z" />
                    </svg>
                </div>

                <div class="h-[55%] w-[1px] bg-homize-white hidden lg:block"></div>

                <!-- Auth Section -->
                <div class="hidden lg:flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 text-white hover:text-homize-orange">
                                <span class="text-sm font-medium capitalize">{{ Auth::user()->nama }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1"
                                style="display: none;">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/login"
                            class="text-white hover:text-homize-orange px-3 py-2 text-sm font-medium">Masuk</a>
                        <a href="/register"
                            class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-homize-blue bg-homize-white hover:bg-gray-100">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation Links - Hidden on mobile -->
    <div class="border-t border-homize-blue-second hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8 py-2 text-sm">
                @php
                    $randomNumbers = [];
                    if (isset($bottomNavigation) && count($bottomNavigation) > 0) {
                        while (count($randomNumbers) < 3) {
                            $randomNumber = rand(0, count($bottomNavigation) - 1);
                            if (!in_array($randomNumber, $randomNumbers)) {
                                $randomNumbers[] = $randomNumber;
                            }
                        }
                    }
                @endphp
                @if (isset($bottomNavigation) && count($bottomNavigation) > 0)
                    <a href="{{ route('jasa', [$bottomNavigation[$randomNumbers[0]]->id]) }}"
                        class="text-white hover:text-homize-orange">
                        {{ $bottomNavigation[$randomNumbers[0]]->category_name }}
                    </a>
                    <a href="{{ route('jasa', [$bottomNavigation[$randomNumbers[1]]->id]) }}"
                        class="text-white hover:text-homize-orange">
                        {{ $bottomNavigation[$randomNumbers[1]]->category_name }}
                    </a>
                    <a href="{{ route('jasa', [$bottomNavigation[$randomNumbers[2]]->id]) }}"
                        class="text-white hover:text-homize-orange">
                        {{ $bottomNavigation[$randomNumbers[2]]->category_name }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Search - Shown only on mobile -->
    <div class="md:hidden px-4 py-3 border-t border-homize-blue-second">
        <div class="relative w-full" x-data="searchMobile()">
            <form action="{{ route('search') }}" method="GET">
                <div class="relative">
                    <input type="text" name="query" value="{{ request('query') }}" x-model="searchQuery"
                        @input.debounce.300ms="search" @click.away="closeResults" @keydown.escape="closeResults"
                        @keydown.arrow-down.prevent="navigateResults('down')"
                        @keydown.arrow-up.prevent="navigateResults('up')" @keydown.enter.prevent="selectResult"
                        class="w-full bg-white rounded-md py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-homize-blue"
                        placeholder="Cari layanan atau merchant...">

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <div x-show="isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </div>
            </form>

            <div x-show="showResults" x-cloak
                class="absolute mt-1 w-full bg-white rounded-md shadow-lg overflow-hidden z-50">
                <div class="max-h-96 overflow-y-auto">
                    <template x-for="(result, index) in searchResults" :key="result.id">
                        <a :href="result.url" class="block hover:bg-gray-50 transition-colors"
                            :class="{ 'bg-gray-50': selectedIndex === index }" @mouseover="selectedIndex = index">
                            <div class="flex items-center p-4">
                                <img :src="result.image" :alt="result.name"
                                    class="h-12 w-12 object-cover rounded">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900" x-text="result.name"></p>
                                        <p class="text-sm font-medium text-homize-blue"
                                            x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(result.price)"></p>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span x-text="result.merchant"></span> •
                                        <span x-text="result.category"></span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </template>
                    <div x-show="searchResults.length === 0 && searchQuery !== ''"
                        class="p-4 text-sm text-gray-500 text-center">
                        Tidak ada hasil yang ditemukan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu - Hidden by default -->
    <div id="mobileMenu" class="hidden lg:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 border-t border-homize-blue-second">
            <div class="px-3 py-2 text-white font-medium">Kategori</div>
            @foreach ($kategori as $nav)
                <div class="px-3">
                    <button
                        class="mobile-category-button w-full text-left text-white hover:text-homize-orange py-2 text-sm font-medium flex items-center justify-between">
                        {{ $nav->nama }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="mobile-subcategory hidden pl-4 py-2 space-y-2">
                        @foreach ($sub_kategori as $index1 => $sub)
                            @if ($sub->id_kategori == $nav->id)
                                <a href="{{ route('jasa', [$ids[$index1]->id]) }}"
                                    class="block text-white hover:text-homize-orange text-sm py-1">
                                    {{ trim($sub->nama) }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Mobile Auth Links -->
            <div class="border-t border-homize-blue-second pt-3 mt-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block px-3 py-2 text-white hover:text-homize-orange text-sm font-medium">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="block px-3 py-2">
                        @csrf
                        <button type="submit" class="text-white hover:text-homize-orange text-sm font-medium">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="/login"
                        class="block px-3 py-2 text-white hover:text-homize-orange text-sm font-medium">Masuk</a>
                    <a href="/register"
                        class="block px-3 py-2 text-white hover:text-homize-orange text-sm font-medium">Daftar</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Kategori Dropdown (Full Width) - For desktop -->
    <div id="kategoriDropdown" class="hidden absolute left-0 right-0 w-full bg-white shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($kategori as $index => $nav)
                        <div class="space-y-2">
                            <h3 class="font-bold text-gray-900 mb-4">{{ $nav->nama }}</h3>
                            <ul class="space-y-2">
                                @foreach ($sub_kategori as $index1 => $sub)
                                    @if ($sub->id_kategori == $nav->id)
                                        <li>
                                            <a href="{{ route('jasa', [$ids[$index1]->id]) }}"
                                                class="text-gray-700 hover:text-homize-blue">
                                                {{ trim($sub->nama) }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Wishlist Dropdown (Full Width) - For desktop -->
    <div id="wishlistDropdown" class="hidden absolute left-0 right-0 w-full bg-white shadow-lg z-50 py-4">
        <div class="flex items-center justify-between max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-4">
            <h2 class="text-xl font-bold text-gray-900">Wishlist Layanan</h2>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 max-h-[400px] overflow-y-auto" id="wishlistContent">
            <div class="space-y-6">
                @auth
                    @if (isset($wishlists) && count($wishlists) > 0)
                        <div class="space-y-4">
                            @foreach ($wishlists as $wishlist)
                                <div
                                    class="bg-white border border-gray-100 rounded-lg p-4 hover:border-gray-200 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <!-- Checkbox (optional) -->
                                        <div class="flex-shrink-0">
                                            <input type="checkbox"
                                                class="h-5 w-5 rounded border-gray-300 text-homize-blue focus:ring-homize-blue">
                                        </div>

                                        <!-- Service Image -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $wishlist->profile_url ?? asset('images/default-merchant.png') }}"
                                                alt="{{ $wishlist->nama_usaha }}" class="h-16 w-16 object-cover rounded">
                                        </div>

                                        <!-- Service Info -->
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-900">{{ $wishlist->nama_layanan }}</h3>
                                            <div class="text-sm text-gray-500 mt-1">
                                                <p>{{ $wishlist->deskripsi_layanan }}</p>
                                                <p>Waktu: {{ $wishlist->durasi }} {{ $wishlist->tipe_durasi }}</p>
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="flex-shrink-0 text-right">
                                            <div class="text-homize-blue font-medium">
                                                Rp{{ number_format($wishlist->harga, 0, ',', '.') }}/{{ $wishlist->satuan }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Wishlist Anda Kosong</h3>
                            <p class="text-gray-500 mb-4">Belum ada layanan yang ditambahkan ke wishlist</p>
                            <a href="#"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second transition-colors">
                                Jelajahi Layanan
                            </a>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Silahkan Login</h3>
                        <p class="text-gray-500 mb-4">Login untuk melihat dan mengelola wishlist Anda</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a href="/login"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second transition-colors w-full sm:w-auto">
                                Masuk
                            </a>
                            <a href="/register"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-homize-blue border border-homize-blue hover:bg-homize-blue hover:text-white transition-colors w-full sm:w-auto">
                                Daftar
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Desktop dropdowns
        const kategoriButton = document.getElementById('kategoriButton');
        const kategoriDropdown = document.getElementById('kategoriDropdown');
        const wishlistButton = document.getElementById('wishlistButton');
        const wishlistDropdown = document.getElementById('wishlistDropdown');

        // Mobile menu
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileCategoryButtons = document.querySelectorAll('.mobile-category-button');

        // Toggle mobile menu
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            // Hide dropdowns when opening mobile menu
            kategoriDropdown.classList.add('hidden');
            wishlistDropdown.classList.add('hidden');
        });

        // Toggle mobile subcategories
        mobileCategoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const subcategory = this.nextElementSibling;
                subcategory.classList.toggle('hidden');

                // Update the arrow icon
                const arrow = this.querySelector('svg');
                if (subcategory.classList.contains('hidden')) {
                    arrow.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
                } else {
                    arrow.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />';
                }
            });
        });

        // Desktop kategori dropdown
        if (kategoriButton) {
            kategoriButton.addEventListener('click', function(e) {
                e.stopPropagation();
                kategoriDropdown.classList.toggle('hidden');
                // Hide wishlist dropdown when opening kategori
                wishlistDropdown.classList.add('hidden');
            });
        }

        // Desktop wishlist dropdown
        if (wishlistButton) {
            wishlistButton.addEventListener('click', function(e) {
                e.stopPropagation();
                wishlistDropdown.classList.toggle('hidden');
                // Hide kategori dropdown when opening wishlist
                kategoriDropdown.classList.add('hidden');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!kategoriDropdown.contains(e.target) && e.target !== kategoriButton) {
                kategoriDropdown.classList.add('hidden');
            }

            if (!wishlistDropdown.contains(e.target) && e.target !== wishlistButton) {
                wishlistDropdown.classList.add('hidden');
            }
        });

        // Prevent dropdowns from closing when clicking inside them
        kategoriDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        wishlistDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Definisikan fungsi searchMobile terlebih dahulu agar tersedia untuk Alpine.js
    window.searchMobile = function() {
        return {
            searchQuery: new URLSearchParams(window.location.search).get('query') || '',
            searchResults: [],
            showResults: false,
            isLoading: false,
            selectedIndex: -1,

            async search() {
                if (this.searchQuery.length < 2) {
                    this.searchResults = [];
                    this.showResults = false;
                    return;
                }

                this.isLoading = true;
                try {
                    const response = await fetch(`/api/search?query=${encodeURIComponent(this.searchQuery)}`);
                    this.searchResults = await response.json();
                    this.showResults = true;
                    this.selectedIndex = -1;
                } catch (error) {
                    console.error('Search error:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            navigateResults(direction) {
                if (direction === 'down') {
                    this.selectedIndex = Math.min(this.selectedIndex + 1, this.searchResults.length - 1);
                } else {
                    this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                }
            },

            selectResult() {
                if (this.selectedIndex >= 0 && this.searchResults[this.selectedIndex]) {
                    window.location.href = this.searchResults[this.selectedIndex].url;
                } else {
                    // Submit form jika tidak ada hasil yang dipilih
                    this.$el.closest('form').submit();
                }
            },

            closeResults() {
                this.showResults = false;
                this.selectedIndex = -1;
            }
        };
    };
</script>
