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
            
            // Pastikan semua relasi dimuat
            if (!$booking->relationLoaded('user') || !$booking->relationLoaded('merchant') || 
                !$booking->relationLoaded('layanan') || !$booking->relationLoaded('pembayaran')) {
                $booking->load(['user', 'merchant', 'layanan', 'pembayaran']);
            }
            
            // Pastikan booking memiliki relasi user
            if ($booking->user && $booking->user->email) {
                // Catat alamat email user untuk referensi
                $userEmail = $booking->user->email;
                
                // STRATEGI 1: Port 465 dengan SSL (biasanya bekerja di shared hosting)
                Log::info('Mencoba kirim email dengan port 465/SSL');
                try {
                    config([
                        'mail.mailers.smtp.port' => 465,
                        'mail.mailers.smtp.encryption' => 'ssl',
                        'mail.mailers.smtp.timeout' => 60
                    ]);
                    
                    Mail::to($userEmail)->send(new OrderCompletedNotification($booking));
                    Log::info('Email berhasil dikirim dengan port 465/SSL', ['to' => $userEmail]);
                    return; // Sukses, berhenti di sini
                } catch (\Exception $e) {
                    Log::warning('Gagal kirim email dengan port 465/SSL: ' . $e->getMessage());
                    
                    // STRATEGI 2: Port 587 dengan TLS (bekerja di beberapa server)
                    Log::info('Mencoba kirim email dengan port 587/TLS');
                    try {
                        config([
                            'mail.mailers.smtp.port' => 587,
                            'mail.mailers.smtp.encryption' => 'tls',
                            'mail.mailers.smtp.timeout' => 60
                        ]);
                        
                        Mail::to($userEmail)->send(new OrderCompletedNotification($booking));
                        Log::info('Email berhasil dikirim dengan port 587/TLS', ['to' => $userEmail]);
                        return; // Sukses, berhenti di sini
                    } catch (\Exception $e2) {
                        Log::warning('Gagal kirim email dengan port 587/TLS: ' . $e2->getMessage());
                        
                        // STRATEGI 3: Fallback ke API Mailtrap
                        Log::info('Mencoba kirim email dengan Mailtrap');
                        try {
                            config([
                                'mail.mailers.smtp.host' => 'sandbox.smtp.mailtrap.io',
                                'mail.mailers.smtp.port' => 2525,
                                'mail.mailers.smtp.encryption' => 'tls',
                                'mail.mailers.smtp.username' => '1df8a4e2b3dd0c',
                                'mail.mailers.smtp.password' => '7d79f27ab9b8ae'
                            ]);
                            
                            Mail::to($userEmail)->send(new OrderCompletedNotification($booking));
                            Log::info('Email berhasil dikirim via Mailtrap', ['to' => $userEmail]);
                            return; // Sukses, berhenti di sini
                        } catch (\Exception $e3) {
                            // Semua metode gagal
                            Log::error('SEMUA METODE EMAIL GAGAL', [
                                'error_1' => $e->getMessage(),
                                'error_2' => $e2->getMessage(),
                                'error_3' => $e3->getMessage()
                            ]);
                        }
                    }
                }
            } else {
                Log::warning('Tidak dapat mengirim email: data user tidak lengkap', [
                    'booking_id' => $booking->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal proses notifikasi email: ' . $e->getMessage());
        }
    }
} 