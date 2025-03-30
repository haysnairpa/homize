<x-app-layout>
    <div class="min-h-screen bg-homize-gray py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 top-1/2 h-0.5 bg-homize-blue/20 w-full -translate-y-1/2 z-0"></div>
                    <div class="relative z-10 flex items-center gap-3 bg-white">
                        <div class="w-8 h-8 rounded-full bg-homize-blue text-white flex items-center justify-center">1</div>
                        <span class="font-medium text-homize-blue">Informasi Dasar</span>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 bg-white">
                        <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center">2</div>
                        <span class="font-medium text-gray-400">Kontak & Lokasi</span>
                    </div>
                </div>
            </div>

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <form id="step1Form" action="{{ route('merchant.register.step1.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4">Foto Profil Usaha <span class="text-red-500">*</span></label>
                                <div id="imagePreviewContainer" class="mb-4 {{ isset($oldData['profile_url']) ? '' : 'hidden' }}">
                                    @if(isset($oldData['profile_url']))
                                    <img src="{{ asset('storage/' . $oldData['profile_url']) }}" alt="Preview" class="w-40 h-40 object-cover rounded-lg border-2 border-homize-blue">
                                    @else
                                    <img id="imagePreview" src="#" alt="Preview" class="w-40 h-40 object-cover rounded-lg border-2 border-homize-blue">
                                    @endif
                                </div>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-homize-blue transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="profile_url" class="relative cursor-pointer bg-white rounded-md font-medium text-homize-blue hover:text-homize-blue-second">
                                                <span>Upload foto</span>
                                                <input id="profile_url" name="profile_url" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG hingga 2MB</p>
                                    </div>
                                </div>
                                @error('profile_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Usaha <span class="text-red-500">*</span></label>
                                    <input 
                                        type="text" 
                                        name="nama_usaha" 
                                        id="nama_usaha"
                                        value="{{ old('nama_usaha', $oldData['nama_usaha'] ?? '') }}"
                                        class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-homize-blue focus:ring-homize-blue transition duration-200 ease-in-out hover:border-gray-400 placeholder-gray-400 focus:placeholder-gray-500 px-4 py-2" 
                                        placeholder="Contoh: Jasa Bersih Express"
                                    >
                                    @error('nama_usaha')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Usaha <span class="text-red-500">*</span></label>
                                    <select 
                                        name="id_kategori" 
                                        id="id_kategori"
                                        class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-homize-blue focus:ring-homize-blue transition duration-200 ease-in-out hover:border-gray-400 placeholder-gray-400 focus:placeholder-gray-500 px-4 py-2"
                                    >
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategori as $kat)
                                            <option value="{{ $kat->id }}" {{ old('id_kategori', $oldData['id_kategori'] ?? '') == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button 
                                type="submit" 
                                id="submitBtn"
                                class="inline-flex items-center px-6 py-3 bg-homize-blue text-white rounded-lg hover:bg-homize-blue-second transition-colors text-base font-medium gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled
                            >
                                Lanjutkan
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileInput = document.getElementById('profile_url');
            const namaUsahaInput = document.getElementById('nama_usaha');
            const kategoriInput = document.getElementById('id_kategori');
            const submitBtn = document.getElementById('submitBtn');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            
            // Fungsi untuk validasi form
            function validateForm() {
                const profileValid = profileInput.files.length > 0 || {{ isset($oldData['profile_url']) ? 'true' : 'false' }};
                const namaUsahaValid = namaUsahaInput.value.trim() !== '';
                const kategoriValid = kategoriInput.value !== '';
                
                submitBtn.disabled = !(profileValid && namaUsahaValid && kategoriValid);
            }
            
            // Cek validasi saat halaman dimuat
            validateForm();
            
            // Tambahkan event listener untuk input
            profileInput.addEventListener('change', function(e) {
                validateForm();
                
                // Preview gambar
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.classList.remove('hidden');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            namaUsahaInput.addEventListener('input', validateForm);
            kategoriInput.addEventListener('change', validateForm);
        });
    </script>
</x-app-layout> 