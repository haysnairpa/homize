@foreach ($layanan as $index => $service)
    <a href="{{ route('layanan.detail', $service->id) }}"
        class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
        <div class="relative">

            <img src="{{ $service->url_layanan }}" alt="{{ $service->nama_layanan }}"
                class="w-full h-48 object-contain">
            <div class="absolute top-3 left-3">
                <span class="px-3 py-1 bg-white/80 backdrop-blur-sm text-homize-blue text-sm font-medium rounded-full">
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
                    <span class="text-sm text-gray-600">{{ number_format($service->rating_avg, 1) }}</span>
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
