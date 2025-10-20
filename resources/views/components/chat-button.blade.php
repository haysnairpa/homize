@props(['merchant', 'booking' => null, 'hideIcon' => false])

<!-- Debug info - Merchant ID: {{ $merchant->id ?? 'null' }} -->
<div>
    <form action="{{ route('chat.start') }}" method="POST">
        @csrf
        <input type="hidden" name="merchant_id" value="{{ $merchant->id }}">
        @if ($booking)
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        @endif
        
        <input type="hidden" name="debug_timestamp" value="{{ now() }}">
        <input type="hidden" name="debug_route" value="{{ route('chat.start') }}">
        <input type="hidden" name="debug_merchant_exists" value="{{ $merchant ? 'yes' : 'no' }}">
        <input type="hidden" name="debug_merchant_id" value="{{ $merchant->id ?? 'null' }}">
        <input type="hidden" name="debug_request_method" value="POST">
        <button type="submit" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-homize-blue hover:bg-homize-blue-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-homize-blue']) }}>
            @if(!$hideIcon)
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            @endif
            {{ $slot }}
        </button>
    </form>
</div>
