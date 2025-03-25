<div class="space-y-6">
    @if($merchant->layanan->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($merchant->layanan as $layanan)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                    @if($layanan->aset)
                        <img src="{{ Storage::url($layanan->aset->media_url) }}" 
                             alt="{{ $layanan->nama_layanan }}" 
                             class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900">{{ $layanan->nama_layanan }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($layanan->deskripsi_layanan, 100) }}</p>
                        <div class="mt-4 flex justify-between items-center">
                            <div>
                                <span class="text-homize-blue font-medium">
                                    Rp {{ number_format($layanan->tarif_layanan->harga ?? 0) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    /{{ $layanan->tarif_layanan->satuan ?? '-' }}
                                </span>
                                <p class="text-sm text-gray-500 mt-1">
                                    Durasi: {{ $layanan->tarif_layanan->durasi ?? 0 }} {{ $layanan->tarif_layanan->tipe_durasi ?? '-' }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button class="p-2 text-gray-600 hover:text-homize-blue hover:bg-homize-blue/5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="mb-4">
                <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Layanan</h3>
            <p class="mt-1 text-gray-500">Mulai tambahkan layanan pertama Anda</p>
            <div class="mt-6">
                <button @click="activeTab = 'add'" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-homize-blue hover:bg-homize-blue-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-homize-blue">
                    Tambah Layanan
                </button>
            </div>
        </div>
    @endif
</div>