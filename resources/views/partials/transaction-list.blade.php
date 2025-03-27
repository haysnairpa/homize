@foreach ($bookings as $booking)
    <div class="space-y-4 mt-5">
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-4">
                <div class="font-semibold text-homize-blue"> {{ $booking->nama_status }} </div>
                <div class="text-green-500">Your service is {{ $booking->nama_status }} </div>
            </div>
            <div class="flex gap-4">
                <img src="{{ $booking->profile_url }}" alt="Laundry" class="w-20 h-20 object-cover rounded">
                <div class="flex-1">
                    <div class="font-medium">{{ $booking->nama_sub_kategori }}</div>
                    <div class="text-sm text-gray-600">{{ $booking->nama_layanan }}</div>
                </div>
                <div class="text-right">
                    <div class="font-medium text-homize-blue">Rp. {{ $booking->amount }}</div>
                    <button
                        class="mt-2 px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors text-sm">
                        Pesan Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
