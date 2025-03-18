<div class="border-t border-gray-100 p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Ulasan Pelanggan</h2>
        <button class="text-homize-blue hover:text-homize-blue-second font-medium">
            Tampilkan ulasan lainnya
        </button>
    </div>
    
    <div class="space-y-6">
        @forelse($rates as $rate)
            <div class="border-b border-gray-100 pb-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-homize-blue rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr($rate->customer?->name ?? 'Anonymous', 0, 1) }}
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-900">{{ $rate->customer?->name ?? 'Anonymous' }}</h4>
                            <span class="text-sm text-gray-500">{{ $rate->created_at?->diffForHumans() ?? '-' }}</span>
                        </div>
                        <div class="flex items-center mt-1">
                            @for($i = 0; $i < ($rate->rate ?? 0); $i++)
                                <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="mt-2 text-gray-600">{{ $rate->message ?? 'Tidak ada pesan' }}</p>
                        @if($rate->media_url)
                            <div class="mt-3">
                                <img src="{{ $rate->media_url }}" alt="Review Image" class="rounded-lg w-24 h-24 object-cover">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-4">
                Belum ada ulasan
            </div>
        @endforelse
    </div>
</div> 