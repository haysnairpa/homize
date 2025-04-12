<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Status Verifikasi') }}
        </h2>
    </x-slot>
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            @if($merchant->verification_status === 'pending')
                <div class="mb-6">
                    <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Menunggu Verifikasi</h2>
                    <p class="text-gray-600 mb-6">
                        Pendaftaran merchant Anda sedang dalam proses verifikasi. Kami akan memberitahu Anda melalui email setelah proses verifikasi selesai.
                    </p>
                </div>
            @else
                <div class="mb-6">
                    <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Verifikasi Ditolak</h2>
                    <p class="text-gray-600 mb-4">
                        Maaf, pendaftaran merchant Anda ditolak dengan alasan berikut:
                    </p>
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <p class="text-red-700">{{ $merchant->rejection_reason }}</p>
                    </div>
                    <p class="text-gray-600 mb-6">
                        Anda dapat mendaftar ulang dengan memperbaiki data sesuai dengan alasan penolakan di atas.
                    </p>
                    <form action="{{ route('merchant.verification.retry') }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                            Daftar Ulang
                        </button>
                    </form>
                </div>
            @endif

            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition duration-200">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
</x-app-layout>
