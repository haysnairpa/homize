<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Kode Promo') }}
            </h2>
            <a href="{{ route('admin.promo.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.promo.store') }}" id="promo-form">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kode Promo -->
                            <div>
                                <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">Kode Promo *</label>
                                <input type="text" 
                                       name="kode" 
                                       id="kode" 
                                       value="{{ old('kode') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('kode') border-red-500 @enderror"
                                       placeholder="Contoh: DISKON50"
                                       required>
                                @error('kode')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Promo -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Promo *</label>
                                <input type="text" 
                                       name="nama" 
                                       id="nama" 
                                       value="{{ old('nama') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                                       placeholder="Contoh: Diskon Akhir Tahun"
                                       required>
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipe Diskon -->
                            <div>
                                <label for="tipe_diskon" class="block text-sm font-medium text-gray-700 mb-2">Tipe Diskon *</label>
                                <select name="tipe_diskon" 
                                        id="tipe_diskon" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tipe_diskon') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Tipe Diskon</option>
                                    <option value="percentage" {{ old('tipe_diskon') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                    <option value="fixed" {{ old('tipe_diskon') === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                </select>
                                @error('tipe_diskon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nilai Diskon -->
                            <div>
                                <label for="nilai_diskon" class="block text-sm font-medium text-gray-700 mb-2">Nilai Diskon *</label>
                                <div class="relative">
                                    <input type="number" 
                                           name="nilai_diskon" 
                                           id="nilai_diskon" 
                                           value="{{ old('nilai_diskon') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nilai_diskon') border-red-500 @enderror"
                                           placeholder="0"
                                           min="0"
                                           step="0.01"
                                           required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="discount-unit">%</span>
                                    </div>
                                </div>
                                @error('nilai_diskon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                                <input type="date" 
                                       name="tanggal_mulai" 
                                       id="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tanggal_mulai') border-red-500 @enderror"
                                       required>
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Berakhir -->
                            <div>
                                <label for="tanggal_berakhir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir *</label>
                                <input type="date" 
                                       name="tanggal_berakhir" 
                                       id="tanggal_berakhir" 
                                       value="{{ old('tanggal_berakhir') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tanggal_berakhir') border-red-500 @enderror"
                                       required>
                                @error('tanggal_berakhir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Batas Penggunaan Global -->
                            <div>
                                <label for="batas_penggunaan_global" class="block text-sm font-medium text-gray-700 mb-2">Batas Penggunaan Global</label>
                                <input type="number" 
                                       name="batas_penggunaan_global" 
                                       id="batas_penggunaan_global" 
                                       value="{{ old('batas_penggunaan_global') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('batas_penggunaan_global') border-red-500 @enderror"
                                       placeholder="Kosongkan untuk tidak terbatas"
                                       min="1">
                                <p class="mt-1 text-xs text-gray-500">Total maksimal penggunaan kode promo ini oleh semua user</p>
                                @error('batas_penggunaan_global')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Batas Penggunaan Per User -->
                            <div>
                                <label for="batas_penggunaan_per_user" class="block text-sm font-medium text-gray-700 mb-2">Batas Penggunaan Per User</label>
                                <input type="number" 
                                       name="batas_penggunaan_per_user" 
                                       id="batas_penggunaan_per_user" 
                                       value="{{ old('batas_penggunaan_per_user', 1) }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('batas_penggunaan_per_user') border-red-500 @enderror"
                                       placeholder="1"
                                       min="1">
                                <p class="mt-1 text-xs text-gray-500">Maksimal penggunaan per user (default: 1)</p>
                                @error('batas_penggunaan_per_user')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Target Section -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Target Promo</h3>
                            
                            <div class="space-y-4">
                                <!-- Target Type -->
                                <div>
                                    <label for="target_type" class="block text-sm font-medium text-gray-700 mb-2">Berlaku Untuk *</label>
                                    <select name="target_type" 
                                            id="target_type" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('target_type') border-red-500 @enderror"
                                            required>
                                        <option value="">Pilih Target</option>
                                        <option value="all" {{ old('target_type') === 'all' ? 'selected' : '' }}>Semua Layanan</option>
                                        <option value="category" {{ old('target_type') === 'category' ? 'selected' : '' }}>Kategori Tertentu</option>
                                        <option value="service" {{ old('target_type') === 'service' ? 'selected' : '' }}>Layanan Tertentu</option>
                                    </select>
                                    @error('target_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category Selection -->
                                <div id="category-selection" class="hidden">
                                    <label for="target_kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Kategori</label>
                                    <select name="target_kategori_id" 
                                            id="target_kategori_id" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('target_kategori_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_kategori_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Service Selection -->
                                <div id="service-selection" class="hidden">
                                    <label for="target_layanan_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Layanan</label>
                                    <select name="target_layanan_id" 
                                            id="target_layanan_id" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Layanan</option>
                                        <!-- Options will be populated via AJAX -->
                                    </select>
                                    @error('target_layanan_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Options Section -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Opsi Tambahan</h3>
                            
                            <div class="space-y-4">
                                <!-- Exclusive Promo -->
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_exclusive" 
                                           id="is_exclusive" 
                                           value="1"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ old('is_exclusive') ? 'checked' : '' }}>
                                    <label for="is_exclusive" class="ml-2 block text-sm text-gray-900">
                                        Promo Eksklusif
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 ml-6">Promo eksklusif tidak dapat digabung dengan promo lain</p>

                                <!-- Active Status -->
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="status_aktif" 
                                           id="status_aktif" 
                                           value="1"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ old('status_aktif', true) ? 'checked' : '' }}>
                                    <label for="status_aktif" class="ml-2 block text-sm text-gray-900">
                                        Aktifkan Promo
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 ml-6">Promo hanya dapat digunakan jika diaktifkan</p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.promo.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                                Simpan Kode Promo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipeDiskonSelect = document.getElementById('tipe_diskon');
            const discountUnit = document.getElementById('discount-unit');
            const targetTypeSelect = document.getElementById('target_type');
            const categorySelection = document.getElementById('category-selection');
            const serviceSelection = document.getElementById('service-selection');
            const targetKategoriSelect = document.getElementById('target_kategori_id');
            const targetLayananSelect = document.getElementById('target_layanan_id');

            // Update discount unit based on type
            tipeDiskonSelect.addEventListener('change', function() {
                if (this.value === 'percentage') {
                    discountUnit.textContent = '%';
                } else if (this.value === 'fixed') {
                    discountUnit.textContent = 'Rp';
                } else {
                    discountUnit.textContent = '';
                }
            });

            // Show/hide target selections
            targetTypeSelect.addEventListener('change', function() {
                categorySelection.classList.add('hidden');
                serviceSelection.classList.add('hidden');
                
                if (this.value === 'category') {
                    categorySelection.classList.remove('hidden');
                } else if (this.value === 'service') {
                    serviceSelection.classList.remove('hidden');
                }
            });

            // Load services when category is selected
            targetKategoriSelect.addEventListener('change', function() {
                const categoryId = this.value;
                if (categoryId) {
                    fetch(`/admin/promo/services-by-category/${categoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            targetLayananSelect.innerHTML = '<option value="">Pilih Layanan</option>';
                            data.services.forEach(service => {
                                const option = document.createElement('option');
                                option.value = service.id;
                                option.textContent = service.nama;
                                targetLayananSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error loading services:', error);
                        });
                }
            });

            // Initialize on page load
            if (tipeDiskonSelect.value) {
                tipeDiskonSelect.dispatchEvent(new Event('change'));
            }
            if (targetTypeSelect.value) {
                targetTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-admin-layout>