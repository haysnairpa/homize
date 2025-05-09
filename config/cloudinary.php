<?php

return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', ''),
    'api_key' => env('CLOUDINARY_API_KEY', ''),
    'api_secret' => env('CLOUDINARY_API_SECRET', ''),
    'url' => env('CLOUDINARY_URL', ''),
    'upload_presets' => [
        'default' => env('CLOUDINARY_UPLOAD_PRESET', ''),
        'layanan' => env('CLOUDINARY_LAYANAN_UPLOAD_PRESET', ''),
        'sertifikasi' => env('CLOUDINARY_SERTIFIKASI_UPLOAD_PRESET', ''),
        'user_profile' => env('CLOUDINARY_USER_PROFILE_UPLOAD_PRESET', ''),
    ],
];
