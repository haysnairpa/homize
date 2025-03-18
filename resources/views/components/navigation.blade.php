<!-- Navigation -->
<nav class="bg-homize-blue shadow-md fixed top-0 z-[100] w-full">
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
                            <span class="text-sm font-medium capitalize">{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute -right-5 mt-5 w-48 bg-white rounded-md shadow-lg py-1"
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
                        <li><a href="#" class="text-gray-700 hover:text-homize-blue">Sewa Kendaraan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

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