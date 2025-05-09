<!-- Browse by Category Section -->
<div class="py-16 bg-gradient-to-b from-homize-white to-gray-50 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Explore Our Services
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Professional and reliable services at your fingertips
            </p>
        </div>

        <div class="mt-12 grid self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($kategori as $nav)
                <a href="{{ route('service', [$nav->id]) }}" 
                   class="group block rounded-3xl bg-homize-blue hover:bg-homize-blue-dark text-white transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                    <!-- Card content with icon on left, text on right -->
                    <div class="flex items-center p-6">
                        <!-- Icon Circle -->
                        <div class="bg-blue-100 rounded-full p-4 w-16 h-16 flex items-center justify-center mr-6 flex-shrink-0">
                            @switch($loop->iteration)
                                @case(1)
                                    <!-- Home Services Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                @break

                                @case(2)
                                    <!-- Technical Services Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                @break

                                @case(3)
                                    <!-- Education Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @break

                                @default
                                    <!-- Default Service Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                            @endswitch
                        </div>

                        <!-- Category Title and Subcategories -->
                        <div>
                            <h3 class="text-xl font-bold text-white">{{ $nav->nama }}</h3>
                            <div class="space-y-0.5 mt-1">
                                @foreach (collect($sub_kategori)->where('id_kategori', $nav->id)->take(3) as $sub)
                                    <p class="text-white text-sm opacity-90">
                                        {{ trim($sub->nama) }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
