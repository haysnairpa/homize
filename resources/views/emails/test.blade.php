<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Configuration Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Email Configuration Test</h2>
        </div>
        <div class="content">
            <p>Halo!</p>
            <p>Ini adalah email pengujian dari aplikasi Homize.</p>
            <p>Jika Anda menerima email ini, berarti konfigurasi email di aplikasi berfungsi dengan baik.</p>
            <p>Email ini dikirim pada: {{ date('Y-m-d H:i:s') }}</p>
            <p>Environment: {{ app()->environment() }}</p>
            <p>Mail Driver: {{ config('mail.default') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Homize. All rights reserved.</p>
            <p>Email ini dikirim untuk tujuan pengujian.</p>
        </div>
    </div>
</body>
</html>
