<div class="form-card mb-6 bg-white">
    <div class="form-card-header">
        <h3 class="text-lg font-semibold text-gray-800">Alamat Layanan</h3>
    </div>
    <div class="form-card-body">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="form-label">Nama Depan</label>
                    <input type="text" id="first_name" name="first_name" required class="form-input"
                        placeholder="Masukkan nama depan">
                </div>
                <div>
                    <label for="last_name" class="form-label">Nama Belakang</label>
                    <input type="text" id="last_name" name="last_name" required class="form-input"
                        placeholder="Masukkan nama belakang">
                </div>
            </div>
            
            <div>
                <label for="country" class="form-label">Negara/Wilayah</label>
                <select id="country" name="country" required class="form-input">
                    <option value="Indonesia" selected>Indonesia</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Singapura">Singapura</option>
                    <option value="Thailand">Thailand</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="city" class="form-label">Kota</label>
                    <input type="text" id="city" name="city" required class="form-input"
                        placeholder="Masukkan kota">
                </div>
                <div>
                    <label for="province" class="form-label">Provinsi</label>
                    <select id="province" name="province" required class="form-input">
                        <option value="" selected disabled>Pilih provinsi</option>
                        <option value="Aceh">Aceh</option>
                        <option value="Sumatera Utara">Sumatera Utara</option>
                        <option value="Sumatera Barat">Sumatera Barat</option>
                        <option value="Riau">Riau</option>
                        <option value="Jambi">Jambi</option>
                        <option value="Sumatera Selatan">Sumatera Selatan</option>
                        <option value="Bengkulu">Bengkulu</option>
                        <option value="Lampung">Lampung</option>
                        <option value="Kepulauan Bangka Belitung">Kepulauan Bangka Belitung</option>
                        <option value="Kepulauan Riau">Kepulauan Riau</option>
                        <option value="DKI Jakarta">DKI Jakarta</option>
                        <option value="Jawa Barat">Jawa Barat</option>
                        <option value="Jawa Tengah">Jawa Tengah</option>
                        <option value="DI Yogyakarta">DI Yogyakarta</option>
                        <option value="Jawa Timur">Jawa Timur</option>
                        <option value="Banten">Banten</option>
                        <option value="Bali">Bali</option>
                        <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                        <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                        <option value="Kalimantan Barat">Kalimantan Barat</option>
                        <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                        <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                        <option value="Kalimantan Timur">Kalimantan Timur</option>
                        <option value="Kalimantan Utara">Kalimantan Utara</option>
                        <option value="Sulawesi Utara">Sulawesi Utara</option>
                        <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                        <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                        <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                        <option value="Gorontalo">Gorontalo</option>
                        <option value="Sulawesi Barat">Sulawesi Barat</option>
                        <option value="Maluku">Maluku</option>
                        <option value="Maluku Utara">Maluku Utara</option>
                        <option value="Papua">Papua</option>
                        <option value="Papua Barat">Papua Barat</option>
                    </select>
                </div>
                <div>
                    <label for="postal_code" class="form-label">Kode Pos</label>
                    <input type="text" id="postal_code" name="postal_code" required class="form-input"
                        placeholder="Masukkan kode pos">
                </div>
            </div>
            
            <div>
                <label for="address" class="form-label">Alamat</label>
                <input type="text" id="address" name="address" required class="form-input"
                    placeholder="Nama jalan dan nomor rumah">
            </div>

            <div>
                <button type="button" id="getLocationBtn" class="location-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    Gunakan Lokasi Saat Ini
                </button>
                <p id="locationStatus" class="mt-2 text-sm text-gray-500"></p>
            </div>

            <div id="mapContainer"
                class="h-64 bg-gray-100 rounded-lg overflow-hidden hidden animate-fade-in">
                <div id="map" class="w-full h-full"></div>
            </div>
            
            <!-- Hidden field for the complete address that will be sent to the backend -->
            <input type="hidden" id="alamat_pembeli" name="alamat_pembeli">
        </div>
    </div>
</div>
