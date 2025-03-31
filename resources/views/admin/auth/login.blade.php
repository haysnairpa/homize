<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Homize</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus {
            box-shadow: 0 0 0 2px rgba(48, 160, 224, 0.2);
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-homize-gray min-h-screen flex flex-col items-center justify-center p-4 sm:p-6">
    <div class="animate-fade-in w-full max-w-md">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/homizelogoblue.png') }}" alt="Homize Logo" class="h-12">
        </div>
        
        <!-- Card -->
        <div class="bg-homize-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-8 pt-8 pb-6">
                <h1 class="text-2xl font-medium text-gray-800 text-center">Admin Login</h1>
                <p class="mt-2 text-center text-gray-500 text-sm">Enter your credentials to access the admin panel</p>
            </div>
            
            <!-- Error Message -->
            @if(session('error'))
                <div class="mx-8 mb-4 p-3 bg-red-50 border border-red-100 rounded-lg">
                    <p class="text-red-600 text-sm">{{ session('error') }}</p>
                </div>
            @endif
            
            <!-- Form -->
            <form method="POST" action="{{ route('admin.login.post') }}" class="px-8 pb-8">
                @csrf
                
                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required  
                        autofocus 
                        autocomplete="username"
                        class="form-input w-full px-4 py-3 mdpx-4 md:py-3 rounded-xl border border-gray-200 focus:border-homize-blue focus:outline-none transition-all duration-200"
                    >
                    @error('email')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-homize-blue focus:outline-none transition-all duration-200"
                    >
                    @error('password')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="mb-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember" 
                            class="rounded border-gray-300 text-homize-blue shadow-sm focus:border-homize-blue focus:ring focus:ring-homize-blue focus:ring-opacity-20"
                        >
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>
                
                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="w-full bg-homize-blue hover:bg-homize-blue-second text-homize-white font-medium py-3 px-4 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-homize-blue focus:ring-opacity-50"
                >
                    Sign In
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <p class="mt-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Homize. All rights reserved.
        </p>
    </div>
</body>
</html>