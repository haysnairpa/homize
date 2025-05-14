@extends('layouts.guest')

@section('title', 'Hubungi Kami - Homize')

@section('content')
<div class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
    @include('components.navigation')
</div>

<div class="pt-24 pb-16 bg-homize-gray min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-homize-blue to-blue-600 py-12 mb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Hubungi Kami</h1>
                <p class="text-white/90 max-w-2xl mx-auto">
                    Kami siap membantu Anda dengan pertanyaan, saran, atau masukan untuk meningkatkan layanan kami.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Contact Form -->
            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue" placeholder="Masukkan nama lengkap Anda" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue" placeholder="Masukkan alamat email Anda" required>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                        <input type="text" name="subject" id="subject" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue" placeholder="Subjek pesan Anda" required>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                        <textarea name="message" id="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue" placeholder="Tulis pesan Anda di sini..." required></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full bg-homize-blue hover:bg-homize-blue-second text-white font-medium py-3 px-4 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div>
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Informasi Kontak</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-homize-blue/10 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-800">Email</h3>
                                <p class="mt-1 text-gray-600">homizedigitalindonesia@gmail.com</p>
                                <p class="mt-1 text-sm text-gray-500">Kami akan membalas dalam 1-2 hari kerja</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-homize-blue/10 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-800">Telepon</h3>
                                <p class="mt-1 text-gray-600">+6281523740785</p>
                                <p class="mt-1 text-sm text-gray-500">Senin - Jumat, 09:00 - 17:00 WIB</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-homize-blue/10 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-800">Alamat</h3>
                                <p class="mt-1 text-gray-600">Jalan Colombo Nomor 1, Karang Malang,</p>
                                <p class="mt-1 text-gray-600">Kelurahan Caturtunggal, Catur Tunggal,</p>
                                <p class="mt-1 text-gray-600">Depok, Sleman, Daerah Istimewa Yogyakarta, 55281</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Contact Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Jam Operasional</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-homize-blue/10 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-800">Hari Kerja</h3>
                                <p class="mt-1 text-gray-600">Senin - Jumat: 09:00 - 17:00 WIB</p>
                                <p class="mt-1 text-gray-600">Sabtu: 09:00 - 15:00 WIB</p>
                                <p class="mt-1 text-gray-600">Minggu & Hari Libur: Tutup</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="mt-16 bg-white rounded-xl shadow-sm p-6 md:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">Pertanyaan Umum</h2>
            
            <div class="max-w-3xl mx-auto space-y-6">
                <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-900">Bagaimana cara memesan layanan di Homize?</h3>
                        <svg class="h-5 w-5 text-homize-blue" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 text-gray-600">
                        <p>Untuk memesan layanan di Homize, Anda perlu membuat akun terlebih dahulu. Setelah login, pilih layanan yang Anda butuhkan, tentukan jadwal, dan lakukan pembayaran. Kami akan menghubungkan Anda dengan penyedia layanan terbaik sesuai kebutuhan Anda.</p>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-900">Bagaimana sistem pembayaran di Homize?</h3>
                        <svg class="h-5 w-5 text-homize-blue" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 text-gray-600">
                        <p>Homize menyediakan berbagai metode pembayaran yang aman dan nyaman, termasuk transfer bank, e-wallet, dan kartu kredit. Pembayaran akan diproses setelah layanan selesai dilakukan dan Anda menyatakan puas dengan hasilnya.</p>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-900">Bagaimana jika saya tidak puas dengan layanan?</h3>
                        <svg class="h-5 w-5 text-homize-blue" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 text-gray-600">
                        <p>Kepuasan pelanggan adalah prioritas kami. Jika Anda tidak puas dengan layanan yang diberikan, silakan hubungi tim dukungan kami melalui halaman ini atau email. Kami akan meninjau masalah dan memberikan solusi terbaik, termasuk pengerjaan ulang atau pengembalian dana jika diperlukan.</p>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="pb-4">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-900">Apakah Homize tersedia di seluruh Indonesia?</h3>
                        <svg class="h-5 w-5 text-homize-blue" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 text-gray-600">
                        <p>Saat ini, Homize fokus melayani area Yogyakarta dan sekitarnya. Namun, kami terus berkembang dan berencana untuk memperluas layanan ke kota-kota besar lainnya di Indonesia dalam waktu dekat. Pantau terus perkembangan kami untuk informasi terbaru!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
@include('components.footer')

<!-- Alpine.js for FAQ accordion -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
