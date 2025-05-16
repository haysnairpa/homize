<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Profil Merchant') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif

                <form action="{{ route('merchant.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Foto Profil -->
                        <div class="md:col-span-1">
                            <div class="flex flex-col items-center">
                                <div class="mb-4">
                                    <img src="{{ $merchant->profile_url }}" alt="{{ $merchant->nama_usaha }}" class="w-40 h-40 object-cover rounded-full border-4 border-homize-blue">
                                </div>
                                <div class="w-full">
                                    <label for="profile_url" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                                    <input type="file" name="profile_url" id="profile_url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue">
                                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG. Maks: 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Merchant -->
                        <div class="md:col-span-2">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Dasar</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="nama_usaha" class="block text-sm font-medium text-gray-700 mb-1">Nama Usaha</label>
                                            <input type="text" name="nama_usaha" id="nama_usaha" value="{{ $merchant->nama_usaha }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue">
                                        </div>
                                        <div>
                                            <label for="id_sub_kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                            <select name="id_sub_kategori" id="id_sub_kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue">
                                                @foreach($subKategori as $kategori)
                                                    <option value="{{ $kategori->id }}" {{ $merchant->id_kategori == $kategori->id ? 'selected' : '' }}>
                                                        {{ $kategori->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Alamat & Kontak</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                            <textarea name="alamat" id="alamat" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue">{{ $merchant->alamat }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Media Sosial</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                                            <div class="flex">
                                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">@</span>
                                                <input type="text" name="instagram" id="instagram" value="{{ $mediaSosial['instagram'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                                            <input type="text" name="facebook" id="facebook" value="{{ $mediaSosial['facebook'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue">
                                        </div>
                                        <div>
                                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">Whatsapp</label>
                                            <div class="flex flex-col">
                                                <div class="flex">
                                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">+62</span>
                                                    <input type="text" name="whatsapp" id="whatsapp" value="{{ $mediaSosial['whatsapp'] ?? '' }}" 
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue"
                                                        pattern="[0-9]{8,15}" 
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                                        title="Masukkan nomor WhatsApp yang valid (8-15 digit angka)">
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Hanya masukkan angka, contoh: 81234567890</p>
                                                @error('whatsapp')
                                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                                            <input type="text" name="website" id="website" value="{{ $mediaSosial['website'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-merchant-layout> 