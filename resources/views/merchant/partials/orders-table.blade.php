<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Proses</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
            <tr data-order-id="{{ $order->id }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $order->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->nama_user }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->nama_layanan }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $statusProses = $order->status_proses;
                        $statusProsesClass = match (strtolower($statusProses)) {
                            'selesai' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'dikonfirmasi' => 'bg-blue-100 text-blue-800',
                            'dibatalkan' => 'bg-red-100 text-red-800',
                            'sedang diproses' => 'bg-orange-100 text-orange-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusProsesClass }}">
                        {{ ucfirst($statusProses) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap status-pembayaran-cell">
                    @php
                        $statusPembayaran = $order->status_pembayaran;
                        $statusPembayaranClass = match (strtolower($statusPembayaran)) {
                            'berhasil' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'gagal' => 'bg-red-100 text-red-800',
                            'dibatalkan' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusPembayaranClass }}">
                        {{ ucfirst($statusPembayaran) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
    {{ $order->waktu_mulai ? \Carbon\Carbon::parse($order->waktu_mulai)->format('d M Y') : '-' }}
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
    {{ $order->waktu_selesai ? \Carbon\Carbon::parse($order->waktu_selesai)->format('d M Y') : '-' }}
</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button class="text-homize-blue hover:text-homize-blue-second mr-3 view-order" data-id="{{ $order->id }}">Detail</button>
                    <a href="javascript:void(0)" class="text-indigo-600 hover:text-indigo-900 update-status" data-id="{{ $order->id }}">Update Status Proses</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-12 text-gray-500">Tidak ada pesanan pada rentang tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
