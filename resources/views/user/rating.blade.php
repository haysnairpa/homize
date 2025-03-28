<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Berikan Rating & Ulasan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Pesanan #{{ $booking->id }}</h3>
                    <div class="mt-2 flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($booking->merchant->profile_url)
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($booking->merchant->profile_url) }}" alt="{{ $booking->merchant->nama_usaha }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-homize-blue flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($booking->merchant->nama_usaha, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->merchant->nama_usaha }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->layanan->nama_layanan }}</div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.rating.store', $booking->id) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex items-center">
                            <div class="flex space-x-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="rating-star text-2xl text-gray-300 hover:text-yellow-400" data-value="{{ $i }}">★</button>
                                @endfor
                            </div>
                            <input type="hidden" name="rate" id="rating-value" value="">
                        </div>
                        @error('rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Ulasan (Opsional)</label>
                        <textarea id="message" name="message" rows="4" class="shadow-sm focus:ring-homize-blue focus:border-homize-blue block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Bagikan pengalaman Anda dengan layanan ini...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="media_url" class="block text-sm font-medium text-gray-700 mb-2">Foto (Opsional)</label>
                        <input type="file" id="media_url" name="media_url" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-homize-blue file:text-white hover:file:bg-homize-blue-second">
                        <p class="mt-1 text-sm text-gray-500">Upload foto hasil layanan (JPG, PNG, maks 2MB)</p>
                        @error('media_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-homize-blue mr-3">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-homize-blue hover:bg-homize-blue-second focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-homize-blue">
                            Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating-star');
            const ratingInput = document.getElementById('rating-value');
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.dataset.value;
                    ratingInput.value = value;
                    
                    // Reset all stars
                    stars.forEach(s => s.classList.remove('text-yellow-400'));
                    stars.forEach(s => s.classList.add('text-gray-300'));
                    
                    // Fill stars up to selected
                    for (let i = 0; i < value; i++) {
                        stars[i].classList.remove('text-gray-300');
                        stars[i].classList.add('text-yellow-400');
                    }
                });
            });
        });
    </script>
</x-app-layout> 