<x-app-layout>
    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Merchant Info Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="relative h-48 bg-gradient-to-r from-homize-blue to-homize-blue-second">
                    <div class="absolute -bottom-12 left-8">
                        <div class="relative">
                            <img src="{{ Storage::url($merchant->profile_url) }}" 
                                 alt="{{ $merchant->nama_usaha }}" 
                                 class="w-32 h-32 rounded-xl border-4 border-white object-cover">
                            <button class="absolute bottom-2 right-2 p-2 bg-white rounded-full shadow-md hover:bg-gray-50">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-16 pb-8 px-8">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $merchant->nama_usaha }}</h1>
                            <p class="text-gray-600 mt-1">{{ $merchant->sub_kategori->nama }}</p>
                            <div class="flex items-center gap-6 mt-4">
                                <div class="flex items-center gap-2">
                                    <div class="p-2 bg-homize-blue/5 rounded-lg">
                                        <svg class="w-5 h-5 text-homize-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-600">{{ $merchant->alamat }}</span>
                                </div>
                                @php $sosmed = json_decode($merchant->media_sosial, true); @endphp
                                @if(isset($sosmed['whatsapp']))
                                <div class="flex items-center gap-2">
                                    <div class="p-2 bg-green-50 rounded-lg">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-600">+62{{ $sosmed['whatsapp'] }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-4 mt-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-homize-blue">{{ $merchant->layanan_merchant->count() }}</div>
                                <div class="text-sm text-gray-600">Layanan</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-homize-orange">-</div>
                                <div class="text-sm text-gray-600">Rating</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-500">-</div>
                                <div class="text-sm text-gray-600">Selesai</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Tab Navigation and Content -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" x-data="{ activeTab: 'manage' }">
                <!-- Header -->
                <div class="border-b border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900">Dashboard Merchant</h2>
                            <span class="px-4 py-2 bg-homize-blue/10 text-homize-blue rounded-full text-sm font-medium">
                                {{ $merchant->nama_usaha }}
                            </span>
                        </div>
                    </div>
            
                    <!-- Tab Navigation -->
                    <div class="px-6">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <button @click="activeTab = 'manage'" 
                                    :class="{'border-homize-blue text-homize-blue': activeTab === 'manage', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'manage'}" 
                                    class="py-4 px-1 border-b-2 font-medium text-sm">
                                Manage Layanan
                            </button>
                            <button @click="activeTab = 'add'" 
                                    :class="{'border-homize-blue text-homize-blue': activeTab === 'add', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'add'}" 
                                    class="py-4 px-1 border-b-2 font-medium text-sm">
                                Tambah Layanan Baru
                            </button>
                            <button @click="activeTab = 'ratings'" 
                                    :class="{'border-homize-blue text-homize-blue': activeTab === 'ratings', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'ratings'}" 
                                    class="py-4 px-1 border-b-2 font-medium text-sm">
                                Rating Layanan
                            </button>
                        </nav>
                    </div>
                </div>
            
                <!-- Tab Contents -->
                <div class="p-6">
                    <!-- Manage Layanan Tab -->
                    <div x-show="activeTab === 'manage'" x-cloak>
                        @include('merchant.components.manage-layanan')
                        {{-- <p>Soon~~</p> --}}
                    </div>
            
                    <!-- Add Layanan Tab -->
                    <div x-show="activeTab === 'add'" x-cloak>
                        @include('merchant.components.add-layanan')
                    </div>
            
                    <!-- Ratings Tab -->
                    <div x-show="activeTab === 'ratings'" x-cloak>
                        {{-- @include('merchant.components.rating-layanan') --}}
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Ratings akan segera hadir</h3>
                            <p class="mt-2 text-gray-500">Fitur rating layanan sedang dalam pengembangan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
