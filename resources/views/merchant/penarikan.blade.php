<x-merchant-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-homize-blue leading-tight">
                {{ __('Penarikan Saldo') }}
            </h2>
        </x-slot>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6">
                <div class="font-bold mb-2">Terjadi kesalahan:</div>
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Bank Accounts -->
            <div class="lg:col-span-1">
                <div class="mb-6 bg-white rounded-lg shadow-sm p-4 border-l-4 border-homize-blue">
                    <span class="text-sm text-gray-600">Saldo Tersedia</span>
                    <div class="text-homize-blue text-2xl font-bold">
                        Rp {{ number_format($saldo, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-homize-blue p-4">
                    <h3 class="text-lg text-homize-blue font-semibold">Rekening Bank</h3>
                    <!-- Bank Account List -->
                    <div class="mt-4">
                        @if(count($rekenings) > 0)
                            <div class="space-y-3 mb-6">
                                @foreach($rekenings as $rek)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-md border border-gray-200">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 rounded-full bg-homize-blue-second flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($rek->nama_bank, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow">
                                            <div class="font-medium">{{ $rek->nama_bank }}</div>
                                            <div class="text-sm text-gray-600">{{ $rek->nomor_rekening }}</div>
                                            <div class="text-xs text-gray-500 capitalize">{{ $rek->nama_pemilik }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <p>Belum ada rekening terdaftar.</p>
                                <p class="text-sm mt-1">Tambahkan rekening untuk melakukan penarikan.</p>
                            </div>
                        @endif
                        
                        <!-- Add Bank Account Form -->
                        <div class="mt-4">
                            <button type="button" class="text-homize-blue hover:text-homize-blue-second font-medium flex items-center w-full justify-center" 
                                    onclick="toggleBankForm()" id="toggleBankFormBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Tambah Rekening Baru
                            </button>
                            
                            <form action="{{ route('merchant.penarikan.tambahRekening') }}" method="POST" 
                                class="mt-4 hidden transition-all duration-300" id="bankForm">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                                        <input type="text" name="nama_bank" id="nama_bank" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue" 
                                                placeholder="Contoh: BCA, Mandiri">
                                    </div>
                                    <div>
                                        <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                                        <input type="text" name="nomor_rekening" id="nomor_rekening" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue" 
                                                placeholder="Masukkan nomor rekening">
                                    </div>
                                    <div>
                                        <label for="nama_pemilik" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik</label>
                                        <input type="text" name="nama_pemilik" id="nama_pemilik" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue" 
                                                placeholder="Nama pemilik rekening">
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-2 px-4 rounded-md transition duration-200">
                                        Simpan Rekening
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Withdrawal Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-homize-blue px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Form Penarikan Dana</h3>
                    </div>
                    
                    <div class="p-6">
                        @if(count($rekenings) > 0)
                            <form action="{{ route('merchant.penarikan.ajukan') }}" method="POST">
                                @csrf
                                <div class="space-y-6">
                                    <!-- Withdrawal Amount -->
                                    <div>
                                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Penarikan</label>
                                        <div class="relative mt-1 rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" name="jumlah" id="jumlah" 
                                                class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue text-lg" 
                                                placeholder="0" >
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-400 sm:text-sm">IDR</span>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">Minimal penarikan Rp 20.000</p>
                                    </div>
                                    
                                    <!-- Bank Selection -->
                                    <div>
                                        <label for="rekening_id" class="block text-sm font-medium text-gray-700 mb-1">Rekening Tujuan</label>
                                        <select name="rekening_id" id="rekening_id" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-homize-blue focus:border-homize-blue" 
                                                >
                                            <option value="" disabled selected>Pilih rekening tujuan</option>
                                            @foreach($rekenings as $rek)
                                                <option value="{{ $rek->id }}">
                                                    {{ $rek->nama_bank }} - {{ $rek->nomor_rekening }} ({{ $rek->nama_pemilik }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Information Card -->
                                    <div class="bg-homize-orange bg-opacity-20 rounded-md p-4 border border-homize-orange">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">Informasi Penarikan</h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li>Penarikan diproses dalam 1-3 hari kerja</li>
                                                        <li>Pastikan data rekening sudah benar</li>
                                                        <li>Biaya admin ditanggung oleh platform</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <button type="submit" 
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Ajukan Penarikan Dana
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Rekening Terdaftar</h3>
                                <p class="text-gray-500 mb-4">Anda perlu menambahkan rekening bank terlebih dahulu untuk melakukan penarikan dana.</p>
                                <button type="button" onclick="toggleBankForm()" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-homize-blue hover:bg-homize-blue-second focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-homize-blue">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Tambah Rekening Baru
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Withdrawal History Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                    <div class="bg-homize-blue px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-white">Riwayat Penarikan</h3>
                        
                    </div>
                    <div class="p-6">
                        @if(isset($penarikans) && count($penarikans) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rekening Tujuan</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($penarikans as $penarikan)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $penarikan->created_at->format('d M Y H:i') }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-homize-blue font-semibold">Rp {{ number_format($penarikan->jumlah, 0, ',', '.') }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                    {{ optional($penarikan->rekening_merchant)->nama_bank ?? '-' }}<br>
                                                    <span class="text-xs text-gray-500">{{ optional($penarikan->rekening_merchant)->nomor_rekening ?? '-' }}</span>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                    @if($penarikan->status == 'diterima')
                                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Diterima</span>
                                                    @elseif($penarikan->status == 'ditolak')
                                                        <span class="inline-block px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Ditolak</span>
                                                    @else
                                                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">Menunggu</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                    @if($penarikan->status == 'Ditolak')
                                                        {{ $penarikan->catatan }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p>Belum ada riwayat penarikan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for toggle functionality -->
    <script>
        function toggleBankForm() {
            const form = document.getElementById('bankForm');
            const button = document.getElementById('toggleBankFormBtn');
            
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    Tutup Form
                `;
            } else {
                form.classList.add('hidden');
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Rekening Baru
                `;
            }
        }
    </script>
</x-merchant-layout>