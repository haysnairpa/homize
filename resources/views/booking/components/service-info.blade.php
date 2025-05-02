<div class="form-card mb-6 bg-white">
    <div class="form-card-header">
        <h3 class="text-lg font-semibold text-gray-800">Informasi Layanan</h3>
    </div>
    <div class="form-card-body">
        <div class="flex items-start gap-4 mb-6">
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/' . $layanan->profile_url) }}"
                    alt="{{ $layanan->nama_usaha }}"
                    class="service-image object-scale-down h-full w-full rounded-full">
            </div>
            <div>
                <h4 class="font-semibold text-gray-800 text-lg">{{ $layanan->nama_layanan }}
                </h4>
                <p class="text-gray-500 mb-2">{{ $layanan->nama_usaha }}</p>
                <div
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $layanan->durasi }} {{ $layanan->tipe_durasi }}
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <h5 class="text-sm font-medium text-gray-500 mb-1">Deskripsi Layanan</h5>
                <p class="text-gray-700">{{ $layanan->deskripsi_layanan }}</p>
            </div>

            <div>
                <h5 class="text-sm font-medium text-gray-500 mb-1">Alamat Merchant</h5>
                <p class="text-gray-700">{{ $layanan->alamat_merchant }}</p>
            </div>
        </div>
    </div>
</div>
