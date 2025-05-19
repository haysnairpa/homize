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
                                class="py-3 px-4 text-sm transition-colors"
                                x-show="merchant.layanan && merchant.layanan.length > 0">
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
                                <div class="text-2xl font-bold text-gray-900" x-text="'Rp ' + (merchant.saldo ? merchant.saldo.toLocaleString('id-ID') : '0')"></div>
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
                            <h4 class="font-medium text-gray-800 mb-4">Riwayat Transaksi</h4>
                            <div class="text-center text-gray-500 py-4">
                                <a :href="'/admin/merchants/' + merchant.id + '/transactions'" class="text-blue-600 hover:underline">
                                    Lihat Semua Riwayat Transaksi
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bank Account Tab -->
                    <div x-show="tabs.bank" class="space-y-6">
                        <template x-if="merchant.rekening_merchant && merchant.rekening_merchant.length > 0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <template x-for="(rekening, index) in merchant.rekening_merchant" :key="index">
                                    <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="font-medium text-gray-900" x-text="rekening.nama_bank"></span>
                                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded" x-text="'Primary'"></span>
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
                    </div>
                    
                    <!-- Services Tab -->
                    <div x-show="tabs.services" class="space-y-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-medium text-gray-800">Daftar Layanan</h4>
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded" x-text="'Total: ' + (merchant.layanan ? merchant.layanan.length : 0)"></span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-if="merchant.layanan && merchant.layanan.length > 0">
                                <template x-for="(layanan, index) in merchant.layanan" :key="index">
                                    <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h5 class="font-medium text-gray-900" x-text="layanan.nama"></h5>
                                                <p class="text-sm text-gray-500 mt-1" x-text="layanan.deskripsi || 'Tidak ada deskripsi'"></p>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-medium text-gray-900" x-text="'Rp ' + (layanan.harga ? layanan.harga.toLocaleString('id-ID') : '0')"></div>
                                                <div class="text-xs text-gray-500" x-text="layanan.durasi ? layanan.durasi + ' menit' : '-'"></div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            
                            <div x-show="!merchant.layanan || merchant.layanan.length === 0" class="col-span-2 bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-gray-500">Tidak ada layanan yang tersedia</p>
                            </div>
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
