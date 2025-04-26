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
                                <h3 class="text-lg font-medium text-gray-800">Lokasi</h3>
                                <p class="mt-1 text-gray-600">Yogyakarta, Jawa Tengah</p>
                                <p class="mt-1 text-sm text-gray-500">Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Ikuti Kami</h2>
                    
                    <div class="flex space-x-4">
                        <a href="#" class="bg-homize-blue/10 hover:bg-homize-blue/20 p-3 rounded-full transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                            </svg>
                        </a>
                        
                        <a href="#" class="bg-homize-blue/10 hover:bg-homize-blue/20 p-3 rounded-full transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        
                        <a href="#" class="bg-homize-blue/10 hover:bg-homize-blue/20 p-3 rounded-full transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        
                        <a href="#" class="bg-homize-blue/10 hover:bg-homize-blue/20 p-3 rounded-full transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-homize-blue" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </a>
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
