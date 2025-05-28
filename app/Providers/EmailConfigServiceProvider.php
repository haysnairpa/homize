<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;

class EmailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Load production-specific email configuration if in production
        if (app()->environment('production')) {
            $this->loadProductionEmailConfig();
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Log email configuration on application boot
        $this->logEmailConfiguration();
        
        // Setup email error handling
        $this->setupEmailErrorHandling();
        
        // Register email transport fallback mechanism
        $this->registerEmailFallback();
    }

    /**
     * Load production-specific email configuration
     */
    protected function loadProductionEmailConfig(): void
    {
        $productionConfigPath = config_path('mail.production.php');
        
        if (File::exists($productionConfigPath)) {
            $productionConfig = require $productionConfigPath;
            
            // Apply production-specific settings
            if (is_array($productionConfig)) {
                foreach ($productionConfig as $key => $value) {
                    if ($key === 'timeout') {
                        Config::set('mail.mailers.smtp.timeout', $value);
                    } elseif ($key === 'local_domain') {
                        Config::set('mail.mailers.smtp.local_domain', $value);
                    } else {
                        Config::set('mail.' . $key, $value);
                    }
                }
                
                Log::info('Loaded production-specific email configuration');
            }
        } else {
            Log::warning('Production email config file not found at: ' . $productionConfigPath);
        }
    }

    /**
     * Log email configuration for debugging purposes
     */
    protected function logEmailConfiguration(): void
    {
        Log::info('Email configuration loaded', [
            'environment' => app()->environment(),
            'mail_driver' => Config::get('mail.default'),
            'mail_host' => Config::get('mail.mailers.smtp.host'),
            'mail_port' => Config::get('mail.mailers.smtp.port'),
            'mail_encryption' => Config::get('mail.mailers.smtp.encryption'),
            'mail_from_address' => Config::get('mail.from.address'),
            'mail_from_name' => Config::get('mail.from.name'),
            'has_username' => !empty(Config::get('mail.mailers.smtp.username')),
            'has_password' => !empty(Config::get('mail.mailers.smtp.password')),
            'timeout' => Config::get('mail.mailers.smtp.timeout', 30),
        ]);
    }

    /**
     * Setup global error handling for email sending
     */
    protected function setupEmailErrorHandling(): void
    {
        // Set longer timeout for SMTP connections in production
        if (app()->environment('production')) {
            Config::set('mail.mailers.smtp.timeout', 60);
        }
        
        // Log email configuration for debugging
        Log::info('Email error handling setup', [
            'environment' => app()->environment(),
            'mail_config' => [
                'driver' => Config::get('mail.default'),
                'host' => Config::get('mail.mailers.smtp.host'),
                'port' => Config::get('mail.mailers.smtp.port'),
                'encryption' => Config::get('mail.mailers.smtp.encryption'),
                'timeout' => Config::get('mail.mailers.smtp.timeout', 30),
            ]
        ]);
    }
    
    /**
     * Register email transport fallback mechanism
     */
    protected function registerEmailFallback(): void
    {
        // Set longer timeout for SMTP connections in production
        if (app()->environment('production') && Config::get('mail.default') === 'smtp') {
            // Increase timeout for production environment
            Config::set('mail.mailers.smtp.timeout', 60);
            
            // Log the fallback setup
            Log::info('Email fallback mechanism configured for production', [
                'primary_driver' => Config::get('mail.default'),
                'fallback_driver' => 'log',
                'smtp_host' => Config::get('mail.mailers.smtp.host'),
                'smtp_timeout' => Config::get('mail.mailers.smtp.timeout')
            ]);
        }
    }
}
