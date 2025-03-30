<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
</x-admin-layout> 