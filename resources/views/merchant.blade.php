<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Merchant Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="text-center max-w-2xl mx-auto">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Mulai Perjalanan Bisnis Anda</h3>
                    <p class="text-gray-600 mb-8 text-lg">Bergabunglah dengan ribuan penyedia jasa sukses di Homize dan
                        capai pelanggan lebih luas</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div class="p-6 bg-homize-blue/5 rounded-xl">
                            <div
                                class="w-12 h-12 bg-homize-blue/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-homize-blue" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Penghasilan Lebih</h4>
                            <p class="text-sm text-gray-600">Dapatkan penghasilan tambahan dari pelanggan di seluruh
                                Indonesia</p>
                        </div>

                        <div class="p-6 bg-homize-blue/5 rounded-xl">
                            <div
                                class="w-12 h-12 bg-homize-blue/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-homize-blue" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Terpercaya</h4>
                            <p class="text-sm text-gray-600">Platform terpercaya dengan sistem pembayaran yang aman</p>
                        </div>

                        <div class="p-6 bg-homize-blue/5 rounded-xl">
                            <div
                                class="w-12 h-12 bg-homize-blue/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-homize-blue" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Berkembang</h4>
                            <p class="text-sm text-gray-600">Kembangkan bisnis Anda dengan tools dan insights yang kami
                                sediakan</p>
                        </div>
                    </div>

                    <a href="{{ route('merchant.register.step1') }}"
                        class="inline-flex items-center px-8 py-3 bg-homize-blue text-white rounded-lg hover:bg-homize-blue-second transition-colors text-lg font-medium gap-2 group">
                        Mulai Berjualan
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
