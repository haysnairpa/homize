<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pembayaran - Homize</title>
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
                <h1 class="text-lg font-semibold text-gray-800 hidden md:block">Detail Pembayaran Layanan</h1>
                <div class="w-8 md:w-24"></div>
            </div>
        </div>
    </nav>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Pembayaran</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informasi Booking -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Booking</h3>
                                
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Layanan</p>
                                        <p class="text-gray-700 font-medium">{{ $booking->layanan->nama_layanan }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Penyedia Layanan</p>
                                        <p class="text-gray-700">{{ $booking->merchant->nama_usaha }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Tanggal & Waktu</p>
                                        <p class="text-gray-700">{{ \Carbon\Carbon::parse($booking->booking_schedule->waktu_mulai)->format('d M Y, H:i') }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Durasi Layanan</p>
                                        <p class="text-gray-700">
                                            {{ $tarifLayanan->durasi }} {{ $tarifLayanan->tipe_durasi }}
                                            ({{ \Carbon\Carbon::parse($booking->booking_schedule->waktu_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($booking->booking_schedule->waktu_selesai)->format('H:i') }})
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Alamat</p>
                                        <p class="text-gray-700">{{ $booking->alamat_pembeli }}</p>
                                    </div>
                                    
                                    @if($booking->catatan)
                                    <div>
                                        <p class="text-sm text-gray-500">Catatan</p>
                                        <p class="text-gray-700">{{ $booking->catatan }}</p>
                                    </div>
                                    @endif
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Status Booking</p>
                                        <div class="flex items-center mt-1">
                                            @if($booking->status->nama_status == 'Confirmed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Terkonfirmasi
                                                </span>
                                            @elseif($booking->status->nama_status == 'Cancelled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Menunggu Konfirmasi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informasi Penyedia Layanan -->
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Penyedia Layanan</h3>
                                
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $booking->merchant->profile_url ? asset('storage/' . $booking->merchant->profile_url) : asset('images/default-merchant.png') }}" 
                                            alt="{{ $booking->merchant->nama_usaha }}" 
                                            class="h-12 w-12 rounded-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $booking->merchant->nama_usaha }}</p>
                                        <p class="text-sm text-gray-500">{{ $booking->merchant->alamat }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Pembayaran -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                                
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">ID Pembayaran</p>
                                        <p class="text-gray-700">#{{ $booking->pembayaran->id }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Total Pembayaran</p>
                                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($booking->pembayaran->amount, 0, ',', '.') }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Status Pembayaran</p>
                                        <div class="flex items-center mt-1">
                                            @if($booking->pembayaran->status->nama_status == 'Payment Completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Pembayaran Selesai
                                                </span>
                                            @elseif($booking->pembayaran->status->nama_status == 'Payment Failed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Pembayaran Gagal
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Menunggu Pembayaran
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($booking->pembayaran->status->nama_status == 'Payment Pending' || $booking->pembayaran->status->nama_status == 'Pending')
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-500 mb-2">Pilih Metode Pembayaran</p>
                                        <form action="{{ route('pembayaran.process', $booking->id) }}" method="GET" id="payment-form">
                                            <div class="grid grid-cols-2 gap-3 mb-4">
                                                <div>
                                                    <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" class="hidden peer" checked>
                                                    <label for="bank_transfer" class="flex items-center p-3 border rounded-lg cursor-pointer peer-checked:border-homize-blue peer-checked:bg-blue-50 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                        </svg>
                                                        <span>Transfer Bank</span>
                                                    </label>
                                                </div>
                                                
                                                <div>
                                                    <input type="radio" name="payment_method" id="e_wallet" value="e_wallet" class="hidden peer">
                                                    <label for="e_wallet" class="flex items-center p-3 border rounded-lg cursor-pointer peer-checked:border-homize-blue peer-checked:bg-blue-50 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                        <span>E-Wallet</span>
                                                    </label>
                                                </div>
                                                
                                                <div>
                                                    <input type="radio" name="payment_method" id="credit_card" value="credit_card" class="hidden peer">
                                                    <label for="credit_card" class="flex items-center p-3 border rounded-lg cursor-pointer peer-checked:border-homize-blue peer-checked:bg-blue-50 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                        <span>Kartu Kredit</span>
                                                    </label>
                                                </div>
                                                
                                                <div>
                                                    <input type="radio" name="payment_method" id="qris" value="qris" class="hidden peer">
                                                    <label for="qris" class="flex items-center p-3 border rounded-lg cursor-pointer peer-checked:border-homize-blue peer-checked:bg-blue-50 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                        </svg>
                                                        <span>QRIS</span>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="w-full bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-4 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                </svg>
                                                Bayar Sekarang
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Petunjuk Pembayaran -->
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Petunjuk Pembayaran</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">
                                            1
                                        </div>
                                        <p class="text-gray-600">Pilih metode pembayaran yang Anda inginkan</p>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">
                                            2
                                        </div>
                                        <p class="text-gray-600">Klik tombol "Bayar Sekarang" untuk melanjutkan</p>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">
                                            3
                                        </div>
                                        <p class="text-gray-600">Ikuti petunjuk pembayaran yang muncul dan selesaikan pembayaran</p>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">
                                            4
                                        </div>
                                        <p class="text-gray-600">Status pembayaran akan diperbarui secara otomatis setelah pembayaran berhasil</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Midtrans JS -->
    @if($booking->pembayaran->status->nama_status == 'Payment Pending' || $booking->pembayaran->status->nama_status == 'Pending')
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
                
                // Ambil snap token dari server
                fetch('{{ route('pembayaran.get-token', $booking->id) }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert('Terjadi kesalahan: ' + data.error);
                            payButton.disabled = false;
                            payButton.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                Bayar Sekarang
                            `;
                            return;
                        }
                        
                        // Buka Snap popup
                        snap.pay(data.token, {
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
                                    Bayar Sekarang
                                `;
                                alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran');
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses pembayaran');
                        payButton.disabled = false;
                        payButton.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Bayar Sekarang
                        `;
                    });
            });
        });
    </script>
    @endif
</body>
</html>