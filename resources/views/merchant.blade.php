<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Merchant Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-center py-12">
                    <img src="{{ asset('images/merchant-empty.svg') }}" alt="Empty State" class="w-48 h-48 mx-auto mb-6">
                    <h3 class="text-xl font-medium text-gray-900 mb-4">Belum ada merchant yang terdaftar</h3>
                    <p class="text-gray-600 mb-6">Daftarkan merchant Anda untuk mulai berjualan</p>
                    <button class="px-6 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Daftar Merchant
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 