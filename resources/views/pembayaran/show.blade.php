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
                             <!-- Informasi Pembeli -->
                             <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembeli</h3>
                                
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Nama</p>
                                        <p class="text-gray-700">{{ $booking->first_name }} {{ $booking->last_name }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="text-gray-700">{{ $booking->contact_email }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Nomor Telepon</p>
                                        <p class="text-gray-700">{{ $booking->contact_phone ?? 'Tidak ada' }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Alamat</p>
                                        <p class="text-gray-700">{{ $booking->alamat_pembeli }}</p>
                                    </div>
                                </div>
                            </div>

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
                                            @if($booking->status_proses == 'Dikonfirmasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Dikonfirmasi
                                                </span>
                                            @elseif($booking->status_proses == 'Dibatalkan')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    {{ $booking->status_proses ?? 'Menunggu Konfirmasi' }}
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
                                        <img src="{{ $booking->merchant->profile_url ? $booking->merchant->profile_url : asset('images/default-merchant.png') }}" 
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
                                            @if($booking->pembayaran->status_pembayaran == 'Selesai')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Pembayaran Selesai
                                                </span>
                                            @elseif($booking->pembayaran->status_pembayaran == 'Dibatalkan')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Pembayaran Gagal
                                                </span>
                                            @elseif($booking->pembayaran->status_pembayaran == 'Pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Menunggu Pembayaran
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($booking->pembayaran->status_pembayaran == 'Pending')
                                    <div class="mt-4 space-y-3">
                                        <a href="{{ route('pembayaran.bsi-transfer', $booking->id) }}" class="w-full bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-4 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            Bayar Sekarang
                                        </a>
                                        
                                        <form action="{{ route('pembayaran.process', $booking->id) }}" method="GET" id="payment-form">
                                            <input type="hidden" name="payment_method" value="bank_transfer">
                                            
                                            <button type="submit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Metode Pembayaran Lainnya
                                            </button>
                                        </form>
                                    </div>
                                    @elseif($booking->pembayaran->status_pembayaran == 'Dibatalkan')
                                    <div class="mt-4">
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                            <p class="text-red-700">Pembayaran dibatalkan. Anda dapat mencoba membayar ulang dengan menekan tombol di bawah ini.</p>
                                        </div>
                                        <form action="{{ route('pembayaran.process', $booking->id) }}" method="GET" id="payment-form-ulang">
                                            <input type="hidden" name="payment_method" value="bank_transfer">
                                            
                                            <button type="submit" class="w-full bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-4 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                </svg>
                                                Coba Bayar Lagi
                                            </button>
                                        </form>
                                        <div class="mt-4">
                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Petunjuk Pembayaran Ulang</h3>
                                                <div class="space-y-4">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">1</div>
                                                        <p class="text-gray-600">Klik tombol "Coba Bayar Lagi" untuk melanjutkan</p>
                                                    </div>
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">2</div>
                                                        <p class="text-gray-600">Ikuti petunjuk pembayaran yang muncul dan selesaikan pembayaran</p>
                                                    </div>
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">3</div>
                                                        <p class="text-gray-600">Status pembayaran akan diperbarui secara otomatis setelah pembayaran berhasil</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                        <p class="text-gray-600">Klik tombol "Bayar Sekarang" untuk melanjutkan</p>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">
                                            2
                                        </div>
                                        <p class="text-gray-600">Ikuti petunjuk pembayaran yang muncul dan selesaikan pembayaran</p>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-homize-blue text-white text-sm font-medium mr-3">
                                            3
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
    
    <!-- Xendit JS -->
    @if($booking->pembayaran->status_pembayaran == 'Pending')
    <script src="https://js.xendit.co/xendit.min.js"></script>
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
    <div id="payment-confirmation-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 items-center justify-center z-50 hidden">
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
                    confirmationModal.style.display = 'flex';
                });
            }
            
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    confirmationModal.classList.add('hidden');
                    confirmationModal.style.display = 'none';
                });
            }
            
            if (confirmButton) {
                confirmButton.addEventListener('click', function() {
                    confirmationModal.classList.add('hidden');
                    confirmationModal.style.display = 'none';
                    // Lanjutkan dengan proses pembayaran
                    document.getElementById('payment-form').submit();
                });
            }
        });
    </script>

</body>
</html>