<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Manajemen Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Layanan</h3>
                    <button id="addLayananBtn"
                        class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Layanan
                    </button>
                </div>

                @if (count($layanan) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Layanan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Durasi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jam Operasional</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($layanan as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($item->aset)
                                                        <img class="h-10 w-10 rounded-full object-contain"
                                                            src="{{ $item->aset->media_url }}"
                                                            alt="{{ $item->nama_layanan }}">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-homize-gray flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->nama_layanan }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ Str::limit($item->deskripsi_layanan, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->tarif_layanan)
                                                <div class="text-sm text-gray-900">Rp
                                                    {{ number_format($item->tarif_layanan->harga, 0, ',', '.') }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->tarif_layanan->satuan }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500">Belum ada tarif</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->tarif_layanan)
                                                <div class="text-sm text-gray-900">{{ $item->tarif_layanan->durasi }}
                                                    {{ $item->tarif_layanan->tipe_durasi }}</div>
                                            @else
                                                <div class="text-sm text-gray-500">-</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ \App\Helpers\HariHelper::formatHari($item->jam_operasional->hari->pluck('nama_hari')->implode(',')) }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $item->jam_operasional->jam_buka }} -
                                                {{ $item->jam_operasional->jam_tutup }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aktif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('layanan.detail', $item->id) }}"
                                                class="text-homize-blue hover:text-homize-blue-second mr-3">Lihat</a>
                                            <a href="#"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3 edit-layanan"
                                                data-id="{{ $item->id }}">Edit</a>
                                            <a href="#" class="text-red-600 hover:text-red-900 delete-layanan"
                                                data-id="{{ $item->id }}">Hapus</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mb-4">
                            <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum Ada Layanan</h3>
                        <p class="mt-1 text-gray-500">Mulai tambahkan layanan yang Anda tawarkan</p>
                        <div class="mt-6">
                            <button id="addFirstLayananBtn"
                                class="inline-flex items-center px-4 py-2 bg-homize-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-homize-blue-second focus:bg-homize-blue-second active:bg-homize-blue-second focus:outline-none focus:ring-2 focus:ring-homize-blue focus:ring-offset-2 transition ease-in-out duration-150">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Form tambah layanan -->
            @include('merchant.components.add-layanan')
        </div>
    </div>

    <!-- Modal Edit Layanan -->
    <div id="editLayananModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Edit Layanan</h3>
                <button id="closeEditModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form edit layanan -->
            <form id="editLayananForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <!-- Basic Information -->
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-2">Informasi Dasar</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="edit_nama_layanan" class="block text-sm font-medium text-gray-700">Nama
                                    Layanan</label>
                                <input type="text" name="nama_layanan" id="edit_nama_layanan"
                                    class="mt-1 focus:ring-homize-blue focus:border-homize-blue block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    required>
                            </div>
                            <div>
                                <label for="edit_deskripsi_layanan"
                                    class="block text-sm font-medium text-gray-700">Deskripsi Layanan</label>
                                <textarea name="deskripsi_layanan" id="edit_deskripsi_layanan" rows="3"
                                    class="mt-1 focus:ring-homize-blue focus:border-homize-blue block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    required></textarea>
                            </div>
                            <div>
                                <label for="edit_id_sub_kategori" class="block text-sm font-medium text-gray-700">Sub
                                    Kategori</label>
                                <select name="id_sub_kategori" id="edit_id_sub_kategori"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue sm:text-sm"
                                    required>
                                    @foreach ($subKategori as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="edit_pengalaman"
                                    class="block text-sm font-medium text-gray-700">Pengalaman (Tahun)</label>
                                <input type="number" name="pengalaman" id="edit_pengalaman" min="0"
                                    class="mt-1 focus:ring-homize-blue focus:border-homize-blue block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-2">Harga & Durasi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_harga" class="block text-sm font-medium text-gray-700">Harga</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="harga" id="edit_harga"
                                        class="focus:ring-homize-blue focus:border-homize-blue block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="0" required>
                                </div>
                            </div>
                            <div>
                                <label for="edit_satuan"
                                    class="block text-sm font-medium text-gray-700">Satuan</label>
                                <select name="satuan" id="edit_satuan"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue sm:text-sm">
                                    <option value="kg"
                                        {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->satuan == 'kg' ? 'selected' : '' }}>
                                        Per Kilogram (kg)</option>
                                    <option value="unit"
                                        {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->satuan == 'unit' ? 'selected' : '' }}>
                                        Per Unit</option>
                                    <option value="pcs"
                                        {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->satuan == 'pcs' ? 'selected' : '' }}>
                                        Per Pieces (pcs)</option>
                                    <option value="jam"
                                        {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->satuan == 'jam' ? 'selected' : '' }}>
                                        Per Jam</option>
                                </select>
                                <div>
                                    <label for="edit_durasi"
                                        class="block text-sm font-medium text-gray-700">Durasi</label>
                                    <input type="number" name="durasi" id="edit_durasi" min="1"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue sm:text-sm"
                                        required>
                                </div>
                                <div>
                                    <label for="edit_tipe_durasi" class="block text-sm font-medium text-gray-700">Tipe
                                        Durasi</label>
                                    <select name="tipe_durasi" id="edit_tipe_durasi"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue sm:text-sm"
                                        required>
                                        <option value="Jam"
                                            {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->tipe_durasi == 'Jam' ? 'selected' : '' }}>
                                            Jam</option>
                                        <option value="Hari"
                                            {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->tipe_durasi == 'Hari' ? 'selected' : '' }}>
                                            Hari</option>
                                        <option value="Pertemuan"
                                            {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->tipe_durasi == 'Pertemuan' ? 'selected' : '' }}>
                                            Pertemuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Jam Operasional -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-2">Jam Operasional</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="edit_jam_buka" class="block text-sm font-medium text-gray-700">Jam
                                        Buka</label>
                                    <input type="time" name="jam_operasional[jam_buka]" id="edit_jam_buka"
                                        class="mt-1 focus:ring-homize-blue focus:border-homize-blue block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                </div>
                                <div>
                                    <label for="edit_jam_tutup" class="block text-sm font-medium text-gray-700">Jam
                                        Tutup</label>
                                    <input type="time" name="jam_operasional[jam_tutup]" id="edit_jam_tutup"
                                        class="mt-1 focus:ring-homize-blue focus:border-homize-blue block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hari Operasional</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jam_operasional[hari][]" value="1"
                                            id="edit_hari_senin"
                                            class="h-4 w-4 text-homize-blue focus:ring-homize-blue border-gray-300 rounded">
                                        <label for="edit_hari_senin"
                                            class="ml-2 block text-sm text-gray-700">Senin</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jam_operasional[hari][]" value="2"
                                            id="edit_hari_selasa"
                                            class="h-4 w-4 text-homize-blue focus:ring-homize-blue border-gray-300 rounded">
                                        <label for="edit_hari_selasa"
                                            class="ml-2 block text-sm text-gray-700">Selasa</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jam_operasional[hari][]" value="3"
                                            id="edit_hari_rabu"
                                            class="h-4 w-4 text-homize-blue focus:ring-homize-blue border-gray-300 rounded">
                                        <label for="edit_hari_rabu"
                                            class="ml-2 block text-sm text-gray-700">Rabu</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jam_operasional[hari][]" value="4"
                                            id="edit_hari_kamis"
                                            class="h-4 w-4 text-homize-blue focus:ring-homize-blue border-gray-300 rounded">
                                        <label for="edit_hari_kamis"
                                            class="ml-2 block text-sm text-gray-700">Kamis</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jam_operasional[hari][]" value="5"
                                            id="edit_hari_jumat"
                                            class="h-4 w-4 text-homize-blue focus:ring-homize-blue border-gray-300 rounded">
                                        <label for="edit_hari_jumat"
                                            class="ml-2 block text-sm text-gray-700">Jumat</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jam_operasional[hari][]" value="6"
                                            id="edit_hari_sabtu"
                                            class="h-4 w-4 text-homize-blue focus:ring-homize-blue border-gray-300 rounded">
                                        <label for="edit_hari_sabtu"
                                            class="ml-2 block text-sm text-gray-700">Sabtu</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jam_operasional[hari][]" value="7"
                                            id="edit_hari_minggu"
                                            class="h-4 w-4 text-homize-blue focus:ring-homize-blue border-gray-300 rounded">
                                        <label for="edit_hari_minggu"
                                            class="ml-2 block text-sm text-gray-700">Minggu</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sertifikasi -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-2">Sertifikasi</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="edit_nama_sertifikasi"
                                        class="block text-sm font-medium text-gray-700">Nama Sertifikasi</label>
                                    <input type="text" name="nama_sertifikasi" id="edit_nama_sertifikasi"
                                        class="mt-1 focus:ring-homize-blue focus:border-homize-blue block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="edit_file_sertifikasi"
                                        class="block text-sm font-medium text-gray-700">File Sertifikasi
                                        (Opsional)</label>
                                    <input type="file" name="file_sertifikasi" id="edit_file_sertifikasi"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-homize-blue file:text-white hover:file:bg-homize-blue-second">
                                    <p class="mt-1 text-sm text-gray-500" id="edit_current_sertifikasi"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Gambar -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-2">Gambar Layanan</h4>
                            <div>
                                <label for="edit_aset_layanan" class="block text-sm font-medium text-gray-700">Upload
                                    Gambar (Opsional)</label>
                                <input type="file" name="aset_layanan[]" id="edit_aset_layanan" multiple
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-homize-blue file:text-white hover:file:bg-homize-blue-second">
                                <p class="mt-1 text-sm text-gray-500">Upload gambar baru akan menggantikan gambar yang
                                    ada</p>
                                <div id="edit_current_image" class="mt-2"></div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="button" id="cancelEditBtn"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-homize-blue mr-2">Batal</button>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-homize-blue hover:bg-homize-blue-second focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-homize-blue">Simpan
                                Perubahan</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

    <!-- Konfirmasi Delete Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus layanan ini? Tindakan ini tidak
                        dapat dibatalkan.</p>
                </div>
                <div class="flex justify-center gap-4 px-4 py-3">
                    <button id="cancelDeleteBtn"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">Batal</button>
                    <button id="confirmDeleteBtn"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addModal = document.getElementById('layananModal');
            const editModal = document.getElementById('editLayananModal');
            const deleteModal = document.getElementById('deleteConfirmModal');
            const addBtn = document.getElementById('addLayananBtn');
            const addFirstBtn = document.getElementById('addFirstLayananBtn');
            const closeAddBtn = document.getElementById('closeModal');
            const closeEditBtn = document.getElementById('closeEditModal');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const editLayananForm = document.getElementById('editLayananForm');

            let currentLayananId = null;

            function openAddModal() {
                addModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeAddModal() {
                addModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function openEditModal() {
                editModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                editModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function openDeleteModal() {
                deleteModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeDeleteModal() {
                deleteModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            if (addBtn) addBtn.addEventListener('click', openAddModal);
            if (addFirstBtn) addFirstBtn.addEventListener('click', openAddModal);
            closeAddBtn.addEventListener('click', closeAddModal);
            closeEditBtn.addEventListener('click', closeEditModal);
            cancelEditBtn.addEventListener('click', closeEditModal);
            cancelDeleteBtn.addEventListener('click', closeDeleteModal);

            // Close modals when clicking outside
            addModal.addEventListener('click', function(e) {
                if (e.target === addModal) {
                    closeAddModal();
                }
            });

            editModal.addEventListener('click', function(e) {
                if (e.target === editModal) {
                    closeEditModal();
                }
            });

            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });

            // Add event listeners for edit buttons
            document.querySelectorAll('.edit-layanan').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const layananId = this.getAttribute('data-id');
                    currentLayananId = layananId;
                    fetchLayananDetails(layananId);
                });
            });

            // Add event listeners for delete buttons
            document.querySelectorAll('.delete-layanan').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const layananId = this.getAttribute('data-id');
                    currentLayananId = layananId;
                    openDeleteModal();
                });
            });

            // Fetch layanan details for editing
            function fetchLayananDetails(id) {
                fetch(`/merchant/layanan/${id}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            populateEditForm(data);
                            openEditModal();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengambil data layanan.');
                    });
            }

            // Populate edit form with layanan data
            function populateEditForm(data) {
                const layanan = data.layanan;
                const selectedHari = data.selectedHari;

                // Set form action
                editLayananForm.action = `/merchant/layanan/${layanan.id}`;

                // Basic information
                document.getElementById('edit_nama_layanan').value = layanan.nama_layanan;
                document.getElementById('edit_deskripsi_layanan').value = layanan.deskripsi_layanan;
                document.getElementById('edit_id_sub_kategori').value = layanan.id_sub_kategori;
                document.getElementById('edit_pengalaman').value = layanan.pengalaman || 0;

                // Pricing
                if (layanan.tarif_layanan) {
                    document.getElementById('edit_harga').value = layanan.tarif_layanan.harga;
                    document.getElementById('edit_satuan').value = layanan.tarif_layanan.satuan;
                    document.getElementById('edit_durasi').value = layanan.tarif_layanan.durasi;
                    document.getElementById('edit_tipe_durasi').value = layanan.tarif_layanan.tipe_durasi;
                }

                // Jam operasional
                if (layanan.jam_operasional) {
                    document.getElementById('edit_jam_buka').value = layanan.jam_operasional.jam_buka;
                    document.getElementById('edit_jam_tutup').value = layanan.jam_operasional.jam_tutup;

                    // Reset all checkboxes first
                    document.querySelectorAll('input[name="jam_operasional[hari][]"]').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Check the appropriate days
                    selectedHari.forEach(hariId => {
                        const checkbox = document.querySelector(
                            `input[name="jam_operasional[hari][]"][value="${hariId}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                }

                // Sertifikasi
                if (layanan.sertifikasi && layanan.sertifikasi.length > 0) {
                    document.getElementById('edit_nama_sertifikasi').value = layanan.sertifikasi[0]
                        .nama_sertifikasi || '';
                    if (layanan.sertifikasi[0].media_url) {
                        document.getElementById('edit_current_sertifikasi').textContent = 'Sertifikasi saat ini: ' +
                            layanan.sertifikasi[0].nama_sertifikasi;
                    }
                }

                // Current image
                const currentImageDiv = document.getElementById('edit_current_image');
                currentImageDiv.innerHTML = '';
                if (layanan.aset && layanan.aset.media_url) {
                    const img = document.createElement('img');
                    img.src = `${layanan.aset.media_url}`;
                    img.alt = layanan.nama_layanan;
                    img.className = 'h-24 w-24 object-cover rounded-md';
                    currentImageDiv.appendChild(img);
                }
            }

            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                if (currentLayananId) {
                    deleteLayanan(currentLayananId);
                }
            });

            // Delete layanan
            function deleteLayanan(id) {
                fetch(`/merchant/layanan/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        closeDeleteModal();
                        if (data.success) {
                            // Show success message and reload page
                            alert('Layanan berhasil dihapus');
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus layanan.');
                        closeDeleteModal();
                    });
            }
        });
    </script>
</x-merchant-layout>
