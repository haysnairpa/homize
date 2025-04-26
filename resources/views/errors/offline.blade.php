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
                        <!-- Enhanced illustration -->
                        <div class="flex justify-center mb-10 transform hover:scale-105 transition-all duration-700 ease-out">
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 160" class="w-64 h-48">
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
                                    
                                    <!-- WiFi icon with disconnection -->
                                    <g transform="translate(120, 80) scale(1.2)" filter="url(#shadow)">
                                        <!-- WiFi waves (disconnected) -->
                                        <path d="M -40,20 L -30,10 M 40,20 L 30,10" stroke="#30A0E0" stroke-width="4" stroke-linecap="round" />
                                        <path d="M -30,10 L -15,-5 M 30,10 L 15,-5" stroke="#30A0E0" stroke-width="4" stroke-linecap="round" />
                                        <path d="M -15,-5 L 0,-20 M 15,-5 L 0,-20" stroke="#30A0E0" stroke-width="4" stroke-linecap="round" />
                                        
                                        <!-- Disconnection symbol -->
                                        <g class="animate-pulse-slow">
                                            <line x1="-25" y1="-15" x2="25" y2="35" stroke="#FFC973" stroke-width="5" stroke-linecap="round" />
                                            <line x1="-25" y1="-15" x2="25" y2="35" stroke="#E0A040" stroke-width="3" stroke-linecap="round" />
                                        </g>
                                        
                                        <!-- Device -->
                                        <circle cx="0" cy="25" r="8" fill="#30A0E0" />
                                    </g>
                                    
                                    <!-- Text "OFFLINE" -->
                                    <text x="50%" y="85%" dominant-baseline="middle" text-anchor="middle" class="text-2xl font-black" fill="#FFC973" stroke="#E0A040" stroke-width="0.5">OFFLINE</text>
                                </svg>
                                
                                <!-- Enhanced animated elements -->
                                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center pointer-events-none">
                                    <div class="animate-pulse-slow delay-300 absolute -top-4 right-16">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" fill="#FFC973" filter="drop-shadow(0px 2px 4px rgba(0,0,0,0.1))" />
                                            <path d="M12 17h.01M12 14v-4m0 0a2 2 0 110-4 2 2 0 010 4z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced typography -->
                        <h1 class="text-5xl sm:text-6xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-[#30A0E0] to-[#FFC973] mb-4">Anda Sedang Offline</h1>
                        <p class="text-xl text-white mb-10 max-w-xl mx-auto leading-relaxed">
                            Sepertinya koneksi internet Anda terputus. Periksa koneksi internet Anda dan coba lagi.
                        </p>
                        
                        <!-- Connection status indicator -->
                        <div class="flex justify-center mb-8">
                            <div class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <div id="connection-status" class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                <span id="connection-text" class="text-sm font-medium">Tidak ada koneksi</span>
                            </div>
                        </div>
                        
                        <!-- Enhanced buttons with micro-interactions -->
                        <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-6 justify-center">
                            <button onclick="window.location.reload()" class="group relative inline-flex items-center px-7 py-3.5 text-base font-semibold bg-gradient-to-r from-[#30A0E0] to-[#1E88C7] border border-transparent rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                <span class="absolute inset-0 w-full h-full transition-all duration-300 ease-out transform translate-y-full bg-gradient-to-r from-[#2590D0] to-[#1A78B7] group-hover:translate-y-0"></span>
                                <span class="absolute inset-0 w-full h-full border-2 border-white/20 rounded-xl"></span>
                                <span class="relative flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Muat Ulang
                                </span>
                            </button>
                            <button id="check-connection-btn" class="group relative inline-flex items-center px-7 py-3.5 text-base font-semibold text-gray-800 bg-gradient-to-r from-[#FFC973] to-[#FFB953] border border-transparent rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                <span class="absolute inset-0 w-full h-full transition-all duration-300 ease-out transform translate-y-full bg-gradient-to-r from-[#FFB953] to-[#FFA933] group-hover:translate-y-0"></span>
                                <span class="absolute inset-0 w-full h-full border-2 border-white/20 rounded-xl"></span>
                                <span class="relative flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Periksa Koneksi
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced footer text -->
                <div class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>Beberapa fitur mungkin tetap tersedia dalam mode offline.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Check connection status periodically
        function updateConnectionStatus() {
            const statusDot = document.getElementById('connection-status');
            const statusText = document.getElementById('connection-text');
            
            if (navigator.onLine) {
                statusDot.classList.remove('bg-red-500');
                statusDot.classList.add('bg-green-500');
                statusText.textContent = 'Koneksi tersedia';
                
                // Try to fetch a resource to confirm actual connectivity
                fetch('{{ url('/') }}', { method: 'HEAD', cache: 'no-store' })
                    .then(() => {
                        statusDot.classList.remove('bg-red-500', 'bg-yellow-500');
                        statusDot.classList.add('bg-green-500');
                        statusText.textContent = 'Koneksi tersedia';
                    })
                    .catch(() => {
                        statusDot.classList.remove('bg-green-500', 'bg-red-500');
                        statusDot.classList.add('bg-yellow-500');
                        statusText.textContent = 'Koneksi terbatas';
                    });
            } else {
                statusDot.classList.remove('bg-green-500', 'bg-yellow-500');
                statusDot.classList.add('bg-red-500');
                statusText.textContent = 'Tidak ada koneksi';
            }
        }
        
        // Initial check
        updateConnectionStatus();
        
        // Listen for online/offline events
        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);
        
        // Check connection button
        document.getElementById('check-connection-btn').addEventListener('click', updateConnectionStatus);
        
        // Check connection status every 10 seconds
        setInterval(updateConnectionStatus, 10000);
    </script>

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
