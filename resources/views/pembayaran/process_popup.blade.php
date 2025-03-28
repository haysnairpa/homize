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
                    
                    @if($pembayaran->otp_attempts > 0)
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
                                    <p>Verifikasi 3DS sebelumnya gagal. Anda memiliki {{ $pembayaran->remainingOtpAttempts() }} kesempatan lagi untuk memasukkan kode OTP yang benar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
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
                        // Cek apakah ini error 3DS
                        if (result.status_code === '202' || 
                            (result.status_message && result.status_message.toLowerCase().includes('3ds'))) {
                            
                            // Tampilkan pesan khusus untuk error 3DS
                            alert('Verifikasi 3DS gagal. Silakan coba lagi dengan kode OTP yang benar.');
                            
                            // Cek apakah masih ada kesempatan
                            @if($pembayaran->hasOtpAttempts())
                                // Masih ada kesempatan, reload halaman
                                window.location.reload();
                            @else
                                // Sudah habis kesempatan
                                alert('Anda telah melebihi batas percobaan OTP. Silakan mulai ulang proses pembayaran.');
                                window.location.href = "{{ route('pembayaran.show', $booking->id) }}";
                            @endif
                        } else {
                            // Error lainnya
                            window.location.href = "{{ route('dashboard') }}?status=error";
                        }
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
                    }
                });
            });
        });

        function checkPaymentStatus() {
            fetch('/pembayaran/{{ $booking->id }}/check-status')
                .then(response => response.json())
                .then(data => {
                    console.log('Payment status:', data.status);
                    if (data.status === 'completed') {
                        // Redirect ke halaman sukses
                        window.location.href = '/dashboard';
                    } else if (data.status === 'failed') {
                        // Tampilkan pesan error
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    } else {
                        // Cek lagi setelah 5 detik
                        setTimeout(checkPaymentStatus, 5000);
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                    // Cek lagi setelah 5 detik
                    setTimeout(checkPaymentStatus, 5000);
                });
        }

        // Mulai cek status setelah halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Mulai cek status setelah 10 detik (beri waktu untuk user melakukan pembayaran)
            setTimeout(checkPaymentStatus, 10000);
        });
    </script>
</body>
</html>