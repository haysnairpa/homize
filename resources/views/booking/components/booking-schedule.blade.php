<div class="form-card mb-6 bg-white">
    <div class="form-card-header">
        <h3 class="text-lg font-semibold text-gray-800">Jadwal Booking</h3>
    </div>
    <div class="form-card-body">
        <div class="space-y-4">
            <div>
                <label for="tanggal_booking" class="form-label">Tanggal & Waktu
                    Booking</label>
                <input type="datetime-local" id="tanggal_booking" name="tanggal_booking"
                    value="{{ $tanggalMulai->format('Y-m-d\\TH:i') }}" class="form-input">
            </div>

            <div>
                <h5 class="text-sm font-medium text-gray-500 mb-1">Estimasi Selesai</h5>
                <div class="px-4 py-3 bg-blue-50 rounded-lg text-blue-800 font-medium">
                    {{ $tanggalSelesai->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>
