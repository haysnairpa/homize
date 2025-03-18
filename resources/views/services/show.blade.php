<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Image & Basic Info -->
                <div class="md:flex">
                    <div class="md:w-1/2">
                        <img src="{{ asset($service->image_url) }}" alt="{{ $service->name }}" class="w-full h-96 object-cover">
                    </div>
                    <div class="md:w-1/2 p-8">
                        <div class="flex items-center mb-4">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $service->name }}</h1>
                            <span class="ml-4 px-3 py-1 bg-[#FFC973] text-[#1c91c4] text-sm font-semibold rounded-full">
                                {{ $service->shop_services->shop->category->name }}
                            </span>
                        </div>
                        
                        <div class="flex items-center mb-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1 text-sm text-gray-600">4.9 (2.1k reviews)</span>
                            </div>
                            <span class="mx-4 text-gray-300">|</span>
                            <span class="text-2xl font-bold text-[#30A0E0]">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Deskripsi Layanan</h3>
                            <p class="text-gray-600">{{ $service->description }}</p>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Lokasi</h3>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="ml-2 text-gray-600">{{ $service->shop_services->shop->address }}</p>
                            </div>
                        </div>

                        <button class="w-full bg-[#30A0E0] hover:bg-[#1c91c4] text-white font-semibold py-3 px-6 rounded-lg transition duration-300">
                            Pesan Sekarang
                        </button>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="border-t border-gray-200 p-8">
                    <h2 class="text-2xl font-bold mb-6">Ulasan Pelanggan</h2>
                    
                    <div class="space-y-6">
                        @foreach($rates as $rate)
                        <div class="border-b border-gray-100 pb-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-[#30A0E0] rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($rate->customer->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold">{{ $rate->customer->name }}</h4>
                                    <div class="flex items-center">
                                        @for($i = 0; $i < $rate->rate; $i++)
                                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600">{{ $rate->message }}</p>
                            @if($rate->media_url)
                                <img src="{{ asset($rate->media_url) }}" alt="Review Image" class="mt-4 rounded-lg w-32 h-32 object-cover">
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 