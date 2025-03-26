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
                    
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Informasi Pembayaran</h3>
                                <p class="text-sm text-gray-500">Selesaikan pembayaran sebelum batas waktu</p>
                            </div>
                            <div class="flex items-center gap-1 text-red-500">
                                <span id="countdown-hours">23</span>:<span id="countdown-minutes">59</span>:<span id="countdown-seconds">59</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-b border-gray-200 py-4 my-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Nomor Virtual Account</span>
                                <div class="flex items-center">
                                    <span id="va-number" class="font-medium text-gray-800">{{ $va_number ?? '80777081386348589' }}</span>
                                    <button onclick="copyToClipboard('va-number')" class="ml-2 text-homize-blue hover:text-homize-blue-second">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Tagihan</span>
                                <div class="flex items-center">
                                    <span id="total-amount" class="font-bold text-gray-800">Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}</span>
                                    <button onclick="copyToClipboard('total-amount')" class="ml-2 text-homize-blue hover:text-homize-blue-second">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2 text-sm text-gray-600">
                            <p>• Penting: Transfer Virtual Account hanya bisa dilakukan dari {{ $bank_name ?? 'bank yang kamu pilih' }}</p>
                            <p>• Transaksi kamu baru akan diteruskan ke penjual setelah pembayaran berhasil diverifikasi.</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between gap-4">
                        <a href="{{ route('pembayaran.show', $booking->id) }}" class="flex-1 py-3 px-4 border border-gray-300 rounded-xl text-center text-gray-700 hover:bg-gray-50 transition">
                            Kembali
                        </a>
                        <a href="{{ route('dashboard') }}?check_payment={{ $booking->id }}" class="flex-1 py-3 px-4 bg-homize-blue text-white rounded-xl text-center hover:bg-homize-blue-second transition">
                            Cek Status Bayar
                        </a>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Cara Bayar</h3>
                        
                        <div class="space-y-4">
                            <div class="border rounded-lg overflow-hidden">
                                <button class="w-full flex items-center justify-between p-4 text-left focus:outline-none" onclick="toggleAccordion('atm')">
                                    <span class="font-medium">ATM {{ $bank_name ?? 'Bank' }}</span>
                                    <svg id="atm-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="atm-content" class="hidden p-4 border-t">
                                    <ol class="list-decimal pl-5 space-y-2">
                                        <li>Masukkan kartu ATM dan PIN Anda</li>
                                        <li>Pilih menu "Transaksi Lainnya" > "Transfer"</li>
                                        <li>Pilih jenis rekening yang akan digunakan</li>
                                        <li>Masukkan nomor Virtual Account: <span class="font-medium">{{ $va_number ?? '80777081386348589' }}</span></li>
                                        <li>Masukkan jumlah transfer: <span class="font-medium">Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}</span></li>
                                        <li>Ikuti instruksi untuk menyelesaikan transaksi</li>
                                    </ol>
                                </div>
                            </div>
                            
                            <div class="border rounded-lg overflow-hidden">
                                <button class="w-full flex items-center justify-between p-4 text-left focus:outline-none" onclick="toggleAccordion('mobile')">
                                    <span class="font-medium">Mobile Banking</span>
                                    <svg id="mobile-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="mobile-content" class="hidden p-4 border-t">
                                    <ol class="list-decimal pl-5 space-y-2">
                                        <li>Login ke aplikasi Mobile Banking</li>
                                        <li>Pilih menu "Transfer"</li>
                                        <li>Pilih menu "Virtual Account"</li>
                                        <li>Masukkan nomor Virtual Account: <span class="font-medium">{{ $va_number ?? '80777081386348589' }}</span></li>
                                        <li>Masukkan jumlah transfer: <span class="font-medium">Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}</span></li>
                                        <li>Konfirmasi detail pembayaran</li>
                                        <li>Masukkan PIN atau password untuk menyelesaikan transaksi</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Fungsi untuk menyalin teks ke clipboard
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.innerText;
            
            navigator.clipboard.writeText(text).then(() => {
                // Tampilkan notifikasi berhasil disalin
                alert('Berhasil disalin: ' + text);
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        }
        
        // Fungsi untuk toggle accordion
        function toggleAccordion(id) {
            const content = document.getElementById(id + '-content');
            const icon = document.getElementById(id + '-icon');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
        
        // Countdown timer
        document.addEventListener('DOMContentLoaded', function() {
            // Set waktu countdown (24 jam)
            let hours = 23;
            let minutes = 59;
            let seconds = 59;
            
            const hoursElement = document.getElementById('countdown-hours');
            const minutesElement = document.getElementById('countdown-minutes');
            const secondsElement = document.getElementById('countdown-seconds');
            
            const countdown = setInterval(function() {
                seconds--;
                
                if (seconds < 0) {
                    seconds = 59;
                    minutes--;
                    
                    if (minutes < 0) {
                        minutes = 59;
                        hours--;
                        
                        if (hours < 0) {
                            clearInterval(countdown);
                            // Redirect ke halaman expired atau tampilkan pesan
                            alert('Waktu pembayaran telah habis');
                            window.location.href = "{{ route('dashboard') }}";
                            return;
                        }
                    }
                }
                
                hoursElement.textContent = hours
                hoursElement.textContent = hours < 10 ? '0' + hours : hours;
                minutesElement.textContent = minutes < 10 ? '0' + minutes : minutes;
                secondsElement.textContent = seconds < 10 ? '0' + seconds : seconds;
            }, 1000);
        });
    </script>
</body>
</html>