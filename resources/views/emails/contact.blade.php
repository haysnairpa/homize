<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak Baru</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #30A0E0;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #30A0E0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pesan Kontak Baru</h1>
    </div>
    
    <div class="content">
        <p>Anda telah menerima pesan baru dari formulir kontak website Homize:</p>
        
        <div class="field">
            <p class="label">Nama:</p>
            <p>{{ $name }}</p>
        </div>
        
        <div class="field">
            <p class="label">Email:</p>
            <p>{{ $email }}</p>
        </div>
        
        <div class="field">
            <p class="label">Subjek:</p>
            <p>{{ $subject }}</p>
        </div>
        
        <div class="field">
            <p class="label">Pesan:</p>
            <p>{{ $messageContent }}</p>
        </div>
    </div>
    
    <div class="footer">
        <p>Email ini dikirim secara otomatis dari website Homize. Mohon jangan membalas email ini.</p>
        <p>&copy; {{ date('Y') }} Homize. All rights reserved.</p>
    </div>
</body>
</html>
