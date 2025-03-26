<x-merchant-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-center py-16">
                    <div class="mb-6">
                        <svg class="w-24 h-24 text-homize-blue mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Fitur Analytics Akan Segera Hadir</h3>
                    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                        Kami sedang mengembangkan fitur analytics yang akan membantu Anda memantau performa bisnis Anda dengan lebih baik.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-4xl mx-auto mb-12">
                        <div class="bg-homize-gray p-6 rounded-lg">
                            <div class="text-homize-blue text-4xl font-bold mb-2">{{ $monthlyStats['pendapatan'] > 0 ? 'Rp ' . number_format($monthlyStats['pendapatan'], 0, ',', '.') : 'Rp 0' }}</div>
                            <div class="text-gray-600">Pendapatan Bulan Ini</div>
                        </div>
                        
                        <div class="bg-homize-gray p-6 rounded-lg">
                            <div class="text-homize-blue text-4xl font-bold mb-2">{{ $monthlyStats['pesanan'] }}</div>
                            <div class="text-gray-600">Pesanan Bulan Ini</div>
                        </div>
                        
                        <div class="bg-homize-gray p-6 rounded-lg">
                            <div class="text-homize-blue text-4xl font-bold mb-2">{{ $monthlyStats['pelanggan'] }}</div>
                            <div class="text-gray-600">Pelanggan Bulan Ini</div>
                        </div>
                        
                        <div class="bg-homize-gray p-6 rounded-lg">
                            <div class="text-homize-blue text-4xl font-bold mb-2">{{ $monthlyStats['rating'] > 0 ? number_format($monthlyStats['rating'], 1) : '-' }}</div>
                            <div class="text-gray-600">Rating Rata-rata</div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Fitur yang Akan Datang:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                            <div class="p-4">
                                <svg class="w-12 h-12 text-homize-blue mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                                <h5 class="font-medium text-gray-800 mb-2">Grafik Pendapatan</h5>
                                <p class="text-gray-600 text-sm">Visualisasi pendapatan harian, mingguan, dan bulanan</p>
                            </div>
                            
                            <div class="p-4">
                                <svg class="w-12 h-12 text-homize-blue mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <h5 class="font-medium text-gray-800 mb-2">Analisis Layanan</h5>
                                <p class="text-gray-600 text-sm">Perbandingan performa antar layanan yang Anda tawarkan</p>
                            </div>
                            
                            <div class="p-4">
                                <svg class="w-12 h-12 text-homize-blue mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h5 class="font-medium text-gray-800 mb-2">Insight Pelanggan</h5>
                                <p class="text-gray-600 text-sm">Analisis demografi dan perilaku pelanggan Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-merchant-layout> 