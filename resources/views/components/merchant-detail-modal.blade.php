<!-- Merchant Detail Modal Component -->
<div id="merchant-detail-modal" 
    x-data="{ 
        open: false, 
        merchant: null,
        activeTab: 'basic',
        tabs: {
            basic: true,
            financial: false,
            bank: false,
            services: false
        },
        setActiveTab(tab) {
            this.activeTab = tab;
            Object.keys(this.tabs).forEach(t => {
                this.tabs[t] = (t === tab);
            });
        }
    }"
    x-show="open"
    x-on:open-merchant-modal.window="open = true; merchant = $event.detail.merchant"
    x-on:close-merchant-modal.window="open = false"
    x-cloak
    class="fixed inset-0 flex items-center justify-center z-50">
    
    <!-- Backdrop with blur effect -->
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="open = false"></div>
    
    <!-- Modal container -->
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden relative animate-fadeIn">
        <!-- Header with merchant name and close button -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b px-6 py-4 flex items-center justify-between">
            <template x-if="merchant">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span x-text="merchant.nama_usaha"></span>
                    <span x-show="merchant.verified_at" class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">Verified</span>
                </h3>
            </template>
            <button @click="open = false" class="text-gray-400 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full p-1 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="sr-only">Close</span>
            </button>
        </div>
        
        <template x-if="merchant">
            <div>
                <!-- Navigation tabs -->
                <div class="border-b">
                    <nav class="flex px-6 -mb-px">
                        <button @click="setActiveTab('basic')" 
                                :class="{'border-b-2 border-gray-800 text-gray-800 font-medium': activeTab === 'basic', 'text-gray-500 hover:text-gray-700': activeTab !== 'basic'}"
                                class="py-3 px-4 text-sm transition-colors">
                            Informasi Dasar
                        </button>
                        <button @click="setActiveTab('financial')" 
                                :class="{'border-b-2 border-gray-800 text-gray-800 font-medium': activeTab === 'financial', 'text-gray-500 hover:text-gray-700': activeTab !== 'financial'}"
                                class="py-3 px-4 text-sm transition-colors">
                            Keuangan
                        </button>
                        <button @click="setActiveTab('bank')" 
                                :class="{'border-b-2 border-gray-800 text-gray-800 font-medium': activeTab === 'bank', 'text-gray-500 hover:text-gray-700': activeTab !== 'bank'}"
                                class="py-3 px-4 text-sm transition-colors">
                            Rekening Bank
                        </button>
                        <button @click="setActiveTab('services')" 
                                :class="{'border-b-2 border-gray-800 text-gray-800 font-medium': activeTab === 'services', 'text-gray-500 hover:text-gray-700': activeTab !== 'services'}"
                                class="py-3 px-4 text-sm transition-colors">
                            Layanan
                        </button>
                    </nav>
                </div>
                
                <!-- Content area -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-130px)]">
                    <!-- Basic Information Tab -->
                    <div x-show="tabs.basic" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">ID Merchant</span>
                                    <span class="font-medium text-gray-900" x-text="merchant.id"></span>
                                </div>
                                
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Nama Usaha</span>
                                    <span class="font-medium text-gray-900" x-text="merchant.nama_usaha"></span>
                                </div>
                                
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Pemilik</span>
                                    <span class="font-medium text-gray-900" x-text="merchant.user ? merchant.user.nama : '-'"></span>
                                </div>
                                
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Email</span>
                                    <span class="font-medium text-gray-900" x-text="merchant.user ? merchant.user.email : '-'"></span>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Kategori</span>
                                    <span class="font-medium text-gray-900" x-text="merchant.kategori ? merchant.kategori.nama : '-'"></span>
                                </div>
                                
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Alamat</span>
                                    <span class="font-medium text-gray-900" x-text="merchant.alamat || '-'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Information Tab -->
                    <div x-show="tabs.financial" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-500 mb-1">Saldo</div>
                                <div class="text-2xl font-bold text-gray-900" x-text="'Rp ' + (merchant.saldo ? Number(merchant.saldo).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0}) : '0')"></div>
                            </div>
                        </div>
                        
                        <!-- Balance Adjustment Form -->
                        <div class="mt-6 border rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-4">Pengaturan Saldo</h4>
                            <form id="balance-form" x-data="{
                                amount: '', 
                                type: 'penambahan', 
                                description: '',
                                isSubmitting: false,
                                errorMessage: '',
                                successMessage: ''
                            }" @submit.prevent="
                                isSubmitting = true;
                                errorMessage = '';
                                successMessage = '';
                                
                                fetch('/admin/merchants/' + merchant.id + '/adjust-balance', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        amount: amount,
                                        type: type,
                                        description: description
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    isSubmitting = false;
                                    if (data.success) {
                                        successMessage = data.message;
                                        merchant.saldo = data.new_balance;
                                        amount = '';
                                        description = '';
                                        
                                        // Refresh transaction history after successful balance adjustment
                                        const transactionComponent = document.querySelector('#transaction-history-component');
                                        if (transactionComponent && typeof transactionComponent.__x !== 'undefined') {
                                            transactionComponent.__x.$data.loadTransactions();
                                        }
                                    } else {
                                        errorMessage = data.message || 'Terjadi kesalahan';
                                    }
                                })
                                .catch(error => {
                                    isSubmitting = false;
                                    errorMessage = 'Terjadi kesalahan pada server';
                                    console.error('Error:', error);
                                });
                            ">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
                                        <input type="number" id="amount" x-model="amount" min="1" step="1000" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                            required>
                                    </div>
                                    <div>
                                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                                        <select id="type" x-model="type" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="penambahan">Penambahan</option>
                                            <option value="pengurangan">Pengurangan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                    <textarea id="description" x-model="description" rows="2" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Masukkan keterangan (opsional)"></textarea>
                                </div>
                                
                                <!-- Error Message -->
                                <div x-show="errorMessage" class="mb-4 p-3 bg-red-100 text-red-700 rounded-md" x-text="errorMessage"></div>
                                
                                <!-- Success Message -->
                                <div x-show="successMessage" class="mb-4 p-3 bg-green-100 text-green-700 rounded-md" x-text="successMessage"></div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" 
                                        :disabled="isSubmitting || !amount" 
                                        :class="{'opacity-50 cursor-not-allowed': isSubmitting || !amount}" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <span x-show="isSubmitting">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memproses...
                                        </span>
                                        <span x-show="!isSubmitting">
                                            <span x-text="type === 'penambahan' ? 'Tambah Saldo' : 'Kurangi Saldo'"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Transaction History -->
                        <div class="mt-6 border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-medium text-gray-800">Riwayat Transaksi</h4>
                                <button @click="loadTransactions()" class="text-xs text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Refresh
                                </button>
                            </div>
                            
                            <div id="transaction-history-component" x-data="{ 
                                transactions: [], 
                                isLoading: true,
                                loadTransactions() {
                                    this.isLoading = true;
                                    fetch('/admin/merchants/' + merchant.id + '/transactions?limit=5')
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                this.transactions = data.transactions || [];
                                            } else {
                                                console.error('Error loading transactions:', data.message || 'Unknown error');
                                            }
                                            this.isLoading = false;
                                        })
                                        .catch(error => {
                                            console.error('Error loading transactions:', error);
                                            this.isLoading = false;
                                        });
                                }
                            }" x-init="loadTransactions()">
                                <!-- Loading state -->
                                <div x-show="isLoading" class="py-8 flex justify-center">
                                    <svg class="animate-spin h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                
                                <!-- Transactions list -->
                                <div x-show="!isLoading && transactions.length > 0" class="divide-y">
                                    <template x-for="(transaction, index) in transactions" :key="transaction.id">
                                        <div class="py-3">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-medium text-gray-900" x-text="transaction.description || 'Transaksi Saldo'"></span>
                                                        <span x-show="transaction.type === 'penambahan'" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Penambahan</span>
                                                        <span x-show="transaction.type === 'pengurangan'" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Pengurangan</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1" x-text="transaction.formatted_date || new Date(transaction.created_at).toLocaleString('id-ID', {day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'})"></p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="font-medium" :class="{'text-green-600': transaction.type === 'penambahan', 'text-red-600': transaction.type === 'pengurangan'}">
                                                        <span x-text="transaction.type === 'penambahan' ? '+' : '-'"></span>
                                                        <span x-text="'Rp ' + Number(transaction.amount).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})"></span>
                                                    </span>
                                                    <p class="text-xs text-gray-500 mt-1" x-text="'Saldo: Rp ' + Number(transaction.balance_after).toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0})"></p>
                                                </div>
                                            </div>
                                            <p x-show="transaction.notes" class="mt-1 text-sm text-gray-600 bg-gray-50 p-2 rounded" x-text="transaction.notes"></p>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Empty state -->
                                <div x-show="!isLoading && transactions.length === 0" class="py-8 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-gray-500">Belum ada riwayat transaksi</p>
                                </div>
                                
                                <!-- View all link -->
                                <div class="mt-4 text-center border-t pt-4">
                                    <a :href="'/admin/merchants/' + merchant.id + '/transactions'" class="text-blue-600 hover:underline text-sm">
                                        Lihat Semua Riwayat Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bank Account Tab -->
                    <div x-show="tabs.bank" class="space-y-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-medium text-gray-800">Rekening Bank</h4>
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded" x-text="'Total: ' + (merchant.rekening_merchant ? merchant.rekening_merchant.length : 0)"></span>
                        </div>
                        
                        <template x-if="merchant.rekening_merchant && merchant.rekening_merchant.length > 0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <template x-for="(rekening, index) in merchant.rekening_merchant" :key="index">
                                    <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="font-medium text-gray-900" x-text="rekening.nama_bank"></span>
                                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded" x-text="index === 0 ? 'Primary' : 'Secondary'"></span>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex flex-col">
                                                <span class="text-xs text-gray-500">Nomor Rekening</span>
                                                <span class="font-medium text-gray-900" x-text="rekening.nomor_rekening"></span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs text-gray-500">Atas Nama</span>
                                                <span class="font-medium text-gray-900" x-text="rekening.atas_nama"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        
                        <div x-show="!merchant.rekening_merchant || merchant.rekening_merchant.length === 0" class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <p class="text-gray-600 font-medium">Tidak ada rekening bank</p>
                            <p class="text-gray-500 text-sm mt-1">Merchant ini belum menambahkan rekening bank.</p>
                        </div>
                    </div>
                    
                    <!-- Services Tab -->
                    <div x-show="tabs.services" class="space-y-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-medium text-gray-800">Daftar Layanan</h4>
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded" x-text="'Total: ' + (merchant.layanan ? merchant.layanan.length : 0)"></span>
                        </div>
                        
                        <template x-if="merchant.layanan && merchant.layanan.length > 0">
                            <div class="grid grid-cols-1 gap-4">
                                <template x-for="(layanan, index) in merchant.layanan" :key="index">
                                    <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <div class="flex-1">
                                                <h5 class="font-medium text-gray-900" x-text="layanan.nama_layanan"></h5>
                                                <p class="text-sm text-gray-500 mt-1" x-text="layanan.deskripsi_layanan || 'Tidak ada deskripsi'"></p>
                                                <div class="flex items-center mt-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2" x-text="layanan.kategori ? layanan.kategori.nama : 'Umum'"></span>
                                                    <span class="text-xs text-gray-500" x-text="layanan.created_at ? 'Dibuat: ' + new Date(layanan.created_at).toLocaleDateString('id-ID') : ''"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-homize-blue" x-text="'Rp ' + (layanan.harga ? layanan.harga.toLocaleString('id-ID') : '0')"></div>
                                                <div class="flex items-center justify-end mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-600" x-text="layanan.rating_avg ? layanan.rating_avg.toFixed(1) : '0.0'"></span>
                                                    <span class="text-xs text-gray-500 ml-1" x-text="'(' + (layanan.rating_count || 0) + ' ulasan)'"></span>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1" x-text="layanan.durasi ? layanan.durasi + ' ' + (layanan.tipe_durasi || 'menit') : '-'"></div>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end">
                                            <a :href="'/admin/layanan/' + layanan.id" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">Lihat Detail</a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        
                        <div x-show="!merchant.layanan || merchant.layanan.length === 0" class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-600 font-medium">Tidak ada layanan yang tersedia</p>
                            <p class="text-gray-500 text-sm mt-1">Merchant ini belum menambahkan layanan apapun.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer with actions -->
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 border-t">
                    <button @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Tutup
                    </button>
                    
                </div>
            </div>
        </template>
        
        <!-- Loading state -->
        <div x-show="open && !merchant" class="p-6 flex items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out forwards;
    }
    [x-cloak] { display: none !important; }
</style>
