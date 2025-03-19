@props(['featuredServices'])

<!-- Featured Services Section -->
<div class="bg-homize-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-homize-blue mb-2">Featured Services</h2>
            <p class="text-gray-600 mb-8">Discover our top handpicked services for you</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredServices as $shopService)
            <a href="{{ route('services.show', $shopService->services->id) }}" class="block bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                <div class="relative">
                    <img src="{{ $shopService->services->image_url ?? asset('images/service-default.jpg') }}" 
                         alt="{{ $shopService->services->name }}" 
                         class="w-full h-48 object-cover">
                    <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                        {{ $shopService->shop->category->name }}
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $shopService->services->name }}</h3>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z"/>
                            </svg>
                            <span class="ml-1 text-sm text-gray-600">4.9</span>
                        </div>
                        <span class="text-homize-blue font-semibold">Rp {{ number_format($shopService->services->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second shadow-md">
                View All Services
            </a>
        </div>
    </div>
</div>
