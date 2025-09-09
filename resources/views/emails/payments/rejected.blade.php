@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ asset('images/homizelogoblue.png') }}" alt="Homize Logo" style="max-height: 50px; margin-bottom: 20px;">
    <h1 style="color: #ef4444; margin: 0; font-size: 24px; font-weight: 700;">Pembayaran Ditolak</h1>
</div>

<div style="background-color: #fef2f2; border-radius: 8px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #ef4444;">
    <p style="margin-top: 0; font-size: 16px;">Halo <strong>{{ $pembayaran->booking->user->nama }}</strong>,</p>
    <p style="margin-top: 0; font-size: 16px;">Mohon maaf, pembayaran Anda untuk pesanan berikut tidak dapat dikonfirmasi:</p>
</div>

<table style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 25px; border-radius: 8px; overflow: hidden; border: 1px solid #E5E7EB;">
    <tr style="background-color: #ef4444; color: white;">
        <th colspan="2" style="padding: 12px 15px; text-align: left; font-size: 16px;">Detail Pesanan #{{ $pembayaran->booking->id }}</th>
    </tr>
    <tr style="background-color: white;">
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB; width: 40%; font-weight: 600;">Layanan</td>
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB;">{{ $pembayaran->booking->layanan->nama_layanan }}</td>
    </tr>
    <tr style="background-color: #f0f9ff;">
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB; font-weight: 600;">Penyedia Layanan</td>
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB;">{{ $pembayaran->booking->merchant->nama_usaha }}</td>
    </tr>
    <tr style="background-color: white;">
        <td style="padding: 12px 15px; border-bottom: 1px solid #E5E7EB; font-weight: 600;">Total Pembayaran</td>
        <td style="padding: 12px 15px; font-weight: 700; color: #ef4444;">Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}</td>
    </tr>
    <tr style="background-color: #fef2f2;">
        <td style="padding: 12px 15px; font-weight: 600;">Alasan Penolakan</td>
        <td style="padding: 12px 15px;">{{ $reason }}</td>
    </tr>
</table>

<div style="text-align: center; margin-bottom: 25px;">
    <p style="font-size: 16px;">Anda dapat mencoba melakukan pembayaran ulang atau menghubungi tim dukungan kami untuk bantuan lebih lanjut.</p>
    @component('mail::button', ['url' => route('pembayaran.show', $pembayaran->booking->id), 'color' => 'primary'])
    Coba Pembayaran Ulang
    @endcomponent
</div>

<div style="background-color: #fff8e6; border-radius: 8px; padding: 15px; margin-bottom: 25px; text-align: center; border-left: 4px solid #ffb655;">
    <p style="margin: 0; font-size: 14px; color: #996b33;">Jika Anda memiliki pertanyaan, silakan hubungi tim dukungan kami melalui WhatsApp atau email.</p>
</div>

<div style="text-align: center; margin-top: 30px; color: #6B7280; font-size: 14px;">
    <p>Terima kasih atas pengertian Anda.</p>
    <p style="margin-bottom: 0;">Homize</p>
</div>
@endcomponent
