@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">{{ $kategori->nama }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($jasa as $service)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ $service->profile_url }}" alt="{{ $service->nama_usaha }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold">{{ $service->nama_layanan }}</h2>
                        <p class="text-gray-600 text-sm mb-2">{{ $service->nama_usaha }}</p>
                        <div class="flex items-center mb-2">
                            <span class="text-yellow-400">★</span>
                            <span class="ml-1">{{ number_format($service->rating_avg, 1) }}</span>
                            <span class="text-gray-500 text-sm ml-1">({{ $service->rating_count }} reviews)</span>
                        </div>
                        <p class="text-homize-blue font-bold">
                            Rp {{ number_format($service->harga, 0, ',', '.') }} / {{ $service->satuan }}
                        </p>
                        <p class="text-gray-500 text-sm mt-2">{{ $service->deskripsi_layanan }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
