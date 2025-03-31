<x-merchant-layout>
    <div class="min-h-screen bg-gray-50">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-homize-blue leading-tight">
                {{ __('Analytics & Insights') }}
            </h2>
        </x-slot>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Date Range Selector -->
            <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-5 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 sm:mb-0">Periode Analisis</h2>
                    <div class="flex flex-wrap gap-2">
                        <button class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium" data-period="month">
                            
                        </button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm font-medium" data-period="week">
                            7 Hari Terakhir
                        </button>
                        <button class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium" data-period="month30">
                            30 Hari Terakhir
                        </button>
                        <button class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium flex items-center gap-1" data-period="custom">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Kustom
                        </button>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Revenue Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md" data-chart-type="revenue">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pendapatan </p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1" id="pendapatanValue">
                                    {{ $monthlyStats['pendapatan'] > 0 ? 'Rp ' . number_format($monthlyStats['pendapatan'], 0, ',', '.') : 'Rp 0' }}
                                </h3>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center mt-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $pendapatanTrend > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" id="pendapatanTrend">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $pendapatanTrend > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"></path>
                                </svg>
                                {{ abs($pendapatanTrend) }}%
                            </span>
                            <span class="text-xs text-gray-500 ml-2">dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-blue-600"></div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md" data-chart-type="orders">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pesanan </p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1" id="pesananValue">{{ $monthlyStats['pesanan'] }}</h3>
                            </div>
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center mt-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                {{ abs($pesananTrend) }}%
                            </span>
                            <span class="text-xs text-gray-500 ml-2">dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-green-600"></div>
                </div>

                <!-- Customers Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md" data-chart-type="views">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Produk Dilihat</p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1" id="viewsValue">{{ $weeklyStats['views'] }}</h3>
                            </div>
                            <div class="bg-indigo-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center mt-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $viewsTrend > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" id="viewsTrend">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $viewsTrend > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"></path>
                                </svg>
                                {{ abs($viewsTrend) }}%
                            </span>
                            <span class="text-xs text-gray-500 ml-2">dari minggu lalu</span>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-indigo-600"></div>
                </div>

                <!-- Rating Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md" data-chart-type="rating">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Rating Rata-rata</p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1" id="ratingValue">{{ number_format($monthlyStats['rating'] ?? 0, 1) }}</h3>
                            </div>
                            <div class="bg-amber-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center mt-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $ratingTrend > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}" id="ratingTrend">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ratingTrend > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"></path>
                                </svg>
                                {{ abs($ratingTrend) }}
                            </span>
                            <span class="text-xs text-gray-500 ml-2">dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="h-1 w-full bg-amber-600"></div>
                </div>
            </div>

            <!-- Revenue Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 sm:mb-0" id="chartTitle">Tren Pendapatan</h2>
                        <div class="inline-flex rounded-md shadow-sm">
                            <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-l-lg hover:bg-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:outline-none" data-interval="daily">
                                Harian
                            </button>
                            <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-200 hover:bg-gray-50 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:outline-none" data-interval="weekly">
                                Mingguan
                            </button>
                            <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-50 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:outline-none" data-interval="monthly">
                                Bulanan
                            </button>
                        </div>
                    </div>
                    <div class="h-80">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Order Status & Customer Demographics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Order Status -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6">Status Pesanan</h2>
                        <div class="h-64 mb-6">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500 mb-1">Tingkat Penyelesaian</p>
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-green-600">{{ $completionRate }}%</span>
                                    <svg class="w-5 h-5 ml-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500 mb-1">Tingkat Pembatalan</p>
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-red-600">{{ $cancellationRate }}%</span>
                                    <svg class="w-5 h-5 ml-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Demographics -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6">Demografi Pelanggan</h2>
                        <div class="h-64 mb-6">
                            <canvas id="customerDemographicsChart"></canvas>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500 mb-1">Pelanggan Berulang</p>
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-blue-600">{{ $returningCustomersPercentage }}%</span>
                                    <svg class="w-5 h-5 ml-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-500 mb-1">Pelanggan Baru</p>
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-green-600">{{ $newCustomersPercentage }}%</span>
                                    <svg class="w-5 h-5 ml-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performa Layanan -->
            <div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Performa Layanan</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LAYANAN</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PESANAN</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PENDAPATAN</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RATING</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KONVERSI</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TREN</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($servicePerformance as $service)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                <span class="text-gray-500">{{ substr($service->nama_layanan, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $service->nama_layanan }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $service->total_orders ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp {{ number_format($service->total_revenue ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($service->avg_rating ?? 0))
                                                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-500">{{ number_format($service->avg_rating ?? 0, 1) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                            @php
                                                $conversionRate = $service->total_orders > 0 ? min(100, ($service->unique_customers / $service->total_orders) * 100) : 0;
                                            @endphp
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $conversionRate }}%"></div>
                                        </div>
                                        <span class="text-xs">{{ number_format($conversionRate, 0) }}%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $growthRate = $service->growth_rate ?? 0;
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $growthRate >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $growthRate >= 0 ? '+' : '' }}{{ number_format($growthRate, 0) }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada data layanan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Feedback & Ratings -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Ulasan & Rating Pelanggan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="text-4xl font-bold text-amber-500">
                                    {{ number_format($avgRating, 1) }}
                                    <span class="text-lg text-gray-500">/5.0</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg mb-1">
                                        {{ $ratingCount > 0 ? round(($ratingStats[5] / $ratingCount) * 100) : 0 }}%
                                        pembeli merasa puas
                                    </p>
                                    <p class="text-gray-500">{{ $ratingCount }} rating • {{ $reviewCount }} ulasan</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-700 w-8">{{ $i }}</span>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mx-2">
                                            <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $ratingCount > 0 ? ($ratingStats[$i] / $ratingCount) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500 w-8">{{ $ratingStats[$i] }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="space-y-4">
                            @foreach($latestReviews as $review)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            {{ substr($review->nama_user, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $review->nama_user }}</p>
                                            <div class="flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rate ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $review->message }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insights & Recommendations -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Insights & Rekomendasi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Insight Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-blue-200 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-md font-semibold text-gray-800">Insight</h3>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">Layanan Pembersihan Rumah memiliki tingkat konversi tertinggi (68%) dan menghasilkan pendapatan terbesar.</p>
                        </div>

                        <!-- Recommendation Card -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-green-200 rounded-lg">
                                    <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-md font-semibold text-gray-800">Rekomendasi</h3>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">Tingkatkan promosi untuk layanan Pembersihan Rumah dan pertimbangkan untuk menambah variasi layanan serupa.</p>
                        </div>

                        <!-- Warning Card -->
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-5 border border-amber-200 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-amber-200 rounded-lg">
                                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-md font-semibold text-gray-800">Perhatian</h3>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">Layanan Perbaikan Listrik mengalami penurunan 3%. Periksa ulasan pelanggan untuk menemukan area yang perlu ditingkatkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hapus salah satu dari baris berikut -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart.js global settings
            Chart.defaults.font.family = "'Inter', 'Helvetica', 'Arial', sans-serif";
            Chart.defaults.color = '#64748b';
            Chart.defaults.scale.grid.color = '#f1f5f9';
            
            // Definisi variabel chart
            let mainChart;
            const chartCtx = document.getElementById('mainChart').getContext('2d');
            let currentChart = 'revenue';
            
            // Data real dari backend
            const revenueLabels = {!! json_encode(array_column($revenueData, 'date')) !!};
            const revenueValues = {!! json_encode(array_column($revenueData, 'total')) !!};

            const orderLabels = {!! json_encode(array_column($orderData, 'date')) !!};
            const orderValues = {!! json_encode(array_column($orderData, 'total')) !!};

            const viewsLabels = {!! json_encode(array_column($viewsData, 'date')) !!};
            const viewsValues = {!! json_encode(array_column($viewsData, 'total')) !!};

            const ratingLabels = {!! json_encode(array_column($ratingData, 'date')) !!};
            const ratingValues = {!! json_encode(array_column($ratingData, 'total')) !!};

            // Tambahkan logging untuk debugging
            console.log('Revenue Data:', revenueLabels, revenueValues);
            console.log('Order Data:', orderLabels, orderValues);
            console.log('Views Data:', viewsLabels, viewsValues);
            console.log('Rating Data:', ratingLabels, ratingValues);
            
            // Data untuk berbagai jenis chart
            const chartData = {
                revenue: {
                    label: 'Pendapatan',
                    labels: revenueLabels,
                    data: revenueValues,
                    color: '#3b82f6',
                    bgColor: 'rgba(59, 130, 246, 0.1)'
                },
                orders: {
                    label: 'Pesanan',
                    labels: orderLabels,
                    data: orderValues,
                    color: '#10b981',
                    bgColor: 'rgba(16, 185, 129, 0.1)'
                },
                views: {
                    label: 'Produk Dilihat',
                    labels: viewsLabels,
                    data: viewsValues,
                    color: '#6366f1',
                    bgColor: 'rgba(99, 102, 241, 0.1)'
                },
                rating: {
                    label: 'Rating',
                    labels: ratingLabels,
                    data: ratingValues,
                    color: '#f59e0b',
                    bgColor: 'rgba(245, 158, 11, 0.1)'
                }
            };
            
            // Fungsi untuk membuat atau memperbarui chart
            function updateChart(type) {
                const chartConfig = {
                    type: 'line',
                    data: {
                        labels: chartData[type].labels,
                        datasets: [{
                            label: chartData[type].label,
                            data: chartData[type].data,
                            backgroundColor: chartData[type].bgColor,
                            borderColor: chartData[type].color,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: chartData[type].color,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            if (type === 'revenue') {
                                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                            } else if (type === 'rating') {
                                                label += context.parsed.y.toFixed(1);
                                            } else {
                                                label += context.parsed.y;
                                            }
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 10
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (type === 'revenue') {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        } else if (type === 'rating') {
                                            return value.toFixed(1);
                                        }
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                };
                
                if (mainChart) {
                    mainChart.destroy();
                }
                
                mainChart = new Chart(chartCtx, chartConfig);
                
                // Update judul chart
                document.getElementById('chartTitle').textContent = `Tren ${chartData[type].label}`;
                
                // Update kelas aktif pada card
                document.querySelectorAll('[data-chart-type]').forEach(card => {
                    if (card.dataset.chartType === type) {
                        card.classList.add('ring-2', 'ring-blue-500');
                    } else {
                        card.classList.remove('ring-2', 'ring-blue-500');
                    }
                });
                
                currentChart = type;
            }
            
            // Inisialisasi chart pertama kali
            try {
                updateChart('revenue');
                
                // Inisialisasi chart status pesanan
                const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
                const orderStatusChart = new Chart(orderStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai', 'Dalam Proses', 'Dibatalkan'],
                        datasets: [{
                            data: [50, 40, 10], // Data dummy
                            backgroundColor: ['#10b981', '#3b82f6', '#ef4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
                
                // Inisialisasi chart demografi pelanggan
                const demographicsCtx = document.getElementById('customerDemographicsChart').getContext('2d');
                const demographicsChart = new Chart(demographicsCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Pelanggan Berulang', 'Pelanggan Baru'],
                        datasets: [{
                            data: [30, 70], // Data dummy
                            backgroundColor: ['#3b82f6', '#10b981'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing charts:', error);
            }
            
            // Event listener untuk card
            document.querySelectorAll('[data-chart-type]').forEach(card => {
                card.addEventListener('click', function() {
                    updateChart(this.dataset.chartType);
                });
            });
        });
    </script>
</x-merchant-layout>