<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\NewOrderNotification;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewOrderNotification
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $booking = $event->booking;
        
        // Pastikan booking memiliki relasi merchant dan user
        if ($booking->merchant && $booking->merchant->user) {
            try {
                Mail::to($booking->merchant->user->email)
                    ->send(new NewOrderNotification($booking));
                
                Log::info('Email notifikasi pesanan baru berhasil dikirim', [
                    'booking_id' => $booking->id,
                    'merchant_email' => $booking->merchant->user->email
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email notifikasi pesanan baru', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            Log::warning('Tidak dapat mengirim email notifikasi: data merchant atau user tidak lengkap', [
                'booking_id' => $booking->id
            ]);
        }
    }
} 