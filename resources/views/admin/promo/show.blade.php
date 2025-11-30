<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Kode Promo') }}: {{ $promo->kode }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.promo.edit', $promo->id) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Edit
                </a>
                <a href="{{ route('admin.promo.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Promo Details Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kode Promo</label>
                                    <p class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $promo->kode }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Promo</label>
                                    <p class="text-sm text-gray-900">{{ $promo->nama }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <p class="text-sm text-gray-900">{{ $promo->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $promo->status_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $promo->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Discount Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Diskon</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipe Diskon</label>
                                    <p class="text-sm text-gray-900">
                                        {{ $promo->tipe_diskon === 'percentage' ? 'Persentase' : 'Nominal Tetap' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nilai Diskon</label>
                                    <p class="text-sm text-gray-900 font-semibold">
                                        @if($promo->tipe_diskon === 'percentage')
                                            {{ $promo->nilai_diskon }}%
                                        @else
                                            Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                                        @endif
                                    </p>
                                </div>
                                
                                @if($promo->minimum_pembelian)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Minimum Pembelian</label>
                                    <p class="text-sm text-gray-900">Rp {{ number_format($promo->minimum_pembelian, 0, ',', '.') }}</p>
                                </div>
                                @endif
                                
                                @if($promo->maksimum_diskon)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Maksimum Diskon</label>
                                    <p class="text-sm text-gray-900">Rp {{ number_format($promo->maksimum_diskon, 0, ',', '.') }}</p>
                                </div>
                                @endif
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Promo Eksklusif</label>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $promo->is_exclusive ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $promo->is_exclusive ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Target Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Target Promo</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipe Target</label>
                                    <p class="text-sm text-gray-900">
                                        @if($promo->target_type === 'all')
                                            Semua Layanan
                                        @elseif($promo->target_type === 'category')
                                            Kategori Tertentu
                                        @elseif($promo->target_type === 'service')
                                            Layanan Tertentu
                                        @endif
                                    </p>
                                </div>
                                
                                @if($promo->target_type !== 'all')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Target</label>
                                    <p class="text-sm text-gray-900">
                                        @if($promo->target_type === 'category')
                                            {{ $promo->targetKategori->nama ?? 'Kategori Tidak Ditemukan' }}
                                        @elseif($promo->target_type === 'service')
                                            {{ $promo->targetLayanan->nama ?? 'Layanan Tidak Ditemukan' }}
                                        @endif
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Usage & Date Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Penggunaan & Waktu</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Periode Berlaku</label>
                                    <p class="text-sm text-gray-900">
                                        {{ $promo->tanggal_mulai->format('d/m/Y') }} - {{ $promo->tanggal_berakhir->format('d/m/Y') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Batas Penggunaan Per User</label>
                                    <p class="text-sm text-gray-900">{{ $promo->batas_penggunaan_per_user }} kali</p>
                                </div>
                                
                                @if($promo->batas_penggunaan_global)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Batas Penggunaan Global</label>
                                    <p class="text-sm text-gray-900">{{ $promo->batas_penggunaan_global }} kali</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Usage Statistics Card -->
            @if(isset($stats))
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Penggunaan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $totalUsage ?? 0 }}</div>
                            <div class="text-sm text-blue-600">Total Penggunaan</div>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $uniqueUsers ?? 0 }}</div>
                            <div class="text-sm text-green-600">Pengguna Unik</div>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">
                                Rp {{ number_format($totalDiscount ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-purple-600">Total Diskon Diberikan</div>
                        </div>
                        
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ $promo->batas_penggunaan_global ? number_format((($totalUsage ?? 0) / $promo->batas_penggunaan_global) * 100, 1) : '∞' }}%
                            </div>
                            <div class="text-sm text-yellow-600">Penggunaan dari Batas</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>