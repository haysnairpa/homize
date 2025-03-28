<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Mail\OrderCompletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderCompletedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     * 
     * Mengirim email notifikasi ke user bahwa pesanan telah selesai
     * dan menyertakan link untuk memberikan rating & ulasan.
     */
    public function handle(OrderCompleted $event): void
    {
        $booking = $event->booking;
        
        // Pastikan booking memiliki relasi user
        if ($booking->user) {
            try {
                Mail::to($booking->user->email)
                    ->send(new OrderCompletedNotification($booking));
                
                Log::info('Email notifikasi pesanan selesai berhasil dikirim', [
                    'booking_id' => $booking->id,
                    'user_email' => $booking->user->email
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email notifikasi pesanan selesai', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            Log::warning('Tidak dapat mengirim email notifikasi: data user tidak lengkap', [
                'booking_id' => $booking->id
            ]);
        }
    }
} 