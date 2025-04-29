<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Total Amount Card -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Transaksi</h3>
                            <p class="text-2xl font-bold text-green-500">Rp
                                {{ number_format($totalAmount, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">dari {{ number_format($transactionCount) }} transaksi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Range Chart -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Distribusi Range Transaksi</h3>
                    <form action="{{ route('admin.transactions') }}" method="GET" class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <label for="interval" class="text-sm text-gray-600">Interval Range:</label>
                            <select name="interval" id="interval"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                onchange="this.form.submit()">
                                <option value="15000" {{ request('interval', 15000) == 15000 ? 'selected' : '' }}>Rp
                                    15.000</option>
                                <option value="50000" {{ request('interval') == 50000 ? 'selected' : '' }}>Rp 50.000
                                </option>
                                <option value="100000" {{ request('interval') == 100000 ? 'selected' : '' }}>Rp 100.000
                                </option>
                                <option value="500000" {{ request('interval') == 500000 ? 'selected' : '' }}>Rp 500.000
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="text-sm text-gray-500 mb-4">
                    Range Transaksi: Rp {{ number_format($minAmount, 0, ',', '.') }} - Rp
                    {{ number_format($maxAmount, 0, ',', '.') }}
                </div>
                <div class="relative" style="height: 300px;">
                    <canvas id="transactionRangeChart"></canvas>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex flex-row pt-6 px-6 pb-2">
                    <h1 class="font-bold text-gray-500">Transaksi belum di bayar</h1>
                </div>
                <div class="py-2 px-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Merchant</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody id="belum-dibayar-tbody" class="bg-white divide-y divide-gray-200">
                                @foreach ($transactions as $index => $transaction)
                                    @if ($transaction->booking->id_paid == 1)
                                        <tr data-transaction-id="{{ $transaction->id }}"
                                            data-booking-id="{{ $transaction->booking->id }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                #{{ $transaction->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->booking->user->nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->booking->merchant->nama_usaha }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                                {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $transaction->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-400">
                                                <select
                                                    class="paid-status-select bg-homize-gray font-bold p-2 rounded border-gray-300"
                                                    data-booking-id="{{ $transaction->booking->id }}">
                                                    @foreach ($paid as $paidStatus)
                                                        <option value="{{ $paidStatus->id }}"
                                                            {{ $transaction->booking->id_paid == $paidStatus->id ? 'selected' : '' }}>
                                                            {{ $paidStatus->status_pembayaran }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
            {{-- Sudah selesai di bayar --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8">
                <div class="flex flex-row pt-6 px-6 pb-2">
                    <h1 class="font-bold text-gray-500">Transaksi sudah di bayar</h1>
                </div>
                <div class="py-2 px-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Merchant</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody id="sudah-dibayar-tbody" class="bg-white divide-y divide-gray-200">
                                @foreach ($transactions as $index => $transaction)
                                    @if ($transaction->booking->id_paid == 2)
                                        <tr data-transaction-id="{{ $transaction->id }}"
                                            data-booking-id="{{ $transaction->booking->id }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                #{{ $transaction->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->booking->user->nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->booking->merchant->nama_usaha }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                                {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $transaction->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-400">
                                                <select class="paid-status-select bg-gray-400 font-bold p-2 rounded border-gray-300"
                                                    data-booking-id="{{ $transaction->booking->id }}">
                                                    @foreach ($paid as $paidStatus)
                                                        <option value="{{ $paidStatus->id }}"
                                                            {{ $transaction->booking->id_paid == $paidStatus->id ? 'selected' : '' }}>
                                                            {{ $paidStatus->status_pembayaran }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('paid-status-select')) {
                    const select = e.target;
                    const bookingId = select.getAttribute('data-booking-id');
                    const idPaid = select.value;
                    const row = select.closest('tr');
                    // Debug log
                    console.log('Dropdown changed:', {
                        bookingId,
                        idPaid,
                        row
                    });
                    fetch('/admin/booking/' + bookingId + '/update-paid', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                id_paid: idPaid
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let targetTbody = null;
                                if (idPaid == '1') {
                                    targetTbody = document.getElementById('belum-dibayar-tbody');
                                } else if (idPaid == '2') {
                                    targetTbody = document.getElementById('sudah-dibayar-tbody');
                                }
                                if (targetTbody && !targetTbody.contains(row)) {
                                    row.parentNode.removeChild(row);
                                    targetTbody.prepend(row);
                                    // Optionally update dropdown value
                                    row.querySelector('.paid-status-select').value = idPaid;
                                }
                                console.log('Status pembayaran berhasil diubah.');
                            } else {
                                console.log('Gagal mengubah status pembayaran.');
                            }
                        })
                        .catch((err) => {
                            console.error('AJAX error:', err);
                            alert('Terjadi kesalahan.');
                        });
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('transactionRangeChart').getContext('2d');

            const rangeData = @json($rangeData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: rangeData.map(item => item.label),
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: rangeData.map(item => item.count),
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.paid-status-select').forEach(function(select) {
                select.addEventListener('change', function(e) {
                    const bookingId = this.getAttribute('data-booking-id');
                    const idPaid = this.value;
                    fetch('/admin/booking/' + bookingId + '/update-paid', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                id_paid: idPaid
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log("Status berhasil diubah.");
                            } else {
                                console.log('Gagal mengubah status pembayaran.');
                            }
                        })
                        .catch(() => alert('Terjadi kesalahan.'));
                });
            });
        });
    </script>
</x-admin-layout>
