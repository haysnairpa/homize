<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Disetujui</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        table td:first-child {
            font-weight: bold;
            width: 40%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Pesanan Disetujui oleh Pelanggan</h2>
        </div>
        
        <div class="content">
            @if($isForAdmin)
            <p>Halo Admin,</p>
            <p>Pesanan berikut telah <strong>disetujui</strong> oleh pelanggan. Anda dapat menambahkan saldo ke akun merchant.</p>
            @else
            <p>Halo {{ $booking->merchant->nama_usaha }},</p>
            <p>Selamat! Pesanan berikut telah <strong>disetujui</strong> oleh pelanggan. Saldo Anda akan segera ditambahkan oleh admin.</p>
            @endif
            
            <div class="order-details">
                <h3>Detail Pesanan</h3>
                <table>
                    <tr>
                        <td>ID Pesanan</td>
                        <td>#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <td>Layanan</td>
                        <td>{{ $booking->layanan->nama_layanan }}</td>
                    </tr>
                    <tr>
                        <td>Pelanggan</td>
                        <td>{{ $booking->user->name }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pesanan</td>
                        <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Selesai</td>
                        <td>{{ $booking->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Disetujui</td>
                        <td>{{ $booking->customer_approval_date->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Total Pembayaran</td>
                        <td>Rp {{ number_format($booking->pembayaran->amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            
            @if($isForAdmin)
            <p>Silakan login ke panel admin untuk menambahkan saldo ke akun merchant.</p>
            <a href="{{ route('admin.merchants.detail', $booking->merchant->id) }}" class="button">Lihat Detail Merchant</a>
            @else
            <p>Saldo akan segera ditambahkan ke akun Anda oleh admin. Terima kasih atas layanan yang Anda berikan.</p>
            <a href="{{ route('merchant.orders') }}" class="button">Lihat Pesanan Anda</a>
            @endif
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Homize. Semua hak dilindungi.</p>
            <p>Ini adalah email otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
