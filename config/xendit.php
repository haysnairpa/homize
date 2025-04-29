<?php

// config/xendit.php

return [
    'api_key' => env('XENDIT_API_KEY', ''),
    'callback_token' => env('XENDIT_CALLBACK_TOKEN', ''),
    'is_production' => env('XENDIT_IS_PRODUCTION', false),
    'max_otp_attempts' => env('XENDIT_MAX_OTP_ATTEMPTS', 3),
    'qris_enabled' => env('XENDIT_QRIS_ENABLED', true),
];
