<div class="form-card bg-white">
    <div class="form-card-header">
        <h3 class="text-lg font-semibold text-gray-800">Ringkasan Biaya</h3>
    </div>
    <div class="form-card-body">
        <div class="price-row">
            <p class="text-gray-700">Harga Layanan</p>
            <p class="font-semibold">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
        </div>

        <div class="price-row">
            <p class="font-semibold text-gray-800">Total Pembayaran</p>
            <p class="price-total">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
