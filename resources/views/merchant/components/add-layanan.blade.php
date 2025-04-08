<div class="container mx-auto py-8 px-4 max-w-4xl">
    <form id="layananForm" method="POST" action="{{ route('merchant.layanan.store') }}" enctype="multipart/form-data">
        @csrf
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        <div class="space-y-8">
            <!-- Header -->
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-800">Tambah Layanan Baru</h2>
                <p class="text-gray-500 mt-2">Lengkapi informasi layanan yang akan Anda tawarkan kepada pelanggan</p>
            </div>

            <!-- Notifikasi error -->
            <div id="errorMessages"
                class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc list-inside"></ul>
            </div>

            <!-- Tambahkan setelah div header -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Informasi Layanan -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-transparent">
                    <div class="flex items-center space-x-2">
                        <div class="bg-primary/10 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Informasi Layanan</h3>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 ml-9">
                        Masukkan detail informasi layanan yang akan Anda tawarkan
                    </p>
                </div>
                <div class="p-6 space-y-5">
                    <div class="space-y-2">
                        <label for="nama_layanan" class="block text-sm font-medium text-gray-700">Nama Layanan <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="nama_layanan" name="nama_layanan" placeholder="Masukkan nama layanan"
                            required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <p class="text-xs text-gray-500 mt-1">Contoh: Jasa Desain Logo, Konsultasi Bisnis, dll.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="id_sub_kategori" class="block text-sm font-medium text-gray-700">Sub Kategori <span
                                class="text-red-500">*</span></label>
                        <select id="id_sub_kategori" name="id_sub_kategori" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="" disabled selected>Pilih Sub Kategori</option>
                            @foreach($subKategori as $subKat)
                                <option value="{{ $subKat->id }}">{{ $subKat->nama }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pilih sub kategori yang sesuai dengan layanan Anda</p>
                    </div>

                    <div class="space-y-2">
                        <label for="deskripsi_layanan" class="block text-sm font-medium text-gray-700">Deskripsi <span
                                class="text-red-500">*</span></label>
                        <textarea id="deskripsi_layanan" name="deskripsi_layanan" rows="4"
                            placeholder="Jelaskan detail layanan yang Anda tawarkan" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Deskripsikan layanan Anda secara detail agar pelanggan
                            memahami apa yang Anda tawarkan.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="pengalaman" class="block text-sm font-medium text-gray-700">Pengalaman
                            (tahun)</label>
                        <div class="relative">
                            <input type="number" id="pengalaman" name="pengalaman" min="0"
                                placeholder="Berapa tahun pengalaman Anda"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500">tahun</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Harga & Durasi -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-transparent">
                    <div class="flex items-center space-x-2">
                        <div class="bg-primary/10 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Harga & Durasi</h3>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 ml-9">
                        Tentukan harga dan durasi layanan Anda
                    </p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label for="harga" class="block text-sm font-medium text-gray-700">Harga <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" id="harga" name="harga" min="0"
                                    placeholder="Masukkan harga layanan" required
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan <span
                                    class="text-red-500">*</span></label>
                            <select name="satuan"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-homize-blue focus:ring-homize-blue">
                                <option value="kg">Kilogram (kg)</option>
                                <option value="unit">Unit</option>
                                <option value="pcs">Pieces (pcs)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="durasi" class="block text-sm font-medium text-gray-700">Durasi <span
                                    class="text-red-500">*</span></label>
                            <input type="number" id="durasi" name="durasi" min="0"
                                placeholder="Masukkan durasi layanan" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="tipe_durasi" class="block text-sm font-medium text-gray-700">Tipe Durasi <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="tipe_durasi" name="tipe_durasi" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none bg-white">
                                    <option value="" disabled selected>Pilih tipe durasi</option>
                                    <option value="Jam">Jam</option>
                                    <option value="Hari">Hari</option>
                                    <option value="Pertemuan">Pertemuan</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revisi Layanan -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md mt-8">
                <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-transparent">
                    <div class="flex items-center space-x-2">
                        <div class="bg-primary/10 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Revisi Layanan</h3>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 ml-9">
                        Tentukan harga dan durasi revisi layanan Anda (opsional)
                    </p>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="flex items-center mb-4">
                            <input id="enable_revisi" type="checkbox"
                                class="w-4 h-4 text-homize-blue bg-gray-100 border-gray-300 rounded focus:ring-homize-blue">
                            <label for="enable_revisi" class="ml-2 text-sm font-medium text-gray-700">Aktifkan opsi
                                revisi untuk layanan ini</label>
                        </div>
                    </div>

                    <div id="revisi_fields" class="grid grid-cols-1 md:grid-cols-2 gap-5 hidden">
                        <div class="space-y-2">
                            <label for="revisi_harga" class="block text-sm font-medium text-gray-700">Harga
                                Revisi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" id="revisi_harga" name="revisi_harga" min="0"
                                    placeholder="Masukkan harga revisi"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="revisi_durasi" class="block text-sm font-medium text-gray-700">Durasi
                                Revisi</label>
                            <input type="number" id="revisi_durasi" name="revisi_durasi" min="0"
                                placeholder="Masukkan durasi revisi"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="revisi_tipe_durasi" class="block text-sm font-medium text-gray-700">Tipe
                                Durasi Revisi</label>
                            <div class="relative">
                                <select id="revisi_tipe_durasi" name="revisi_tipe_durasi"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none bg-white">
                                    <option value="" disabled selected>Pilih tipe durasi</option>
                                    <option value="Jam">Jam</option>
                                    <option value="Hari">Hari</option>
                                    <option value="Pertemuan">Pertemuan</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-transparent">
                    <div class="flex items-center space-x-2">
                        <div class="bg-primary/10 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Jam Operasional</h3>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 ml-9">
                        Tentukan jam operasional layanan Anda
                    </p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label for="jam_buka" class="block text-sm font-medium text-gray-700">Jam Buka <span
                                    class="text-red-500">*</span></label>
                            <input type="time" id="jam_buka" name="jam_operasional[jam_buka]" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="jam_tutup" class="block text-sm font-medium text-gray-700">Jam Tutup <span
                                    class="text-red-500">*</span></label>
                            <input type="time" id="jam_tutup" name="jam_operasional[jam_tutup]" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="hari" class="block text-sm font-medium text-gray-700">Hari Operasional <span
                                class="text-red-500">*</span></label>
                        <select id="hari" name="jam_operasional[hari][]" required multiple
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="1">Senin</option>
                            <option value="2">Selasa</option>
                            <option value="3">Rabu</option>
                            <option value="4">Kamis</option>
                            <option value="5">Jumat</option>
                            <option value="6">Sabtu</option>
                            <option value="7">Minggu</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Tekan Ctrl/Cmd untuk memilih beberapa hari</p>
                    </div>
                </div>
            </div>

            <!-- Media & Sertifikasi -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-transparent">
                    <div class="flex items-center space-x-2">
                        <div class="bg-primary/10 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Media & Sertifikasi</h3>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 ml-9">
                        Unggah foto layanan dan sertifikasi yang Anda miliki
                    </p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="space-y-3">
                        <label for="aset_layanan" class="block text-sm font-medium text-gray-700">Foto Layanan <span
                                class="text-red-500">*</span></label>
                        <div id="dropzone"
                            class="border-2 border-dashed border-gray-200 rounded-lg p-8 text-center hover:bg-gray-50 transition-colors cursor-pointer group">
                            <input id="aset_layanan" name="aset_layanan[]" type="file" multiple accept="image/*"
                                class="hidden" required>
                            <label for="aset_layanan" class="cursor-pointer">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="p-3 bg-primary/10 rounded-full group-hover:bg-primary/20 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-primary"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                            <polyline points="17 8 12 3 7 8" />
                                            <line x1="12" y1="3" x2="12" y2="15" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Klik untuk mengunggah foto
                                            layanan</span>
                                        <p class="text-xs text-gray-500 mt-1">atau seret dan lepas file di sini</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, atau JPEG (maks. 5MB)</p>
                                </div>
                            </label>
                        </div>
                        <div id="preview-container"
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-3 hidden">
                            <!-- Preview images will be displayed here -->
                        </div>
                    </div>

                    <div class="border-t border-gray-100 my-4"></div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sertifikasi</label>
                                <p class="text-xs text-gray-500 mt-1">Tambahkan sertifikasi untuk meningkatkan
                                    kredibilitas Anda</p>
                            </div>
                            <button type="button" id="add-sertifikasi"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                <span>Tambah Sertifikasi</span>
                            </button>
                        </div>

                        <div id="sertifikasi-container" class="space-y-4">
                            <div class="p-5 border rounded-lg bg-gray-50/50 hover:bg-white transition-colors">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-sm font-medium flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                            <polyline points="17 21 17 13 7 13 7 21" />
                                            <polyline points="7 3 7 8 15 8" />
                                        </svg>
                                        Sertifikasi #1
                                    </h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label for="sertifikasi_nama_0"
                                            class="block text-sm font-medium text-gray-700">Nama Sertifikasi</label>
                                        <input type="text" id="sertifikasi_nama_0" name="nama_sertifikasi"
                                            placeholder="Masukkan nama sertifikasi"
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                    </div>
                                    <div class="space-y-2">
                                        <label for="sertifikasi_file_0"
                                            class="block text-sm font-medium text-gray-700">File Sertifikasi</label>
                                        <input type="file" id="sertifikasi_file_0" name="file_sertifikasi"
                                            accept="image/*"
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between gap-4">
                <a href=""
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Simpan Layanan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Sertifikasi management
    let sertifikasiCount = 1;

    document.getElementById('add-sertifikasi').addEventListener('click', function() {
        const container = document.getElementById('sertifikasi-container');
        const div = document.createElement('div');
        div.className = 'p-5 border rounded-lg bg-gray-50/50 hover:bg-white transition-colors';
        div.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-sm font-medium flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Sertifikasi #${sertifikasiCount + 1}
            </h4>
            <button type="button" class="text-red-500 hover:text-red-700 transition-colors p-1.5 hover:bg-red-50 rounded-full" onclick="removeSertifikasi(this)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label for="sertifikasi_nama_${sertifikasiCount}" class="block text-sm font-medium text-gray-700">Nama Sertifikasi</label>
                <input type="text" id="sertifikasi_nama_${sertifikasiCount}" name="sertifikasi[${sertifikasiCount}][nama]" placeholder="Masukkan nama sertifikasi"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            <div class="space-y-2">
                <label for="sertifikasi_file_${sertifikasiCount}" class="block text-sm font-medium text-gray-700">File Sertifikasi</label>
                <input type="file" id="sertifikasi_file_${sertifikasiCount}" name="sertifikasi[${sertifikasiCount}][file]" accept="image/*"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
        </div>
    `;
        container.appendChild(div);
        sertifikasiCount++;
    });

    function removeSertifikasi(button) {
        const sertifikasiItem = button.closest('.p-5');

        // Add a simple fade-out animation
        sertifikasiItem.style.transition = 'all 0.3s ease';
        sertifikasiItem.style.opacity = '0';
        sertifikasiItem.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            sertifikasiItem.remove();
        }, 300);
    }

    // Image preview functionality
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('aset_layanan');
    const previewContainer = document.getElementById('preview-container');

    // Handle drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropzone.classList.add('border-primary', 'bg-primary/5');
    }

    function unhighlight() {
        dropzone.classList.remove('border-primary', 'bg-primary/5');
    }

    // Handle dropped files
    dropzone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFiles(files);
    }

    // Handle selected files
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        previewContainer.innerHTML = '';
        previewContainer.classList.remove('hidden');

        if (files.length > 0) {
            Array.from(files).forEach(file => {
                if (!file.type.match('image.*')) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                    <div class="aspect-square rounded-lg overflow-hidden border border-gray-200">
                        <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                        <span class="text-white text-xs truncate max-w-[90%] px-2">${file.name}</span>
                    </div>
                `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    document.getElementById('layananForm').addEventListener('submit', function(e) {
        const errorDiv = document.getElementById('errorMessages');
        const errorList = errorDiv.querySelector('ul');
        errorList.innerHTML = '';
        let hasError = false;

        // Validasi hari
        if (!document.querySelector('select[name="jam_operasional[hari]"]').value) {
            errorList.innerHTML += '<li>Hari operasional harus dipilih</li>';
            hasError = true;
        }

        // Validasi jam
        if (!document.querySelector('input[name="jam_operasional[jam_buka]"]').value) {
            errorList.innerHTML += '<li>Jam buka harus diisi</li>';
            hasError = true;
        }
        if (!document.querySelector('input[name="jam_operasional[jam_tutup]"]').value) {
            errorList.innerHTML += '<li>Jam tutup harus diisi</li>';
            hasError = true;
        }

        // Validasi satuan
        const satuan = document.querySelector('select[name="satuan"]').value;
        if (!['kg', 'unit', 'pcs'].includes(satuan)) {
            errorList.innerHTML += '<li>Satuan harus berupa kg, unit, atau pcs</li>';
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            errorDiv.classList.remove('hidden');
            window.scrollTo(0, 0);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const enableRevisiCheckbox = document.getElementById('enable_revisi');
        const revisiFields = document.getElementById('revisi_fields');

        enableRevisiCheckbox.addEventListener('change', function() {
            if (this.checked) {
                revisiFields.classList.remove('hidden');
                // Tambahkan required attribute ke field revisi
                document.getElementById('revisi_harga').setAttribute('required', '');
                document.getElementById('revisi_durasi').setAttribute('required', '');
                document.getElementById('revisi_tipe_durasi').setAttribute('required', '');
            } else {
                revisiFields.classList.add('hidden');
                // Hapus required attribute dari field revisi
                document.getElementById('revisi_harga').removeAttribute('required');
                document.getElementById('revisi_durasi').removeAttribute('required');
                document.getElementById('revisi_tipe_durasi').removeAttribute('required');
            }
        });
    });
</script>
