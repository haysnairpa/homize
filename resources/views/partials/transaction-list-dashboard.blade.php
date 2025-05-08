@if (count($transactions) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merchant
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        Mulai
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            #{{ $transaction->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->nama_layanan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->nama_usaha }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status = $transaction->status_proses ?? $transaction->status_pembayaran;
                                $statusClass = match ($status) {
                                    'Selesai' => 'bg-green-100 text-green-800',
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Dikonfirmasi' => 'bg-blue-100 text-blue-800',
                                    'Sedang diproses' => 'bg-orange-100 text-orange-800',                                    'Dibatalkan' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($transaction->tanggal_booking)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                            @if (($transaction->status_proses ?? null) === 'Selesai' && !empty($transaction->tanggal_selesai))
                                {{ \Carbon\Carbon::parse($transaction->tanggal_selesai)->format('d M Y') }}
                            @else
                                <p class="py-4 whitespace-nowrap text-sm text-gray-500 text-left">Layanan anda
                                    belum selesai</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('user.transaction.detail', $transaction->id) }}"
                                class="text-homize-blue hover:text-homize-blue-second">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-12">
        <div class="mb-4">
            <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">Belum Ada Transaksi</h3>
        <p class="mt-1 text-gray-500">Mulai memesan layanan sekarang</p>
        <div class="mt-6">
            <a href="{{ route('home') }}"
                class="inline-flex items-center px-4 py-2 bg-homize-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-homize-blue-second focus:bg-homize-blue-second active:bg-homize-blue-second focus:outline-none focus:ring-2 focus:ring-homize-blue focus:ring-offset-2 transition ease-in-out duration-150">
                Cari Layanan
            </a>
        </div>
    </div>
@endif
