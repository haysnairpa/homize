<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailConfigTester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:check {email? : Email address to send test to} {--debug : Show detailed debug information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration and send a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing email configuration...');
        
        // Display current email configuration
        $this->displayEmailConfig();
        
        // Test SMTP connection
        $this->testSmtpConnection();
        
        // Send test email if requested
        if ($email = $this->argument('email')) {
            $this->sendTestEmail($email);
        } else {
            $this->warn('No email address provided. Skipping test email sending.');
            $this->info('To send a test email, run: php artisan email:check your@email.com');
        }
    }
    
    /**
     * Display current email configuration
     */
    protected function displayEmailConfig()
    {
        $this->info('Current Email Configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Environment', app()->environment()],
                ['Mail Driver', Config::get('mail.default')],
                ['SMTP Host', Config::get('mail.mailers.smtp.host')],
                ['SMTP Port', Config::get('mail.mailers.smtp.port')],
                ['SMTP Encryption', Config::get('mail.mailers.smtp.encryption') ?: 'None'],
                ['From Address', Config::get('mail.from.address')],
                ['From Name', Config::get('mail.from.name')],
                ['Has Username', !empty(Config::get('mail.mailers.smtp.username')) ? 'Yes' : 'No'],
                ['Has Password', !empty(Config::get('mail.mailers.smtp.password')) ? 'Yes' : 'No'],
                ['Timeout', Config::get('mail.mailers.smtp.timeout', 30)],
            ]
        );
    }
    
    /**
     * Test SMTP connection
     */
    protected function testSmtpConnection()
    {
        if (Config::get('mail.default') !== 'smtp') {
            $this->warn('Mail driver is not set to SMTP. Skipping SMTP connection test.');
            return;
        }
        
        $this->info('Testing SMTP connection...');
        
        try {
            // Create a new SMTP transport
            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                Config::get('mail.mailers.smtp.host'),
                Config::get('mail.mailers.smtp.port'),
                Config::get('mail.mailers.smtp.encryption') === 'tls'
            );
            
            // Set credentials if available
            if (Config::get('mail.mailers.smtp.username')) {
                $transport->setUsername(Config::get('mail.mailers.smtp.username'));
                $transport->setPassword(Config::get('mail.mailers.smtp.password'));
            }
            
            // Test connection
            $transport->start();
            $this->info('✓ SMTP connection successful!');
            $transport->stop();
        } catch (\Exception $e) {
            $this->error('✗ SMTP connection failed: ' . $e->getMessage());
            
            if ($this->option('debug')) {
                $this->line('');
                $this->line('Debug information:');
                $this->line($e->getTraceAsString());
            }
        }
    }
    
    /**
     * Send a test email
     */
    protected function sendTestEmail(string $email)
    {
        $this->info("Sending test email to {$email}...");
        
        try {
            // Send email using a simple test mailable
            Mail::to($email)->send(new SimpleTestEmail());
            
            $this->info('✓ Test email sent successfully!');
            $this->line("Please check {$email} inbox (and spam folder)");
            
            // Log success
            Log::info('Test email sent successfully', [
                'to' => $email,
                'driver' => Config::get('mail.default')
            ]);
        } catch (\Exception $e) {
            $this->error('✗ Failed to send test email: ' . $e->getMessage());
            
            // Log error
            Log::error('Test email failed', [
                'to' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($this->option('debug')) {
                $this->line('');
                $this->line('Debug information:');
                $this->line($e->getTraceAsString());
            }
            
            // Try fallback to log driver
            $this->line('');
            $this->info('Attempting to send using log driver as fallback...');
            
            try {
                // Save original driver
                $originalDriver = Config::get('mail.default');
                
                // Set to log driver
                Config::set('mail.default', 'log');
                
                // Send test email with log driver
                Mail::to($email)->send(new SimpleTestEmail());
                
                $this->info('✓ Test email logged successfully using log driver');
                $this->line('Check your Laravel log file for the email content');
                
                // Reset to original driver
                Config::set('mail.default', $originalDriver);
            } catch (\Exception $fallbackException) {
                $this->error('✗ Fallback to log driver also failed: ' . $fallbackException->getMessage());
            }
        }
    }
}

/**
 * Simple test email class for testing email configuration
 */
class SimpleTestEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Configuration Test',
        );
    }
    
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.test',
        );
    }
}
