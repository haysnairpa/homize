<x-guest-layout>
    <x-slot name="bodyClass">overflow-hidden</x-slot>
    <div class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-50 via-white to-amber-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 sm:pt-0 overflow-hidden">
        <!-- Enhanced animated background elements -->
        <div class="absolute inset-0 overflow-hidden opacity-20 dark:opacity-15">
            <div class="absolute -left-10 top-10 w-64 h-64 rounded-full mix-blend-multiply filter blur-2xl animate-float" style="background: linear-gradient(135deg, #30A0E0 0%, #2080C0 100%);"></div>
            <div class="absolute left-80 top-20 w-72 h-72 rounded-full mix-blend-multiply filter blur-2xl animate-float-delay-2" style="background: linear-gradient(135deg, #FFC973 0%, #FFB040 100%);"></div>
            <div class="absolute right-20 bottom-20 w-56 h-56 rounded-full mix-blend-multiply filter blur-2xl animate-float-delay-4" style="background: linear-gradient(135deg, #30A0E0 0%, #2080C0 100%);"></div>
            <div class="absolute left-20 bottom-40 w-48 h-48 rounded-full mix-blend-multiply filter blur-2xl animate-float-delay-3" style="background: linear-gradient(135deg, #FFC973 0%, #FFB040 100%);"></div>
        </div>

        <div class="max-w-4xl mx-auto px-6 lg:px-8 z-10">
            <div class="flex flex-col items-center pt-8 sm:justify-center sm:pt-0">
                <!-- Modern glassmorphism card -->
                <div class="w-full px-8 py-12 overflow-hidden bg-white/180 dark:bg-gray-800/70 shadow-2xl rounded-xl backdrop-blur-lg border border-black/20 dark:border-gray-700/30">
                    <div class="text-center">
                        <!-- Enhanced 3D 404 Illustration -->
                        <div class="flex justify-center mb-10 transform hover:scale-105 transition-all duration-700 ease-out">
                            <div class="relative">
                                <!-- Main 404 illustration with 3D effect -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 160" class="w-64 h-48">
                                    <!-- Background circle with subtle gradient -->
                                    <defs>
                                        <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#EBF8FF" class="dark:stop-color-gray-700" />
                                            <stop offset="100%" stop-color="#DBEAFE" class="dark:stop-color-gray-800" />
                                        </linearGradient>
                                        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                                            <feDropShadow dx="0" dy="4" stdDeviation="6" flood-opacity="0.2" />
                                        </filter>
                                    </defs>
                                    
                                    <!-- Main background circle -->
                                    <circle cx="120" cy="80" r="70" fill="url(#bgGradient)" filter="url(#shadow)" />
                                    
                                    <!-- First "4" with 3D effect -->
                                    <g class="first-four" filter="url(#shadow)">
                                        <text x="30%" y="55%" dominant-baseline="middle" text-anchor="middle" class="text-9xl font-black" fill="#30A0E0" stroke="#1E88C7" stroke-width="1">4</text>
                                        <text x="30%" y="54%" dominant-baseline="middle" text-anchor="middle" class="text-9xl font-black" fill="#4DB8FF" stroke="none">4</text>
                                    </g>
                                    
                                    <!-- Center animated element -->
                                    <g transform="translate(120, 80)" class="center-element">
                                        <circle cx="0" cy="0" r="28" fill="#DBEAFE" class="dark:fill-gray-600" filter="url(#shadow)" />
                                        <g class="animate-spin-slow" style="transform-origin: center;">
                                            <circle cx="0" cy="0" r="20" fill="#FFC973" />
                                            <path d="M 0,-20 A 20,20 0 0,1 20,0 L 0,0 Z" fill="#30A0E0" />
                                            <path d="M 20,0 A 20,20 0 0,1 0,20 L 0,0 Z" fill="#FFC973" />
                                            <path d="M 0,20 A 20,20 0 0,1 -20,0 L 0,0 Z" fill="#30A0E0" />
                                            <path d="M -20,0 A 20,20 0 0,1 0,-20 L 0,0 Z" fill="#FFC973" />
                                            <circle cx="0" cy="0" r="6" fill="white" />
                                        </g>
                                    </g>
                                    
                                    <!-- Second "4" with 3D effect -->
                                    <g class="second-four" filter="url(#shadow)">
                                        <text x="70%" y="55%" dominant-baseline="middle" text-anchor="middle" class="text-9xl font-black" fill="#30A0E0" stroke="#1E88C7" stroke-width="1">4</text>
                                        <text x="70%" y="54%" dominant-baseline="middle" text-anchor="middle" class="text-9xl font-black" fill="#4DB8FF" stroke="none">4</text>
                                    </g>
                                </svg>
                                
                                <!-- Enhanced animated elements -->
                                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none">
                                    <!-- Floating question mark -->
                                    <div class="animate-float-slow delay-300 absolute -top-4 right-16">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" fill="#FFC973" filter="drop-shadow(0px 2px 4px rgba(0,0,0,0.1))" />
                                            <path d="M12 17h.01M12 14v-4m0 0a2 2 0 110-4 2 2 0 010 4z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Small decorative elements -->
                                    <div class="absolute -bottom-2 left-20 animate-pulse-slow">
                                        <div class="w-3 h-3 rounded-full bg-blue-400"></div>
                                    </div>
                                    <div class="absolute top-10 left-24 animate-pulse-slow delay-700">
                                        <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced typography -->
                        <h1 class="text-5xl sm:text-6xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-[#30A0E0] to-[#FFC973] mb-4">Halaman Tidak Ditemukan</h1>
                        <p class="text-xl text-white mb-10 max-w-xl mx-auto leading-relaxed">
                            Maaf, sepertinya Anda tersesat di ruang digital. Halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.
                        </p>
                        
                        <!-- Enhanced buttons with micro-interactions -->
                        <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-6 justify-center">
                            <a href="{{ url('/') }}" class="group relative inline-flex items-center px-7 py-3.5 text-base font-semibold bg-gradient-to-r from-[#30A0E0] to-[#1E88C7] border border-transparent rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                <span class="absolute inset-0 w-full h-full transition-all duration-300 ease-out transform translate-y-full bg-gradient-to-r from-[#2590D0] to-[#1A78B7] group-hover:translate-y-0"></span>
                                <span class="absolute inset-0 w-full h-full border-2 border-white/20 rounded-xl"></span>
                                <span class="relative flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Kembali ke Beranda
                                </span>
                            </a>
                            <button onclick="window.history.back()" class="group relative inline-flex items-center px-7 py-3.5 text-base font-semibold text-gray-800 bg-gradient-to-r from-[#FFC973] to-[#FFB953] border border-transparent rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                <span class="absolute inset-0 w-full h-full transition-all duration-300 ease-out transform translate-y-full bg-gradient-to-r from-[#FFB953] to-[#FFA933] group-hover:translate-y-0"></span>
                                <span class="absolute inset-0 w-full h-full border-2 border-white/20 rounded-xl"></span>
                                <span class="relative flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                    </svg>
                                    Kembali
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced footer text -->
                <div class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>Jika Anda yakin alamat yang Anda masukkan benar, silakan 
                        <a href="{{ url('/contact') }}" class="relative text-[#30A0E0] hover:text-[#2590D0] dark:text-[#30A0E0] dark:hover:text-[#50B0F0] transition-colors duration-300 group">
                            hubungi kami
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#30A0E0] group-hover:w-full transition-all duration-300"></span>
                        </a> 
                        untuk bantuan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced animations */
        .animate-float {
            animation: float 10s ease-in-out infinite;
        }
        
        .animate-float-delay-2 {
            animation: float 12s ease-in-out 2s infinite;
        }
        
        .animate-float-delay-3 {
            animation: float 9s ease-in-out 3s infinite;
        }
        
        .animate-float-delay-4 {
            animation: float 11s ease-in-out 4s infinite;
        }
        
        .animate-spin-slow {
            animation: spin 12s linear infinite;
        }
        
        .animate-float-slow {
            animation: float-y 4s ease-in-out infinite;
        }
        
        .animate-pulse-slow {
            animation: pulse 3s ease-in-out infinite;
        }
        
        .delay-300 {
            animation-delay: 300ms;
        }
        
        .delay-700 {
            animation-delay: 700ms;
        }
        
        @keyframes float {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.05);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.95);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        
        @keyframes float-y {
            0%, 100% {
                transform: translateY(-25%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }
            50% {
                transform: translateY(0);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }
        
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(0.8);
            }
        }
    </style>
</x-guest-layout>