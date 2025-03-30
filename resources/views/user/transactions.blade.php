<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Transaksi Saya') }}
        </h2>
    </x-slot>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script loaded'); // Debug log

            let datePicker;

            // Initialize Flatpickr
            datePicker = flatpickr("#dateRange", {
                mode: "range",
                dateFormat: "Y-m-d",
                locale: "id",
                theme: "material_blue",
                allowInput: true,
                disableMobile: true, // Force desktop UI
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length === 2) {
                        filterByDateRange(selectedDates[0], selectedDates[1]);
                    }
                }
            });

            // Make functions globally available
            window.filterTransactions = function(status) {
                console.log('Filtering transactions for status:', status); // Debug log

                // Update active button
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('bg-homize-blue', 'text-white');
                    btn.classList.add('border', 'border-gray-300', 'text-gray-700');
                });
                event.target.classList.remove('border', 'border-gray-300', 'text-gray-700');
                event.target.classList.add('bg-homize-blue', 'text-white');

                // Show loading state
                document.getElementById('transaction-list').innerHTML = '<div class="text-center py-4">Loading...</div>';

                // Make AJAX request
                fetch(`/user/transactions/filter?status=${status}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        console.log('Response received:', response); // Debug log
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data received:', data); // Debug log
                        if (data.success) {
                            document.getElementById('transaction-list').innerHTML = data.html;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('transaction-list').innerHTML =
                            '<div class="text-center py-4 text-red-500">Error loading transactions</div>';
                    });
            };

            window.resetDateFilter = function() {
                // Clear the date picker
                if (datePicker) {
                    datePicker.clear();
                }

                // Reset the filter buttons to show all transactions
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('bg-homize-blue', 'text-white');
                    btn.classList.add('border', 'border-gray-300', 'text-gray-700');
                });
                document.querySelector('.filter-btn[onclick="filterTransactions(\'all\')"]').classList.remove('border',
                    'border-gray-300', 'text-gray-700');
                document.querySelector('.filter-btn[onclick="filterTransactions(\'all\')"]').classList.add('bg-homize-blue',
                    'text-white');

                // Show loading state
                document.getElementById('transaction-list').innerHTML = '<div class="text-center py-4">Loading...</div>';

                // Fetch all transactions
                fetch('/user/transactions/filter?status=all', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('transaction-list').innerHTML = data.html;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('transaction-list').innerHTML =
                            '<div class="text-center py-4 text-red-500">Error loading transactions</div>';
                    });
            };

            window.filterByDateRange = function(startDate, endDate) {
                console.log('Filtering transactions by date range:', startDate, endDate); // Debug log

                // Show loading state
                document.getElementById('transaction-list').innerHTML = '<div class="text-center py-4">Loading...</div>';

                // Format dates for the API
                const start = startDate.toISOString().split('T')[0];
                const end = endDate.toISOString().split('T')[0];

                // Make AJAX request
                fetch(`/user/transactions/filter-by-date?start_date=${start}&end_date=${end}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        console.log('Response received:', response); // Debug log
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data received:', data); // Debug log
                        if (data.success) {
                            document.getElementById('transaction-list').innerHTML = data.html;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('transaction-list').innerHTML =
                            '<div class="text-center py-4 text-red-500">Error loading transactions</div>';
                    });
            };
        });
    </script>
    @endpush

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Status Filter -->
                <div class="mb-6">
                    <div class="flex gap-4">
                        <button type="button" onclick="filterTransactions('all')"
                            class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors filter-btn active">Semua</button>
                        <button type="button" onclick="filterTransactions('1')"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors filter-btn">Diproses</button>
                        <button type="button" onclick="filterTransactions('3')"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors filter-btn">Berlangsung</button>
                        <button type="button" onclick="filterTransactions('4')"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors filter-btn">Selesai</button>
                        <button type="button" onclick="filterTransactions('5')"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors filter-btn">Dibatalkan</button>
                    </div>
                </div>

                <!-- Search and Date Filter -->
                <div class="flex justify-between mb-6">
                    <div class="w-1/3">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Cari riwayat pesananmu di sini"
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
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" id="dateRange" placeholder="Pilih Tanggal Transaksi"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors flex items-center gap-2 cursor-pointer pe-6">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <button type="button" id="resetDate" onclick="resetDateFilter()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Transaction List -->
                <div id="transaction-list">
                    @include('partials.transaction-list-dashboard', ['transactions' => $transactions])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
