<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Amount Card -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Transaksi</h3>
                            <p class="text-2xl font-bold text-green-500">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">dari {{ number_format($transactionCount) }} transaksi</p>
                        </div>
                    </div>
                </div>
                <!-- Pending Card -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-400">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-400 bg-opacity-10">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Payment Pending</h3>
                            <p class="text-2xl font-bold text-yellow-500">{{ number_format($paymentPendingCount) }}</p>
                            <p class="text-sm text-gray-500">Rp {{ number_format($paymentPendingAmount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <!-- Confirmed Card -->
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Payment Confirmed</h3>
                            <p class="text-2xl font-bold text-blue-500">{{ number_format($paymentConfirmedCount) }}</p>
                            <p class="text-sm text-gray-500">Rp {{ number_format($paymentConfirmedAmount, 0, ',', '.') }}</p>
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
                            <select name="interval" id="interval" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                                <option value="15000" {{ request('interval', 15000) == 15000 ? 'selected' : '' }}>Rp 15.000</option>
                                <option value="50000" {{ request('interval') == 50000 ? 'selected' : '' }}>Rp 50.000</option>
                                <option value="100000" {{ request('interval') == 100000 ? 'selected' : '' }}>Rp 100.000</option>
                                <option value="500000" {{ request('interval') == 500000 ? 'selected' : '' }}>Rp 500.000</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="text-sm text-gray-500 mb-4">
                    Range Transaksi: Rp {{ number_format($minAmount, 0, ',', '.') }} - Rp {{ number_format($maxAmount, 0, ',', '.') }}
                </div>
                <div class="relative" style="height: 300px;">
                    <canvas id="transactionRangeChart"></canvas>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-700">Daftar Transaksi</h3>
                        <div>
                            <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="filterTransactions(this.value)">
                                <option value="all">Semua Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merchant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Asli</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diskon</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Unik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bayar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                <tr class="transaction-row" data-status="{{ $transaction->status_pembayaran }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        #{{ $transaction->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->booking->user->nama ?? $transaction->booking->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->booking->merchant->nama_usaha ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->booking->layanan->nama_layanan ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($transaction->original_amount ?? $transaction->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($transaction->discount_amount && $transaction->discount_amount > 0)
                                            <span class="text-green-600">-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->unique_code ? 'Rp ' . number_format($transaction->unique_code, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        @if($transaction->unique_code)
                                            Rp {{ number_format($transaction->amount + $transaction->unique_code, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($transaction->status_pembayaran == 'Pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($transaction->status_pembayaran == 'Selesai')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        @elseif($transaction->status_pembayaran == 'Dibatalkan')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Dibatalkan
                                            </span>
                                            @if($transaction->rejection_reason)
                                                <p class="text-xs text-red-600 mt-1">Alasan: {{ $transaction->rejection_reason }}</p>
                                            @endif
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $transaction->status_pembayaran ?? '-' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($transaction->status_pembayaran == 'Pending')
                                            <div class="flex space-x-2">
                                                <form method="POST" action="{{ route('admin.payment.approve', $transaction->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs" onclick="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <button onclick="showRejectModal({{ $transaction->id }})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">
                                                    Tolak
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
            
            <!-- Reject Payment Modal -->
            <div id="reject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" style="z-index: 50;">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex justify-between items-center pb-3">
                            <h3 class="text-lg font-medium text-gray-900">Tolak Pembayaran</h3>
                            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <form id="reject-form">
    <input type="hidden" id="reject-transaction-id" name="transaction_id" value="">
    <div class="mt-2">
        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
    </div>
    <div class="mt-4 flex justify-end space-x-3">
        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
            Batal
        </button>
        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            Tolak Pembayaran
        </button>
    </div>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            
            // Initialize status filter
            const urlParams = new URLSearchParams(window.location.search);
            const statusFilter = urlParams.get('status');
            if (statusFilter) {
                document.getElementById('status-filter').value = statusFilter;
                filterTransactions(statusFilter);
            }
        });
        
        // Filter transactions by status
        function filterTransactions(status) {
            const rows = document.querySelectorAll('.transaction-row');
            
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        let currentRejectTransactionId = null;

        function showRejectModal(id) {
            const modal = document.getElementById('reject-modal');
            modal.classList.remove('hidden');
            document.getElementById('reject-transaction-id').value = id;
            currentRejectTransactionId = id;
        }

        const rejectForm = document.getElementById('reject-form');
        if (rejectForm) {
            rejectForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = document.getElementById('reject-transaction-id').value;
                const reason = document.getElementById('rejection_reason').value;
                fetch(`/admin/payment/${id}/reject`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ rejection_reason: reason })
                    })
                    .then(async response => {
                        let data;
                        try {
                            data = await response.json();
                        } catch (e) {
                            // If response is not JSON
                            console.error('Non-JSON response:', response);
                            alert('Terjadi kesalahan tak terduga (bukan JSON). Lihat console untuk detail.');
                            return;
                        }
                        if (response.ok && data.success) {
                            alert('Pembayaran berhasil ditolak');
                            location.reload();
                        } else {
                            // Show friendly alert, log details
                            alert(data.message || 'Gagal menolak pembayaran');
                            console.error('Reject Payment Error:', data);
                        }
                    })
                    .catch(error => {
                        // Network or unexpected error
                        alert('Terjadi kesalahan jaringan/server. Lihat console untuk detail.');
                        console.error('Fetch error:', error);
                    });
                });
            }

        function closeRejectModal() {
            const modal = document.getElementById('reject-modal');
            modal.classList.add('hidden');
            document.getElementById('reject-transaction-id').value = '';
            document.getElementById('rejection_reason').value = '';
            currentRejectTransactionId = null;
        }
    </script>
</x-admin-layout>