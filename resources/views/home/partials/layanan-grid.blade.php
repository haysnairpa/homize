@if (count($layanan) === 0)
    <div class="col-span-full flex flex-col items-center justify-center py-16">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 018 0v2m-4-4a4 4 0 100-8 4 4 0 000 8zm6 4v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2a6 6 0 0112 0z" />
        </svg>
        <p class="text-gray-500 text-lg mb-2">Tidak ada layanan ditemukan</p>
        <p class="text-gray-400">Coba ubah filter atau kata kunci pencarian Anda.</p>
    </div>
@endif
@foreach ($layanan as $item)
    <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
        <div class="relative">
            <img src="{{ $item->media_url }}" alt="{{ $item->nama_layanan }}"
                class="w-full h-48 object-cover">

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
