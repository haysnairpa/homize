<x-guest-layout>
    <div class="flex min-h-screen">
        <!-- Left side with background -->
        <div class="flex-1 bg-homize-blue p-12 relative">
            <div class="absolute top-12 left-12">
                <img src="{{ asset('images/homizelogo.png') }}" alt="Homize Logo" class="h-8">
            </div>
            <div class="flex items-center justify-center h-full">
                <div class="text-white">
                    <img src="{{ asset('images/homizelogo.png') }}" alt="Homize Icon" class="h-32 mb-6">
                    <h2 class="text-3xl font-medium mb-2">Solve your problem,</h2>
                    <h2 class="text-3xl font-medium">From your <span class="text-homize-orange">home</span></h2>
                </div>
            </div>
        </div>

        <!-- Right side with form -->
        <div class="flex-1 p-12 flex items-center justify-center">
            <div class="w-full max-w-md">
                <div class="flex justify-end mb-12">
                    <a href="#" class="text-gray-600">Butuh bantuan?</a>
                </div>

                <h1 class="text-2xl font-semibold mb-8">Log In Akun Homize</h1>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-input 
                                id="email" 
                                type="email" 
                                name="email" 
                                placeholder="No HandPhone/EMail"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md" 
                                required 
                            />
                        </div>

                        <div class="relative">
                            <x-input 
                                id="password" 
                                type="password"
                                name="password" 
                                placeholder="Password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md" 
                                required 
                            />
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('password.request') }}" class="text-sm text-gray-600">Lupa Password</a>
                        </div>

                        <x-button class="w-full bg-[#38BDF8] hover:bg-[#0EA5E9] text-white py-3 rounded-md">
                            LOG IN
                        </x-button>

                        <div class="text-center text-gray-500">ATAU</div>

                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" class="flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-md">
                                <img src="https://cdn.cdnlogo.com/logos/f/91/facebook-icon.svg" alt="Facebook" class="w-5 h-5">
                                Facebook
                            </button>
                            <button type="button" class="flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-md">
                                <img src="https://cdn.cdnlogo.com/logos/g/35/google-icon.svg" alt="Google" class="w-5 h-5">
                                Google
                            </button>
                        </div>

                        <div class="text-center text-sm mt-4">
                            Baru di Homize? 
                            <a href="{{ route('register') }}" class="text-[#38BDF8]">Daftar Yuk!</a>
                        </div>

                        <div class="text-center text-xs text-gray-500 mt-2">
                            ©Homize 2025. Hak Cipta Dilindungi
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>