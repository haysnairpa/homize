<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Production Email Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains production-specific email settings that will be loaded
    | only in the production environment. These settings override the default
    | settings in config/mail.php when in production.
    |
    */
    
    // Set a longer timeout for production SMTP connections
    'timeout' => 60,
    
    // Ensure we're using the correct local domain in production
    'local_domain' => 'homize.id',
    
    // Add retry mechanism for failed emails
    'retry_after' => 60, // seconds
    'max_retries' => 3,
    
    // Configure a backup mailer for failover
    'backup_mailer' => 'log',
    
    // SSL/TLS verification settings (sometimes needed in production)
    'verify_peer' => true,
    'verify_peer_name' => true,
];
