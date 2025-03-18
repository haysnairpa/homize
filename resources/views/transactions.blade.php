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
                        <button class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors">Semua</button>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Diproses</button>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Berlangsung</button>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Selesai</button>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-homize-gray transition-colors">Dibatalkan</button>
                    </div>
                </div>

                <!-- Search and Date Filter -->
                <div class="flex justify-between mb-6">
                    <div class="w-1/3">
                        <div class="relative">
                            <input type="text" placeholder="Cari riwayat pesananmu di sini" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue">
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
                        Pilih Tanggal Transaksi
                    </button>
                </div>

                <!-- Transaction List -->
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center mb-4">
                            <div class="font-semibold text-homize-blue">Pick Your Laundry</div>
                            <div class="text-green-500">Layanan anda telah selesai</div>
                        </div>
                        <div class="flex gap-4">
                            <img src="{{ asset('images/service-laundry.jpg') }}" alt="Laundry" class="w-20 h-20 object-cover rounded">
                            <div class="flex-1">
                                <div class="font-medium">Laundry Pakaian</div>
                                <div class="text-sm text-gray-600">Cuci Sprei dan Badcover</div>
                                <div class="text-sm">X1</div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-homize-blue">Rp28.700</div>
                                <button class="mt-2 px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors text-sm">
                                    Pesan Lagi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 