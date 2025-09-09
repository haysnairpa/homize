<?php

namespace App\Listeners;

use App\Events\PaymentStatusChanged;
use App\Mail\PaymentConfirmationMail;
use App\Mail\PaymentRejectionMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPaymentStatusNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\PaymentStatusChanged  $event
     * @return void
     */
    public function handle(PaymentStatusChanged $event): void
    {
        $pembayaran = $event->pembayaran;
        $booking = $pembayaran->booking;
        
        // Pastikan booking memiliki relasi user
        if (!$booking || !$booking->user || !$booking->user->email) {
            Log::warning('Tidak dapat mengirim email notifikasi: data user tidak lengkap', [
                'pembayaran_id' => $pembayaran->id,
                'booking_id' => $booking->id ?? 'tidak ada'
            ]);
            return;
        }

        try {
            if ($event->status === 'confirmed') {
                // Kirim email konfirmasi pembayaran ke customer
                Mail::to($booking->user->email)
                    ->send(new PaymentConfirmationMail($pembayaran));
                
                Log::info('Email notifikasi pembayaran berhasil dikirim ke customer', [
                    'pembayaran_id' => $pembayaran->id,
                    'booking_id' => $booking->id,
                    'customer_email' => $booking->user->email
                ]);
            } elseif ($event->status === 'rejected') {
                // Kirim email penolakan pembayaran ke customer
                Mail::to($booking->user->email)
                    ->send(new PaymentRejectionMail($pembayaran, $event->reason));
                
                Log::info('Email notifikasi pembayaran ditolak dikirim ke customer', [
                    'pembayaran_id' => $pembayaran->id,
                    'booking_id' => $booking->id,
                    'customer_email' => $booking->user->email,
                    'reason' => $event->reason
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email notifikasi status pembayaran', [
                'pembayaran_id' => $pembayaran->id,
                'booking_id' => $booking->id,
                'status' => $event->status,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
