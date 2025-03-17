<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Merchant Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-center py-12">
                    <h3 class="text-xl font-medium mb-4">Belum ada merchant yang terdaftar</h3>
                    <p class="text-gray-600 mb-6">Daftarkan merchant Anda untuk mulai berjualan</p>
                    <button class="px-6 py-2 bg-[#38BDF8] text-white rounded-md hover:bg-[#0EA5E9]">
                        Daftar Merchant
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 