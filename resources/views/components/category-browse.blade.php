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
                        <!-- Icon Circle with white background -->
                        <div class="rounded-full p-4 w-16 h-16 flex items-center justify-center mr-6 flex-shrink-0 bg-white shadow-sm">
                            @switch($loop->iteration)
                                @case(1)
                                    <!-- Jasa Rumah Tangga Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22L12 3Z" />
                                    </svg>
                                @break

                                @case(2)
                                    <!-- Jasa Perbaikan & Instalasi Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.7 19L13.6 9.9C14.5 7.6 14 4.9 12.1 3C10.1 1 7.1 0.6 4.7 1.7L9 6L6 9L1.6 4.7C0.4 7.1 0.9 10.1 2.9 12.1C4.8 14 7.5 14.5 9.8 13.6L18.9 22.7C19.3 23.1 19.9 23.1 20.3 22.7L22.6 20.4C23.1 20 23.1 19.3 22.7 19Z" />
                                    </svg>
                                @break

                                @case(3)
                                    <!-- Jasa Pendidikan & Bimbingan Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 2L14 6.5V17.5L19 13V2M6.5 5C4.55 5 2.45 5.4 1 6.5V21.16C1 21.41 1.25 21.66 1.5 21.66C1.6 21.66 1.65 21.59 1.75 21.59C3.1 20.94 5.05 20.5 6.5 20.5C8.45 20.5 10.55 20.9 12 22C13.35 21.15 15.8 20.5 17.5 20.5C19.15 20.5 20.85 20.81 22.25 21.56C22.35 21.61 22.4 21.59 22.5 21.59C22.75 21.59 23 21.34 23 21.09V6.5C22.4 6.05 21.75 5.75 21 5.5V19C19.9 18.65 18.7 18.5 17.5 18.5C15.8 18.5 13.35 19.15 12 20V6.5C10.55 5.4 8.45 5 6.5 5Z" />
                                    </svg>
                                @break
                                
                                @case(4)
                                    <!-- Jasa Kesehatan & Kecantikan Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                                    </svg>
                                @break
                                
                                @case(5)
                                    <!-- Jasa Kreatif & Digital Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21,16H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10V20H8V22H16V20H14V18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z" />
                                    </svg>
                                @break
                                
                                @case(6)
                                    <!-- Jasa Event Organizer Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z" />
                                    </svg>
                                @break
                                
                                @case(7)
                                    <!-- Jasa Penyewaan Barang Icon -->
                                    <svg class="w-8 h-8 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17,18C15.89,18 15,18.89 15,20A2,2 0 0,0 17,22A2,2 0 0,0 19,20C19,18.89 18.1,18 17,18M1,2V4H3L6.6,11.59L5.24,14.04C5.09,14.32 5,14.65 5,15A2,2 0 0,0 7,17H19V15H7.42A0.25,0.25 0 0,1 7.17,14.75C7.17,14.7 7.18,14.66 7.2,14.63L8.1,13H15.55C16.3,13 16.96,12.58 17.3,11.97L20.88,5.5C20.95,5.34 21,5.17 21,5A1,1 0 0,0 20,4H5.21L4.27,2M7,18C5.89,18 5,18.89 5,20A2,2 0 0,0 7,22A2,2 0 0,0 9,20C9,18.89 8.1,18 7,18Z" />
                                    </svg>
                                @break

                                @default
                                    <!-- Default Service Icon -->
                                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M12,10.5A1.5,1.5 0 0,1 13.5,12A1.5,1.5 0 0,1 12,13.5A1.5,1.5 0 0,1 10.5,12A1.5,1.5 0 0,1 12,10.5M7.5,10.5A1.5,1.5 0 0,1 9,12A1.5,1.5 0 0,1 7.5,13.5A1.5,1.5 0 0,1 6,12A1.5,1.5 0 0,1 7.5,10.5M16.5,10.5A1.5,1.5 0 0,1 18,12A1.5,1.5 0 0,1 16.5,13.5A1.5,1.5 0 0,1 15,12A1.5,1.5 0 0,1 16.5,10.5Z" />
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
