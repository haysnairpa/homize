<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Kode Promo') }}
            </h2>
            <a href="{{ route('admin.promo.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                Tambah Kode Promo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ $totalPromos }}</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Promo</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $totalPromos }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ $activePromos }}</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Aktif</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $activePromos }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ $expiredPromos }}</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Kedaluwarsa</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $expiredPromos }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ $exclusivePromos }}</span>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Eksklusif</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $exclusivePromos }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.promo.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Diskon</label>
                            <select name="tipe_diskon" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Tipe</option>
                                <option value="percentage" {{ request('tipe_diskon') === 'percentage' ? 'selected' : '' }}>Persentase</option>
                                <option value="fixed" {{ request('tipe_diskon') === 'fixed' ? 'selected' : '' }}>Nominal Tetap</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                            <select name="target_type" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Target</option>
                                <option value="all" {{ request('target_type') === 'all' ? 'selected' : '' }}>Semua Layanan</option>
                                <option value="category" {{ request('target_type') === 'category' ? 'selected' : '' }}>Kategori Tertentu</option>
                                <option value="service" {{ request('target_type') === 'service' ? 'selected' : '' }}>Layanan Tertentu</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Promo Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Diskon
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Target
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Periode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Penggunaan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($promos as $promo)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $promo->kode }}</div>
                                            @if($promo->is_exclusive)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Eksklusif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $promo->nama }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @if($promo->tipe_diskon === 'percentage')
                                                    {{ $promo->nilai_diskon }}%
                                                @else
                                                    Rp {{ number_format($promo->nilai_diskon, 0, ',', '.') }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @if($promo->target_type === 'all')
                                                    Semua Layanan
                                                @elseif($promo->target_type === 'category')
                                                    {{ $promo->targetKategori->nama ?? 'Kategori Tidak Ditemukan' }}
                                                @elseif($promo->target_type === 'service')
                                                    {{ $promo->targetLayanan->nama ?? 'Layanan Tidak Ditemukan' }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $promo->tanggal_mulai->format('d/m/Y') }} - 
                                                {{ $promo->tanggal_berakhir->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $promo->penggunaan_count ?? 0 }}
                                                @if($promo->batas_penggunaan_global)
                                                    / {{ $promo->batas_penggunaan_global }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($promo->status_aktif && $promo->tanggal_berakhir >= now())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @elseif($promo->tanggal_berakhir < now())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Kedaluwarsa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.promo.show', $promo->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900">Detail</a>
                                                <a href="{{ route('admin.promo.edit', $promo->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <button class="toggle-status-btn text-yellow-600 hover:text-yellow-900" 
                                                        data-promo-id="{{ $promo->id }}" 
                                                        data-current-status="{{ $promo->status_aktif }}">
                                                    {{ $promo->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                                <button class="delete-promo-btn text-red-600 hover:text-red-900" 
                                                        data-promo-id="{{ $promo->id }}">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada kode promo ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($promos->hasPages())
                        <div class="mt-6">
                            {{ $promos->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-promo-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-30">
        <div class="bg-white rounded-lg shadow-lg w-96 relative">
            <button id="close-delete-modal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
                <p class="mb-6">Apakah Anda yakin ingin menghapus kode promo ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex justify-end space-x-2">
                    <button id="cancel-delete" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Batal</button>
                    <button id="confirm-delete" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedPromoId = null;
        const deleteModal = document.getElementById('delete-promo-modal');
        const closeDeleteModal = document.getElementById('close-delete-modal');
        const cancelDelete = document.getElementById('cancel-delete');
        const confirmDelete = document.getElementById('confirm-delete');

        // Delete promo functionality
        document.querySelectorAll('.delete-promo-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                selectedPromoId = this.getAttribute('data-promo-id');
                deleteModal.classList.remove('hidden');
            });
        });

        // Close modal
        [closeDeleteModal, cancelDelete].forEach(btn => {
            btn.addEventListener('click', function () {
                deleteModal.classList.add('hidden');
                selectedPromoId = null;
            });
        });

        // Confirm deletion
        confirmDelete.addEventListener('click', function () {
            if (!selectedPromoId) return;
            
            fetch(`/admin/promo/${selectedPromoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menghapus kode promo.');
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan saat menghapus kode promo.');
            })
            .finally(() => {
                deleteModal.classList.add('hidden');
                selectedPromoId = null;
            });
        });

        // Toggle status functionality
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const promoId = this.getAttribute('data-promo-id');
                const currentStatus = this.getAttribute('data-current-status') === '1';
                
                fetch(`/admin/promo/${promoId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal mengubah status kode promo.');
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan saat mengubah status.');
                });
            });
        });
    </script>
</x-admin-layout>