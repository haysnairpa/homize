<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Manajemen Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Status Filter -->
                <div class="mb-6">
                    <div class="flex gap-4">
                        <button class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors">Semua</button>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Menunggu Konfirmasi</button>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Dalam Proses</button>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Selesai</button>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Dibatalkan</button>
                    </div>
                </div>

                <!-- Search and Date Filter -->
                <div class="flex justify-between mb-6">
                    <div class="w-1/3">
                        <div class="relative">
                            <input type="text" placeholder="Cari pesanan..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Filter Tanggal
                    </button>
                </div>

                <!-- Orders Table -->
                @if(count($orders) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->nama_user }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->nama_layanan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = match ($order->nama_status) {
                                            'Payment Completed' => 'bg-green-100 text-green-800',
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'In Progress' => 'bg-blue-100 text-blue-800',
                                            'Completed' => 'bg-green-100 text-green-800',
                                            default => 'bg-red-100 text-red-800',
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $order->nama_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->tanggal_booking)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-homize-blue hover:text-homize-blue-second mr-3 view-order" data-id="{{ $order->id }}">Detail</button>
                                    <button class="text-indigo-600 hover:text-indigo-900 update-status" data-id="{{ $order->id }}">Update Status</button>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum Ada Pesanan</h3>
                    <p class="mt-1 text-gray-500">Pesanan dari pelanggan akan muncul di sini</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    <div id="orderDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Detail Pesanan</h3>
                <button class="close-modal text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="orderDetailContent">
                <!-- Content will be loaded dynamically -->
                <div class="animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 mb-6"></div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <div class="h-4 bg-gray-200 rounded w-1/3 mb-2"></div>
                            <div class="h-4 bg-gray-200 rounded w-full"></div>
                        </div>
                        <div>
                            <div class="h-4 bg-gray-200 rounded w-1/3 mb-2"></div>
                            <div class="h-4 bg-gray-200 rounded w-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Status -->
    <div id="updateStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Update Status Pesanan</h3>
                <button class="close-modal text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="updateStatusForm" action="" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Status Baru</label>
                    <select name="status_id" id="status_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-homize-blue focus:border-homize-blue">
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->nama_status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="close-modal px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 mr-2">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderDetailModal = document.getElementById('orderDetailModal');
            const updateStatusModal = document.getElementById('updateStatusModal');
            const updateStatusForm = document.getElementById('updateStatusForm');
            const closeButtons = document.querySelectorAll('.close-modal');
            
            // View Order Detail
            document.querySelectorAll('.view-order').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    fetchOrderDetail(orderId);
                });
            });
            
            // Fungsi untuk fetch detail pesanan
            function fetchOrderDetail(orderId) {
                fetch(`/merchant/orders/${orderId}/detail`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showOrderDetailModal(data.data);
                    } else {
                        alert('Gagal memuat detail pesanan, error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat detail pesanan, error: ' + error);
                });
            }
            
            // Fungsi untuk menampilkan modal detail pesanan
            function showOrderDetailModal(order) {
                const orderDetailContent = document.getElementById('orderDetailContent');
                orderDetailContent.innerHTML = `
                    <div class="border-b pb-4 mb-4">
                        <h4 class="text-lg font-medium text-gray-900">Pesanan #${order.id}</h4>
                        <p class="text-sm text-gray-500">Tanggal: ${order.tanggal}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h5 class="font-medium text-gray-700 mb-2">Detail Pelanggan</h5>
                            <p class="text-sm text-gray-600">Nama: ${order.pelanggan.nama}</p>
                            <p class="text-sm text-gray-600">Email: ${order.pelanggan.email}</p>
                            <p class="text-sm text-gray-600">Alamat: ${order.pelanggan.alamat}</p>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-700 mb-2">Detail Layanan</h5>
                            <p class="text-sm text-gray-600">Layanan: ${order.layanan.nama}</p>
                            <p class="text-sm text-gray-600">Harga: Rp ${new Intl.NumberFormat('id-ID').format(order.layanan.harga)}</p>
                            <p class="text-sm text-gray-600">Durasi: ${order.layanan.durasi}</p>
                            <p class="text-sm text-gray-600">Jadwal: ${order.jadwal.mulai} - ${order.jadwal.selesai}</p>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <h5 class="font-medium text-gray-700 mb-2">Status</h5>
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="px-2 py-1 rounded-full ${getStatusClass(order.status)}">
                                ${order.status}
                            </span>
                        </p>
                        <h5 class="font-medium text-gray-700 mb-2">Total Pembayaran</h5>
                        <p class="text-sm font-semibold text-gray-800 mb-3">Rp ${new Intl.NumberFormat('id-ID').format(order.total)}</p>
                        <h5 class="font-medium text-gray-700 mb-2">Catatan</h5>
                        <p class="text-sm text-gray-600">${order.catatan || 'Tidak ada catatan'}</p>
                    </div>
                `;
                orderDetailModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            // Fungsi untuk mendapatkan class status
            function getStatusClass(status) {
                return status === 'Payment Completed' ? 'bg-green-100 text-green-800' :
                    status === 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                    status === 'In Progress' ? 'bg-blue-100 text-blue-800' :
                    status === 'Completed' ? 'bg-green-100 text-green-800' :
                    'bg-red-100 text-red-800';
            }
            
            // Update Order Status
            document.querySelectorAll('.update-status').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.dataset.id;
                    const modal = document.getElementById('updateStatusModal');
                    const form = document.getElementById('updateStatusForm');
                    
                    // Set form action URL dengan benar
                    form.action = `/merchant/orders/${orderId}/update-status`;
                    
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            // Close Modals
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    orderDetailModal.classList.add('hidden');
                    updateStatusModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            });
            
            // Close modal when clicking outside
            [orderDetailModal, updateStatusModal].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            });
        });
    </script>
</x-merchant-layout> 