<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Mail\OrderCompletedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            
            // Log awal proses
            Log::info('Memulai proses pengiriman email notifikasi pesanan selesai', [
                'booking_id' => $booking->id
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
                
                Mail::to($booking->user->email)
                    ->send(new OrderCompletedNotification($booking));
                
                Log::info('Email notifikasi pesanan selesai berhasil dikirim', [
                    'booking_id' => $booking->id,
                    'user_email' => $booking->user->email
                ]);
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