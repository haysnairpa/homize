<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Mendapat Protes</title>
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
            background-color: #f44336;
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
        .protest-reason {
            background-color: #fff3f3;
            padding: 15px;
            border: 1px solid #ffcccc;
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
            background-color: #f44336;
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
            <h2>Pesanan Mendapat Protes dari Pelanggan</h2>
        </div>
        
        <div class="content">
            @if($isForAdmin)
            <p>Halo Admin,</p>
            <p>Pesanan berikut telah <strong>mendapat protes</strong> dari pelanggan. Mohon segera ditindaklanjuti.</p>
            @else
            <p>Halo {{ $booking->merchant->nama_usaha }},</p>
            <p>Kami ingin memberitahukan bahwa pesanan berikut telah <strong>mendapat protes</strong> dari pelanggan. Mohon segera ditindaklanjuti.</p>
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
                        <td>Tanggal Protes</td>
                        <td>{{ $booking->protest_date->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Total Pembayaran</td>
                        <td>Rp {{ number_format($booking->pembayaran->amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="protest-reason">
                <h3>Alasan Protes</h3>
                <p>{{ $booking->protest_reason }}</p>
            </div>
            
            @if($isForAdmin)
            <p>Mohon segera menghubungi pelanggan dan merchant untuk menyelesaikan masalah ini.</p>
            <a href="{{ route('admin.transactions') }}" class="button">Lihat Transaksi</a>
            @else
            <p>Mohon segera menghubungi pelanggan untuk menyelesaikan masalah ini. Admin juga akan membantu menyelesaikan masalah ini.</p>
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
