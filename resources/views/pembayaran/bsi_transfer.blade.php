<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Transfer Bank - Homize</title>
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
                <h1 class="text-lg font-semibold text-gray-800 hidden md:block">Pembayaran Transfer Bank</h1>
                <div class="w-8 md:w-24"></div>
            </div>
        </div>
    </nav>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Pembayaran Transfer Bank</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Bank Transfer Info -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Rekening Bank</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Bank</p>
                                        <p class="text-gray-700 font-medium">BSI (Bank Syariah Indonesia)</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Nomor Rekening</p>
                                        <p class="text-gray-700 font-medium text-lg">7234155908</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Atas Nama</p>
                                        <p class="text-gray-700 font-medium">A Muh Fathi Katsirun N</p>
                                    </div>
                                    
                                    <button onclick="copyAccountNumber()" class="mt-2 text-homize-blue hover:text-homize-blue-second font-medium flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                        </svg>
                                        Salin Nomor Rekening
                                    </button>
                                </div>
                            </div>
                            
                            <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-100">
                                <h3 class="text-lg font-semibold text-yellow-800 mb-4">Perhatian</h3>
                                <p class="text-yellow-700 mb-2">Mohon perhatikan hal-hal berikut:</p>
                                <ul class="list-disc pl-5 text-yellow-700 space-y-2">
                                    <li>Pastikan Anda membayar dengan nominal yang <strong>TEPAT</strong> sesuai dengan total yang ditampilkan (termasuk kode unik).</li>
                                    <li>Pembayaran dengan nominal yang tidak sesuai akan menyulitkan proses verifikasi.</li>
                                    <li>Mohon cantumkan kode unik pada berita transfer untuk memudahkan verifikasi.</li>
                                    <li>Setelah melakukan pembayaran, silakan kirim bukti transfer melalui WhatsApp.</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-500">ID Pembayaran</p>
                                        <p class="text-gray-700">#{{ $booking->pembayaran->id }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Layanan</p>
                                        <p class="text-gray-700 font-medium">{{ $booking->layanan->nama_layanan }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Penyedia Layanan</p>
                                        <p class="text-gray-700">{{ $booking->merchant->nama_usaha }}</p>
                                    </div>
                                    
                                    <div class="pt-2 border-t border-gray-200">
                                        <p class="text-sm text-gray-500">Harga Layanan</p>
                                        <p class="text-gray-700">{{ $booking->pembayaran->formatted_amount }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Kode Unik</p>
                                        <p class="text-gray-700">Rp {{ number_format($booking->pembayaran->unique_code, 0, ',', '.') }}</p>
                                    </div>
                                    
                                    <div class="pt-2 border-t border-gray-200">
                                        <p class="text-sm font-medium text-gray-800">Total Pembayaran</p>
                                        <p class="text-xl font-bold text-homize-blue">{{ $booking->pembayaran->formatted_total_amount }}</p>
                                        <p class="text-xs text-gray-500 mt-1">*Sudah termasuk kode unik</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-xl border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Pembayaran</h3>
                                
                                <p class="text-gray-700 mb-4">Setelah melakukan pembayaran, silakan kirim bukti pembayaran melalui WhatsApp untuk diverifikasi oleh admin.</p>
                                
                                <a href="{{ $whatsappUrl }}" target="_blank" id="whatsappButton" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-4 px-6 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                    </svg>
                                    Kirim Bukti Pembayaran via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Function to copy account number to clipboard
        function copyAccountNumber() {
            const accountNumber = '7234155908';
            navigator.clipboard.writeText(accountNumber)
                .then(() => {
                    alert('Nomor rekening berhasil disalin!');
                })
                .catch(err => {
                    console.error('Gagal menyalin nomor rekening:', err);
                });
        }
        
        // Track WhatsApp button click
        document.getElementById('whatsappButton').addEventListener('click', function() {
            // Save order to database with pending status
            fetch('{{ route("pembayaran.save-order", $booking->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Order saved with pending status:', data);
            })
            .catch(error => {
                console.error('Error saving order:', error);
            });
        });
    </script>
</body>
</html>
