<!-- Browse by Category Section -->
<div class="py-16 bg-homize-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Browse by Category
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Find the perfect service for your needs
            </p>
        </div>

        <div class="mt-12 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach ($navigation as $nav)
                <div class="group">
                    <div class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 overflow-hidden">
                        <div
                            class="flex items-center justify-center h-full bg-homize-blue bg-opacity-10 group-hover:bg-opacity-20 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-homize-blue" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-sm text-gray-700 text-center">{{ $nav->jasa_name }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
