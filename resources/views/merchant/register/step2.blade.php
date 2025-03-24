<x-app-layout>
    <div class="min-h-screen bg-homize-gray py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 top-1/2 h-0.5 bg-homize-blue w-full -translate-y-1/2 z-0"></div>
                    <div class="relative z-10 flex items-center gap-3 bg-white">
                        <div class="w-8 h-8 rounded-full bg-homize-blue text-white flex items-center justify-center">✓</div>
                        <span class="font-medium text-homize-blue">Informasi Dasar</span>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 bg-white">
                        <div class="w-8 h-8 rounded-full bg-homize-blue text-white flex items-center justify-center">2</div>
                        <span class="font-medium text-homize-blue">Kontak & Lokasi</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('merchant.register.step2.store', $merchant->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea name="alamat" rows="4" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-homize-blue focus:ring-homize-blue py-2 px-4" placeholder="Masukkan alamat lengkap usaha Anda"></textarea>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp (Wajib)</label>
                                    <div class="mt-1 relative rounded-lg shadow-sm border-2 border-gray-300">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">+62</span>
                                        </div>
                                        <input type="text" name="whatsapp" class="block w-full pl-12 rounded-lg border-2 border-gray-300 focus:border-homize-blue focus:ring-homize-blue py-2 px-4" placeholder="8123456789">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Instagram (Opsional)</label>
                                    <div class="mt-1 relative rounded-lg shadow-sm border-2 border-gray-300">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">@</span>
                                        </div>
                                        <input type="text" name="instagram" class="block w-full pl-8 rounded-lg border-2 border-gray-300 focus:border-homize-blue focus:ring-homize-blue py-2 px-4" placeholder="username">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Facebook (Opsional)</label>
                                    <input type="text" name="facebook" class="mt-1 block w-full rounded-lg border-2 border-gray-300 shadow-sm focus:border-homize-blue focus:ring-homize-blue py-2 px-4" placeholder="Username atau URL Facebook">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between pt-6">
                            <a href="{{ route('merchant.register.step1') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-base font-medium gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                                </svg>
                                Kembali
                            </a>
                            <button type="submit" class="inline-flex items-center px-8 py-3 bg-homize-blue text-white rounded-lg hover:bg-homize-blue-second transition-colors text-base font-medium">
                                Selesai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 