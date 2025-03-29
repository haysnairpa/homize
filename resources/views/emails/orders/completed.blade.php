<x-mail::message>
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ asset('images/homizelogoblue.png') }}" alt="Homize Logo" style="max-height: 50px; margin-bottom: 20px;">
    <h1 style="color: #30a0e0; margin: 0; font-size: 24px; font-weight: 700;">Pesanan Anda Sudah Beres! 🎉</h1>
</div>

<div style="background-color: #f0f9ff; border-radius: 8px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #30a0e0;">
    <p style="margin-top: 0; font-size: 16px;">Halo <strong>{{ $booking->user->nama }}</strong>,</p>
    <p style="margin-top: 0; font-size: 16px;">Yeay! Pesanan Anda sudah selesai. Berikut detailnya:</p>
</div>

<table style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 25px; border-radius: 8px; overflow: hidden; border: 1px solid #E5E7EB;">
    <tr style="background-color: #30a0e0; color: white;">
        <th colspan="2" style="padding: 12px 15px; text-align: left; font-size: 16px;">Detail Pesanan #{{ $booking->id }}</th>
    </tr>
    <tr style="background-color: white;">
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB; width: 40%; font-weight: 600;">Layanan</td>
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB;">{{ $booking->layanan->nama_layanan }}</td>
    </tr>
    <tr style="background-color: #f0f9ff;">
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB; font-weight: 600;">Penyedia Layanan</td>
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB;">{{ $booking->merchant->nama_usaha }}</td>
    </tr>
    <tr style="background-color: white;">
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB; font-weight: 600;">Tanggal</td>
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB;">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</td>
    </tr>
    <tr style="background-color: #f0f9ff;">
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB; font-weight: 600;">Total Pembayaran</td>
        <td style="padding: 12px 15px; font-weight: 700; color: #30a0e0;">Rp {{ number_format($booking->pembayaran->amount, 0, ',', '.') }}</td>
    </tr>
</table>

<div style="text-align: center; margin-bottom: 25px;">
    <p style="font-size: 16px;">Kami harap Anda puas dengan layanan ini! Yuk, bantu penyedia layanan terus berkembang dengan memberikan rating dan ulasan. ✨</p>
    <x-mail::button :url="route('user.rating.create', $booking->id)" color="primary">
    Berikan Rating & Ulasan
    </x-mail::button>
</div>

<div style="text-align: center; margin-top: 30px; color: #6B7280; font-size: 14px;">
    <p>Terima kasih sudah mempercayai Homize! Sampai jumpa di pesanan berikutnya. 🚀</p>
    <p style="margin-bottom: 0;">Homize</p>
</div>
</x-mail::message>
