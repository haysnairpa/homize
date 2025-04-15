<x-guest-layout>
    <x-slot name="title">{{ $service->name }} - Homize</x-slot>
    
    <x-slot name="head">
        <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </x-slot>

    <!-- Navigation -->
    @include('components.navigation')

    <div class="py-12 bg-gray-50 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Service Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Left Section with Image -->
                    <div class="md:w-1/2 relative">
                        <img src="{{ $service->image_url ?? asset('images/service-default.jpg') }}" 
                             alt="{{ $service->name }}" 
                             class="w-full h-[500px] object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/80 backdrop-blur-sm text-homize-blue text-sm font-medium rounded-full">
                                {{ $service->shop_services->first()?->shop->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>
                    </div>

                    <!-- Right Section with Details -->
                    <div class="md:w-1/2 p-8">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Service Information (Left Column) -->
                            <div class="md:w-7/12">
                                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $service->name }}</h1>
                                
                                <div class="mb-6">
                                    <div class="flex items-center">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="ml-1 text-sm font-medium">{{ number_format($rates->avg('rate'), 1) }}</span>
                                            <span class="ml-1 text-sm text-gray-500">({{ $rates->count() }} rating)</span>
                                        </div>
                                        {{-- <span class="mx-2 text-gray-300">•</span>
                                        <span class="text-sm text-gray-500">Jasa ini telah dipesan 2rb+ kali</span> --}}
                                    </div>
                                </div>

                                <div class="border-t border-b border-gray-100 py-4 my-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-3xl font-bold text-homize-blue">
                                            Rp {{ number_format($service->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Service Details -->
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Detail:</h3>
                                    <p class="text-gray-600">{{ $service->description }}</p>
                                </div>
                            </div>

                            <!-- Shop Information (Right Column) -->
                            <div class="md:w-5/12">
                                <!-- Merchant Information Box -->
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
                                    <div class="bg-homize-blue/5 px-4 py-3 border-b border-gray-200">
                                        <h3 class="font-semibold text-homize-blue flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Informasi Merchant
                                        </h3>
                                    </div>
                                    
                                    <div class="p-4">
                                        <div class="flex items-start mb-4">
                                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <div>
                                                <h4 class="font-medium text-gray-900">Lokasi:</h4>
                                                <p class="text-gray-600 text-sm">{{ $service->shop_services->first()?->shop->address ?? 'Alamat tidak tersedia' }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($service->shop_services->first()?->shop->phone)
                                        <div class="flex items-start mb-4">
                                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <div>
                                                <h4 class="font-medium text-gray-900">Kontak:</h4>
                                                <p class="text-gray-600 text-sm">{{ $service->shop_services->first()?->shop->phone }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($service->shop_services->first()?->shop->operational_hours)
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <h4 class="font-medium text-gray-900">Jam Operasional:</h4>
                                                <p class="text-gray-600 text-sm">{{ $service->shop_services->first()?->shop->operational_hours }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-3">
                                    <button class="w-full bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        Pesan Sekarang
                                    </button>
                                    
                                    <button class="w-full border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        Simpan ke Favorit
                                    </button>
                                    
                                    <button class="w-full border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>
                                        Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
                <x-service-reviews :rates="$rates" />
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</x-guest-layout>