<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Transaksi Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Status Filter -->
                <div class="mb-6">
                    <div class="flex gap-4">
                        <button
                            class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors">Semua</button>
                        <button
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Diproses</button>
                        <button
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Berlangsung</button>
                        <button
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Selesai</button>
                        <button
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Dibatalkan</button>
                    </div>
                </div>

                <!-- Search and Date Filter -->
                <div class="flex justify-between mb-6">
                    <div class="w-1/3">
                        <div class="relative">
                            <input type="text" placeholder="Cari riwayat pesananmu di sini"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <button
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Pilih Tanggal Transaksi
                    </button>
                </div>

                <!-- Transactions Table -->
                @if (count($transactions) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Layanan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Merchant</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
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
                                                $statusClass = match ($transaction->nama_status) {
                                                    'Payment Completed' => 'bg-green-100 text-green-800',
                                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                                    default => 'bg-red-100 text-red-800',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $transaction->nama_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($transaction->tanggal_booking)->format('d M Y') }}
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
                            <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
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
            </div>
        </div>
    </div>
</x-app-layout>
