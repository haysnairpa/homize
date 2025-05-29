<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ProductionMailServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Jika di production, gunakan smtp dengan port 465 dan SSL untuk menghindari pemblokiran port
        if (app()->environment('production')) {
            Config::set('mail.mailers.smtp.port', 465);
            Config::set('mail.mailers.smtp.encryption', 'ssl');
            Config::set('mail.mailers.smtp.timeout', 60);
            
            // Log konfigurasi untuk debugging
            Log::info('Mail configuration set for production', [
                'host' => Config::get('mail.mailers.smtp.host'),
                'port' => Config::get('mail.mailers.smtp.port'),
                'encryption' => Config::get('mail.mailers.smtp.encryption')
            ]);
        }
    }
}
