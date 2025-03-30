<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Manajemen Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Layanan</h3>
                    <button id="addLayananBtn" class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Layanan
                    </button>
                </div>

                @if(count($layanan) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Operasional</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($layanan as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($item->aset)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $item->aset->media_url) }}" alt="{{ $item->nama_layanan }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-homize-gray flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->nama_layanan }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($item->deskripsi_layanan, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->tarif_layanan)
                                        <div class="text-sm text-gray-900">Rp {{ number_format($item->tarif_layanan->harga, 0, ',', '.') }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->tarif_layanan->satuan }}</div>
                                    @else
                                        <div class="text-sm text-gray-500">Belum ada tarif</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->tarif_layanan)
                                        <div class="text-sm text-gray-900">{{ $item->tarif_layanan->durasi }} {{ $item->tarif_layanan->tipe_durasi }}</div>
                                    @else
                                        <div class="text-sm text-gray-500">-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \App\Helpers\HariHelper::formatHari($item->jam_operasional->hari->pluck('nama_hari')->implode(',')) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $item->jam_operasional->jam_buka }} - {{ $item->jam_operasional->jam_tutup }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('layanan.detail', $item->id) }}" class="text-homize-blue hover:text-homize-blue-second mr-3">Lihat</a>
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3 edit-layanan" data-id="{{ $item->id }}">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-900 delete-layanan" data-id="{{ $item->id }}">Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="mb-4">
                        <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum Ada Layanan</h3>
                    <p class="mt-1 text-gray-500">Mulai tambahkan layanan yang Anda tawarkan</p>
                    <div class="mt-6">
                        <button id="addFirstLayananBtn" class="inline-flex items-center px-4 py-2 bg-homize-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-homize-blue-second focus:bg-homize-blue-second active:bg-homize-blue-second focus:outline-none focus:ring-2 focus:ring-homize-blue focus:ring-offset-2 transition ease-in-out duration-150">
                            Tambah Layanan
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Tambah Layanan -->
    <div id="layananModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Layanan Baru</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Form tambah layanan -->
            @include('merchant.components.add-layanan')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('layananModal');
            const addBtn = document.getElementById('addLayananBtn');
            const addFirstBtn = document.getElementById('addFirstLayananBtn');
            const closeBtn = document.getElementById('closeModal');

            function openModal() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            if (addBtn) addBtn.addEventListener('click', openModal);
            if (addFirstBtn) addFirstBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
</x-merchant-layout> 