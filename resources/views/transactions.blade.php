<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Status Filter -->
                <div class="mb-6">
                    <div class="flex gap-4">
                        <button class="px-4 py-2 bg-[#38BDF8] text-white rounded-md hover:bg-[#0EA5E9]">Semua</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Diproses</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Berlangsung</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Selesai</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Dibatalkan</button>
                    </div>
                </div>

                <!-- Search and Date Filter -->
                <div class="flex justify-between mb-6">
                    <div class="w-1/3">
                        <input type="text" placeholder="Cari riwayat pesananmu di sini" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                            Pilih Tanggal Transaksi
                        </button>
                    </div>
                </div>

                <!-- Transaction List -->
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <div class="font-semibold">Pick Your Laundry</div>
                            <div class="text-[#38BDF8]">Layanan anda telah selesai</div>
                        </div>
                        <div class="flex gap-4">
                            <img src="path_to_image" alt="Laundry" class="w-20 h-20 object-cover rounded">
                            <div>
                                <div class="font-medium">Laundry Pakaian</div>
                                <div class="text-sm text-gray-600">Cuci Sprei dan Badcover</div>
                                <div class="text-sm">X1</div>
                            </div>
                            <div class="ml-auto">
                                <div class="font-medium">Rp28.700</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 