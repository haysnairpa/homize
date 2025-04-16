@foreach ($layanan as $item)
    <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
        <div class="relative">
            <img src="{{ asset('storage/' . $item->media_url) }}" alt="{{ $item->nama_layanan }}"
                class="w-full h-48 object-cover">
            <button class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
            @if (isset($item->is_premium) && $item->is_premium)
                <span class="absolute top-2 left-2 bg-amber-500 text-white text-xs px-2 py-1 rounded-md">
                    Premium
                </span>
            @endif
        </div>
        <div class="p-4">
            <h3 class="font-medium line-clamp-2 mb-1">{{ $item->nama_layanan }}</h3>
            <p class="text-homize-blue font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
            <div class="flex items-center mt-2 text-sm">
                <div class="flex items-center text-amber-500">
                    @php
                        $rating = isset($item->rating_avg) ? $item->rating_avg : 0;
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $rating)
                            ★
                        @elseif($i - 0.5 <= $rating)
                            ☆
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <span class="ml-1 text-gray-600">
                    ({{ number_format($rating, 1) }}) • {{ $item->rating_count ?? 0 }} ulasan
                </span>
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 border-t">
            <a href="{{ route('layanan.detail', $item->id) }}"
                class="block w-full bg-homize-blue text-white text-center px-4 py-2 rounded-lg hover:bg-homize-blue-second transition-colors">
                Pesan Sekarang
            </a>
        </div>
    </div>
@endforeach
