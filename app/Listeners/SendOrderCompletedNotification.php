<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Mail\OrderCompletedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class SendOrderCompletedNotification
{
    /**
     * Handle the event.
     * 
     * Mengirim email notifikasi ke user bahwa pesanan telah selesai
     * dan menyertakan link untuk memberikan rating & ulasan.
     */
    public function handle(OrderCompleted $event): void
    {
        try {
            $booking = $event->booking;
            
            // Log awal proses dengan environment info
            Log::info('Memulai proses pengiriman email notifikasi pesanan selesai', [
                'booking_id' => $booking->id,
                'environment' => app()->environment(),
                'mail_driver' => Config::get('mail.default'),
                'mail_host' => Config::get('mail.mailers.smtp.host')
            ]);
            
            // Pastikan semua relasi dimuat
            if (!$booking->relationLoaded('user') || !$booking->relationLoaded('merchant') || 
                !$booking->relationLoaded('layanan') || !$booking->relationLoaded('pembayaran')) {
                Log::info('Loading relasi yang dibutuhkan untuk email', [
                    'booking_id' => $booking->id
                ]);
                $booking->load(['user', 'merchant', 'layanan', 'pembayaran']);
            }
            
            // Pastikan booking memiliki relasi user
            if ($booking->user && $booking->user->email) {
                Log::info('Mencoba mengirim email ke user', [
                    'booking_id' => $booking->id,
                    'user_email' => $booking->user->email
                ]);
                
                // Gunakan try-catch terpisah untuk isolasi error pengiriman email
                try {
                    // Tambahkan timeout yang lebih lama untuk production
                    if (app()->environment('production')) {
                        Config::set('mail.mailers.smtp.timeout', 60);
                    }
                    
                    Mail::to($booking->user->email)
                        ->send(new OrderCompletedNotification($booking));
                    
                    Log::info('Email notifikasi pesanan selesai berhasil dikirim', [
                        'booking_id' => $booking->id,
                        'user_email' => $booking->user->email
                    ]);
                } catch (\Exception $mailException) {
                    // Log detail error email
                    Log::error('Error saat mengirim email', [
                        'booking_id' => $booking->id,
                        'error' => $mailException->getMessage(),
                        'error_code' => $mailException->getCode(),
                        'mail_config' => [
                            'driver' => Config::get('mail.default'),
                            'host' => Config::get('mail.mailers.smtp.host'),
                            'port' => Config::get('mail.mailers.smtp.port'),
                            'from_address' => Config::get('mail.from.address'),
                            'encryption' => Config::get('mail.mailers.smtp.encryption')
                        ]
                    ]);
                    
                    // Coba fallback ke driver log jika SMTP gagal di production
                    if (app()->environment('production') && Config::get('mail.default') !== 'log') {
                        Log::info('Mencoba fallback ke mail driver log');
                        
                        // Simpan konfigurasi asli
                        $originalMailer = Config::get('mail.default');
                        
                        // Set mailer ke log untuk fallback
                        Config::set('mail.default', 'log');
                        
                        try {
                            Mail::to($booking->user->email)
                                ->send(new OrderCompletedNotification($booking));
                                
                            Log::info('Email berhasil dikirim menggunakan fallback driver log');
                        } catch (\Exception $fallbackException) {
                            Log::error('Fallback email juga gagal', [
                                'error' => $fallbackException->getMessage()
                            ]);
                        }
                        
                        // Kembalikan konfigurasi asli
                        Config::set('mail.default', $originalMailer);
                    }
                }
            } else {
                Log::warning('Tidak dapat mengirim email notifikasi: data user tidak lengkap', [
                    'booking_id' => $booking->id,
                    'user' => $booking->user ? 'ada' : 'tidak ada',
                    'email' => $booking->user->email ?? 'tidak ada'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email notifikasi pesanan selesai', [
                'booking_id' => $event->booking->id ?? 'tidak ada',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
} 