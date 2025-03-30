<?php
// config/midtrans.php

return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    'max_otp_attempts' => env('MIDTRANS_MAX_OTP_ATTEMPTS', 3),
    'qris_enabled' => env('MIDTRANS_QRIS_ENABLED', true),
    'qris_acquirer' => env('MIDTRANS_QRIS_ACQUIRER', 'gopay'),
];