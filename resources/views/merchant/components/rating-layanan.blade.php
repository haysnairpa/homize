<div class="space-y-8">
    @if($merchant->ratings->count() > 0)
        @foreach($merchant->ratings as $rating)
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-homize-blue rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr($rating->user->nama ?? 'Anonymous', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $rating->user->nama ?? 'Anonymous' }}</p>
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $rating->rate ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                        </div>
                        @if($rating->message)
                            <p class="mt-2 text-gray-600">{{ $rating->message }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-12">
            <div class="mb-4">
                <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Rating</h3>
            <p class="mt-1 text-gray-500">Rating dari pelanggan akan muncul di sini</p>
        </div>
    @endif
</div>