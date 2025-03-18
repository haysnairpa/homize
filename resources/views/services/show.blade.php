<x-app-layout>
    <div class="py-12 bg-gray-50">
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
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h1>
                                <div class="flex items-center mt-2">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="ml-1 text-sm font-medium">4.9</span>
                                        <span class="ml-1 text-sm text-gray-500">(530 rating)</span>
                                    </div>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-sm text-gray-500">Jasa ini telah dipesan 2rb+ kali</span>
                                </div>
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
                        <div class="space-y-6">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Detail:</h3>
                                <p class="text-gray-600">{{ $service->description }}</p>
                            </div>

                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Lokasi Merchant:</h3>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $service->shops->first()?->address ?? 'Alamat tidak tersedia' }}
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-4 mt-8">
                                <button class="flex-1 bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-3 px-6 rounded-lg transition duration-300">
                                    Pesan Sekarang
                                </button>
                                <button class="flex items-center justify-center w-12 h-12 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                @include('components.service-reviews', ['rates' => $rates])
            </div>
        </div>
    </div>
</x-app-layout> 