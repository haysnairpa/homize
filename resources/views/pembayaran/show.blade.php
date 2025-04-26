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
                                            @elseif($booking->pembayaran->status->nama_status == 'Payment Pending' || $booking->pembayaran->status->nama_status == 'Pending')
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
                                    @elseif($booking->pembayaran->status->nama_status == 'Payment Failed')
                                    <div class="mt-4">
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                            <p class="text-red-700">Pembayaran gagal. Silakan coba lagi.</p>
                                        </div>
                                        
                                        <a href="{{ route('pembayaran.process', $booking->id) }}" class="w-full bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-4 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            Coba Bayar Lagi
                                        </a>
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
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
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
    
    <!-- Script untuk polling status pembayaran -->
    <script>
        // Cek status pembayaran setiap 5 detik
        function checkPaymentStatus() {
            fetch('{{ route('pembayaran.check-status', $booking->id) }}')
                .then(response => response.json())
                .then(data => {
                    console.log('Status check response:', data);
                    if (data.status === 'completed') {
                        // Jika pembayaran selesai, redirect ke dashboard
                        window.location.href = '{{ route('dashboard') }}?success=true';
                    } else if (data.status === 'failed') {
                        // Jika pembayaran gagal, tampilkan pesan
                        alert('Pembayaran gagal. Silakan coba lagi.');
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                });
        }
        
        // Cek status setiap 5 detik
        setInterval(checkPaymentStatus, 5000);
        
        // Cek status saat halaman dimuat
        document.addEventListener('DOMContentLoaded', checkPaymentStatus);
    </script>

    <!-- Tambahkan modal konfirmasi pembayaran -->
    <div id="payment-confirmation-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Pembayaran</h3>
            <p class="text-gray-600 mb-4">Anda akan melakukan pembayaran sebesar:</p>
            <p class="text-xl font-bold text-gray-800 mb-6">Rp {{ number_format($booking->pembayaran->amount, 0, ',', '.') }}</p>
            
            <p class="text-sm text-gray-500 mb-6">Setelah mengklik "Lanjutkan", Anda tidak dapat mengubah metode pembayaran.</p>
            
            <div class="flex justify-between gap-4">
                <button id="cancel-payment" class="flex-1 py-2 px-4 border border-gray-300 rounded-xl text-center text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button id="confirm-payment" class="flex-1 py-2 px-4 bg-homize-blue text-white rounded-xl text-center hover:bg-homize-blue-second transition">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <script>
        // Tambahkan script untuk modal konfirmasi
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            const confirmationModal = document.getElementById('payment-confirmation-modal');
            const cancelButton = document.getElementById('cancel-payment');
            const confirmButton = document.getElementById('confirm-payment');
            
            if (payButton) {
                payButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    confirmationModal.classList.remove('hidden');
                });
            }
            
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    confirmationModal.classList.add('hidden');
                });
            }
            
            if (confirmButton) {
                confirmButton.addEventListener('click', function() {
                    confirmationModal.classList.add('hidden');
                    // Lanjutkan dengan proses pembayaran
                    document.getElementById('payment-form').submit();
                });
            }
        });
    </script>

    <!-- Tambahkan informasi tentang status pembayaran -->
    @if($booking->pembayaran->status->nama_status == 'Payment Pending')
        <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Jika Anda sudah melakukan pembayaran tetapi status belum berubah, silakan klik tombol di bawah untuk memeriksa status pembayaran.</p>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('pembayaran.check', $booking->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Periksa Status Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('pembayaran.force-check', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
            Cek Status Pembayaran
        </a>
    </div>
</body>
</html>