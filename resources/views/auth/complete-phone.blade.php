<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Lengkapi Nomor Telepon</h2>
            @if (session('error'))
                <div class="p-3 mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('complete.phone.submit') }}" id="completePhoneForm">
                @csrf
                
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center px-3 text-gray-500 text-sm border-r-[1px] border-gray-300">+62</span>
                        <input id="phone" name="phone" type="text" inputmode="numeric" pattern="[0-9]*" placeholder="8123456789" class="w-full pl-14 pr-3 py-2 border border-gray-300 rounded-md focus:ring-homize-blue focus:border-homize-blue shadow-sm" required autofocus value="{{ old('phone') }}" />
                    </div>
                    @error('phone')
                        <p class="mt-1 text-sm text-homize-error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" id="completePhoneBtn" class="w-full bg-homize-blue hover:bg-[#0EA5E9] text-white py-3 rounded-md font-medium shadow-sm transition-colors duration-200">Simpan &amp; Lanjutkan</button>

            </form>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('completePhoneForm');
        const btn = document.getElementById('completePhoneBtn');
        if (form && btn) {
            form.addEventListener('submit', function() {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });
        }
    });
</script>
</x-guest-layout>
