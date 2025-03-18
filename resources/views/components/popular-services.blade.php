<!-- Popular Services Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Popular Services
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Most booked services by our customers
            </p>
        </div>

        <div class="mt-12 grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($popularServices as $service)
            <a href="{{ route('services.show', $service->id) }}" class="block bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl">
                <div class="relative">
                    <img src="{{ $service->image_url ?? asset('images/service-default.jpg') }}" alt="{{ $service->name }}" class="w-full h-40 object-cover">
                    <div class="absolute top-0 right-0 bg-homize-orange text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                        {{ $service->shop_services->shop->category->name ?? 'Uncategorized' }}
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ Str::limit($service->description ?? 'Professional service', 50) }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="ml-1 text-sm text-gray-600">4.9</span>
                        </div>
                        <span class="text-homize-blue font-semibold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second shadow-md">
                Explore More
            </a>
        </div>
    </div>
</div>

</div>
