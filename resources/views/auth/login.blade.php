<x-guest-layout>
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Left side with background -->
        <div class="w-full md:w-1/2 bg-homize-blue p-6 md:p-12 relative">
            <div class="absolute top-6 md:top-12 left-6 md:left-12">
                <img src="{{ asset('images/homizelogo.png') }}" alt="Homize Logo" class="h-6 md:h-8">
            </div>
            <div class="flex items-center justify-center h-48 md:h-full">
                <div class="text-white text-center md:text-left">
                    <div class="hidden md:block">
                        <img src="{{ asset('images/homizelogo.png') }}" alt="Homize Icon" class="h-24 md:h-32 mb-4 md:mb-6 mx-auto md:mx-0">
                    </div>
                    <h2 class="text-2xl md:text-3xl font-medium mb-1 md:mb-2">Solve your problem,</h2>
                    <h2 class="text-2xl md:text-3xl font-medium">From your <span class="text-homize-orange">home</span></h2>
                </div>
            </div>
        </div>

        <!-- Right side with form -->
        <div class="w-full md:w-1/2 p-6 md:p-12 flex items-center justify-center bg-white">
            <div class="w-full max-w-md">
                {{-- <div class="flex justify-end mb-4 md:mb-8">
                    <a href="#" class="text-gray-600 hover:text-gray-800 text-sm transition-colors duration-200">Butuh bantuan?</a>
                </div> --}}

                <h1 class="text-xl md:text-2xl font-bold mb-6 md:mb-8 text-gray-800">Log In Akun Homize</h1>

                @if (session('error'))
                    <div class="p-3 mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}" class="space-y-5 md:space-y-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="p-3 md:p-4 mb-4 rounded-lg bg-red-50 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <x-input 
                            id="email" 
                            type="email" 
                            name="email" 
                            placeholder="Masukkan email anda"
                            class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue shadow-sm" 
                            :value="old('email')"
                            required 
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-homize-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <x-input 
                            id="password" 
                            type="password"
                            name="password" 
                            placeholder="Masukkan password anda"
                            class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-md shadow-sm" 
                            required 
                        />
                        <button type="button" data-password-toggle class="absolute right-3 top-[60%] -translate-y-1/2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-homize-blue transition-colors duration-200">Lupa Password?</a>
                    </div>

                    <x-button class="w-full bg-homize-blue hover:bg-[#0EA5E9] text-white py-3 md:py-4 rounded-md font-medium shadow-sm transition-colors duration-200 mt-4">
                        MASUK
                    </x-button>

                    <div class="relative flex items-center my-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="flex-shrink mx-4 text-gray-500 text-sm">ATAU</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <a href="{{ route('login.google') }}" class="flex items-center justify-center gap-2 px-3 py-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200 w-full">
                        <img src="https://cdn.cdnlogo.com/logos/g/35/google-icon.svg" alt="Google" class="w-5 h-5">
                        <span class="font-medium">Masuk dengan Google</span>
                    </a>

                    <div class="text-center text-sm mt-6">
                        Baru di Homize? 
                        <a href="{{ route('register') }}" class="text-homize-blue font-medium hover:underline">Daftar Yuk!</a>
                    </div>

                    <div class="text-center text-xs text-gray-500 mt-4">
                        ©Homize 2025. Hak Cipta Dilindungi
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('[data-password-toggle]');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        const eyeOpen = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                         <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
        const eyeClosed = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />`;
                         
        this.querySelector('svg').innerHTML = type === 'password' ? eyeOpen : eyeClosed;
    });
});
</script>