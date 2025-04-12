<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Detail Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <a href="{{ route('transactions') }}"
                        class="inline-flex items-center text-homize-blue hover:underline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar Transaksi
                    </a>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Transaksi #{{ $transaction->id }}</h2>
                    <div class="mt-2">
                        @php
                            $statusClass = match ($transaction->nama_status) {
                                'Payment Completed' => 'bg-green-100 text-green-800',
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-red-100 text-red-800',
                            };
                        @endphp
                        <span
                            class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ $transaction->nama_status }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Informasi Layanan</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-600">Nama Layanan:</span>
                                <p class="font-medium">{{ $transaction->nama_layanan }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Deskripsi:</span>
                                <p class="font-medium">{{ $transaction->deskripsi_layanan ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Merchant:</span>
                                <p class="font-medium">{{ $transaction->nama_usaha }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Alamat Merchant:</span>
                                <p class="font-medium">{{ $transaction->alamat_merchant ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Detail Pemesanan</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-600">Tanggal Booking:</span>
                                <p class="font-medium">
                                    {{ \Carbon\Carbon::parse($transaction->tanggal_booking)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Waktu Mulai:</span>
                                <p class="font-medium">{{ optional($transaction->waktu_mulai)->format('H:i') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600">Waktu Selesai:</span>
                                <p class="font-medium">
                                    {{ optional($transaction->waktu_selesai)->format('H:i') ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Alamat Anda:</span>
                                <p class="font-medium">{{ $transaction->alamat_pembeli ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Catatan:</span>
                                <p class="font-medium">{{ $transaction->catatan ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pembayaran</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Pembayaran:</span>
                            <span class="text-xl font-bold text-homize-blue">Rp
                                {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        @if ($transaction->id_status == 6)
                            <div class="flex justify-between align-center mt-4">
                                <p class="text-md font-light text-gray-500">Pembayaran anda belum selesai!</p>
                                <a href="{{ route('pembayaran.show', $transaction->id) }}">
                                    <p class="text-lg font-semibold text-homize-blue-second">Lanjutkan Pembayaran</p>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if (!empty($transaction->latitude) && !empty($transaction->longitude))
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4">Lokasi</h3>
                        <div class="h-64 bg-gray-200 rounded-lg">
                            <div id="map" class="h-full w-full rounded-lg"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (!empty($transaction->latitude) && !empty($transaction->longitude))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap"
            async defer></script>
        <script>
            function initMap() {
                const location = {
                    lat: {{ $transaction->latitude }},
                    lng: {{ $transaction->longitude }}
                };
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 15,
                    center: location,
                });
                const marker = new google.maps.Marker({
                    position: location,
                    map: map,
                });
            }
        </script>
    @endif
</x-app-layout>
