<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nama Layanan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Durasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Jam Operasional</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                        src="{{ $item->aset->media_url }}" alt="{{ $item->nama_layanan }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-homize-gray flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                        <button type="button"
                            onclick="window.location.href='{{ route('layanan.detail', $item->id) }}'"
                            class="text-homize-blue hover:text-homize-blue-second mr-3">
                            Lihat
                        </button>
                        <button type="button" class="text-indigo-600 hover:text-indigo-900 mr-3 edit-layanan"
                            data-id="{{ $item->id }}">
                            Edit
                        </button>
                        <button type="button" class="text-red-600 hover:text-red-900 delete-layanan"
                            data-id="{{ $item->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
