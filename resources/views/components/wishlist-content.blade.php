@if($isAuthenticated)
    @if(count($wishlists) > 0)
        <div class="space-y-4">
            @foreach($wishlists as $wishlist)
                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:border-gray-200 transition-colors">
                    <div class="flex items-center gap-4">
                        <!-- Checkbox (optional) -->
                        <div class="flex-shrink-0">
                            <input type="checkbox" class="h-5 w-5 rounded border-gray-300 text-homize-blue focus:ring-homize-blue">
                        </div>
                        
                        <!-- Service Image -->
                        <div class="flex-shrink-0">
                            <img src="{{ $wishlist->profile_url ?? asset('images/default-merchant.png') }}" 
                                alt="{{ $wishlist->nama_usaha }}" 
                                class="h-16 w-16 object-cover rounded">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Wishlist Anda Kosong</h3>
            <p class="text-gray-500 mb-4">Belum ada layanan yang ditambahkan ke wishlist</p>
            <a href="#" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second transition-colors">
                Jelajahi Layanan
            </a>
        </div>
    @endif
@else
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Silahkan Login</h3>
        <p class="text-gray-500 mb-4">Login untuk melihat dan mengelola wishlist Anda</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="/login" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second transition-colors w-full sm:w-auto">
                Masuk
            </a>
            <a href="/register" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-homize-blue border border-homize-blue hover:bg-homize-blue hover:text-white transition-colors w-full sm:w-auto">
                Daftar
            </a>
        </div>
    </div>
@endif 