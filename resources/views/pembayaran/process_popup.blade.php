<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - Homize</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" id="homeLink" class="flex items-center">
                    <img src="{{ asset('images/homizelogoblue.png') }}" alt="Homize Logo" class="h-8">
                </a>
                <h1 class="text-lg font-semibold text-gray-800 hidden md:block">Pembayaran Layanan</h1>
                <div class="w-8 md:w-24"></div>
            </div>
        </div>
    </nav>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Proses Pembayaran</h2>
                    
                    <div class="text-center mb-8">
                        <p class="text-gray-600 mb-4">Silakan klik tombol di bawah untuk melanjutkan pembayaran</p>
                        <p class="text-xl font-bold text-gray-800">Total: Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="flex justify-center">
                        <button id="pay-button" class="bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-4 px-8 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Lanjutkan Pembayaran
                        </button>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <a href="{{ route('pembayaran.show', $booking->id) }}" class="text-homize-blue hover:underline">
                            Kembali ke halaman pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Midtrans JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            
            payButton.addEventListener('click', function() {
                // Tampilkan loading
                payButton.disabled = true;
                payButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
                
                // Buka Snap popup dengan token yang sudah ada
                snap.pay('{{ $pembayaran->snap_token }}', {
                    onSuccess: function(result) {
                        window.location.href = "{{ route('dashboard') }}?status=success";
                    },
                    onPending: function(result) {
                        window.location.href = "{{ route('dashboard') }}?status=pending";
                    },
                    onError: function(result) {
                        window.location.href = "{{ route('dashboard') }}?status=error";
                    },
                    onClose: function() {
                        // Jika user menutup popup tanpa menyelesaikan pembayaran
                        payButton.disabled = false;
                        payButton.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Lanjutkan Pembayaran
                        `;
                        alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran');
                    }
                });
            });
        });
    </script>
</body>
</html>