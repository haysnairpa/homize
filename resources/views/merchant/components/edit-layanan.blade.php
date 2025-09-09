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
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue sm:text-sm"
                        required>
                        <option value="kilogram"
                            {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->satuan == 'kilogram' ? 'selected' : '' }}>
                            Per Kilogram</option>
                        <option value="unit"
                            {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->satuan == 'unit' ? 'selected' : '' }}>
                            Per Unit</option>
                        <option value="pcs"
                            {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->satuan == 'pcs' ? 'selected' : '' }}>
                            Per Pieces (pcs)</option>
                        <option value="pertemuan"
                            {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->satuan == 'pertemuan' ? 'selected' : '' }}>
                            Per Pertemuan</option>
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
                            <option value="Menit"
                                {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->tipe_durasi == 'Menit' ? 'selected' : '' }}>
                                Menit</option>
                            <option value="Jam"
                                {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->tipe_durasi == 'Jam' ? 'selected' : '' }}>
                                Jam</option>
                            <option value="Hari"
                                {{ isset($editLayanan) && $editLayanan->tarif_layanan && $editLayanan->tarif_layanan->tipe_durasi == 'Hari' ? 'selected' : '' }}>
                                Hari</option>
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
    </div>
</form>