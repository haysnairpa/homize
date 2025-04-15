<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <!-- Merchant Verification Section -->
             <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Verifikasi Merchant</h3>
                    
                    @if($pendingMerchants->isEmpty())
                        <p class="text-gray-500">Tidak ada merchant yang menunggu verifikasi.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merchant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingMerchants as $merchant)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $merchant->profile_url) }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $merchant->nama_usaha }}</div>
                                                    <div class="text-sm text-gray-500">{{ $merchant->alamat }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $merchant->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $merchant->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $merchant->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="approveMerchant({{ $merchant->id }})" class="text-green-600 hover:text-green-900 mr-3">
                                                Setujui
                                            </button>
                                            <button onclick="showRejectModal({{ $merchant->id }})" class="text-red-600 hover:text-red-900">
                                                Tolak
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Users Card -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
                                    <p class="text-3xl font-bold text-gray-900">{{ $regularUserCount }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Merchants Card -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-700">Total Merchants</h3>
                                    <p class="text-3xl font-bold text-gray-900">{{ $merchantCount }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Transactions Card -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-700">Total Transactions</h3>
                                    <p class="text-3xl font-bold text-gray-900">{{ $transactionCount }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- User Distribution Chart -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Distribusi Pengguna</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                    <!-- Statistics Summary -->
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600">Regular Users</p>
                            <p class="text-xl font-bold text-blue-600">{{ $regularUserCount }}</p>
                            <p class="text-sm text-gray-500">
                                ({{ number_format(($regularUserCount / $userCount) * 100, 1) }}%)
                            </p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-sm text-gray-600">Merchants</p>
                            <p class="text-xl font-bold text-green-600">{{ $merchantCount }}</p>
                            <p class="text-sm text-gray-500">
                                ({{ number_format(($merchantCount / $userCount) * 100, 1) }}%)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Transaction Amount -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Total Transaksi</h3>
                    <div class="flex items-center justify-center h-[300px]">
                        <div class="text-center">
                            <p class="text-4xl font-bold text-green-600">
                                Rp {{ number_format($totalAmount, 0, ',', '.') }}
                            </p>
                            <p class="text-gray-500 mt-2">Total dari {{ $transactionCount }} transaksi</p>
                            
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>



    <!-- Include Chart.js and Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        // Initialize the pie chart
        const ctx = document.getElementById('userDistributionChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Regular Users', 'Merchants'],
                datasets: [{
                    data: [{{ $regularUserCount }}, {{ $merchantCount }}],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)', // Blue for regular users
                        'rgba(16, 185, 129, 0.8)'  // Green for merchants
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        function showApproveModal(id) {
            const modal = document.getElementById('approveModal');
            const form = document.getElementById('approveForm');
            modal.classList.remove('hidden');
            form.action = `/admin/merchant/${id}/approve`;
        }

        function hideApproveModal() {
            const modal = document.getElementById('approveModal');
            const form = document.getElementById('approveForm');
            modal.classList.add('hidden');
            form.action = '';
        }

        function approveMerchant(id) {
            showApproveModal(id);
        }

        // Handle approve form submission
        document.getElementById('approveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Terjadi kesalahan saat memproses permintaan');
                    });
                }
                return response.json();
            })
            .then(data => {
                hideApproveModal();
                alert(data.message);
                window.location.reload();
            })
            .catch(error => {
                alert(error.message || 'Terjadi kesalahan saat memproses permintaan');
            });
        });

        function showRejectModal(id) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            modal.classList.remove('hidden');
            form.action = `/admin/merchant/${id}/reject`;
        }

        function hideRejectModal() {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            const reason = document.getElementById('rejection_reason');
            modal.classList.add('hidden');
            form.action = '';
            reason.value = '';
        }

        // Handle reject form submission
        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            
            // Add CSRF token to formData if not already present
            if (!formData.has('_token')) {
                formData.append('_token', '{{ csrf_token() }}');
            }

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                    // Don't set Content-Type here as it's automatically set with FormData
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Terjadi kesalahan saat memproses permintaan');
                    });
                }
                return response.json();
            })
            .then(data => {
                hideRejectModal();
                alert(data.message);
                window.location.reload();
            })
            .catch(error => {
                alert(error.message || 'Terjadi kesalahan saat memproses permintaan');
            });
        });
    </script>
    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" aria-labelledby="approve-modal-title" role="dialog" aria-modal="true">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="approve-modal-title">Setujui Merchant</h3>
                <form id="approveForm" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <p class="text-gray-700">Apakah Anda yakin ingin menyetujui merchant ini?</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideApproveModal()" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Batal</button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" aria-labelledby="reject-modal-title" role="dialog" aria-modal="true">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="reject-modal-title">Tolak Merchant</h3>
                <form id="rejectForm" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Masukkan alasan penolakan"></textarea>
                        <!-- Error Message -->
                        <div id="rejectionError" class="mt-2 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideRejectModal()" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Batal</button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>