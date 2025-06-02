@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 text-white p-4">
            <h1 class="text-xl font-bold">Persetujuan Pesanan #{{ $booking->id }}</h1>
        </div>
        
        <div class="p-6">
            <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-200">
                <p class="text-blue-800">
                    <span class="font-bold">Penting:</span> Merchant telah menandai pesanan ini sebagai selesai. 
                    Mohon periksa pesanan Anda dan berikan persetujuan atau ajukan protes jika ada masalah.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h2 class="text-lg font-semibold mb-3 text-gray-800">Detail Pesanan</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Layanan:</span>
                            <span class="ml-2">{{ $booking->layanan->nama_layanan }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Merchant:</span>
                            <span class="ml-2">{{ $booking->merchant->nama_usaha }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Tanggal Pesanan:</span>
                            <span class="ml-2">{{ $booking->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Tanggal Selesai:</span>
                            <span class="ml-2">{{ $booking->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Status:</span>
                            <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">{{ $booking->status_proses }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold mb-3 text-gray-800">Detail Pembayaran</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Total Pembayaran:</span>
                            <span class="ml-2 font-bold">Rp {{ number_format($booking->pembayaran->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Metode Pembayaran:</span>
                            <span class="ml-2">{{ ucfirst($booking->pembayaran->method) }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium text-gray-600">Status Pembayaran:</span>
                            <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">{{ $booking->pembayaran->status_pembayaran }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-3 text-gray-800">Catatan Pesanan</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p>{{ $booking->catatan ?: 'Tidak ada catatan' }}</p>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Persetujuan Pesanan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200 mb-4">
                            <h3 class="font-semibold text-green-800 mb-2">Setuju</h3>
                            <p class="text-green-700 mb-4">Jika pesanan telah selesai dengan baik dan sesuai dengan harapan Anda, silakan setujui pesanan ini.</p>
                            <form action="{{ route('customer.approval.approve', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                    Setuju & Selesaikan
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div>
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <h3 class="font-semibold text-red-800 mb-2">Protes</h3>
                            <p class="text-red-700 mb-4">Jika ada masalah dengan pesanan, silakan ajukan protes dengan menjelaskan alasannya.</p>
                            <form action="{{ route('customer.approval.protest', $booking->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="protest_reason" class="block text-red-700 text-sm font-bold mb-2">Alasan Protes:</label>
                                    <textarea id="protest_reason" name="protest_reason" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('protest_reason') border-red-500 @enderror" placeholder="Jelaskan masalah yang Anda alami..."></textarea>
                                    @error('protest_reason')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                    Kirim Protes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
