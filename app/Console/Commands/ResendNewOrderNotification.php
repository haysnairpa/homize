<?php

namespace App\Console\Commands;

use App\Mail\NewOrderNotification;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ResendNewOrderNotification extends Command
{
    protected $signature = 'email:resend-new-order {booking_id?}';
    protected $description = 'Resend new order notification email';

    public function handle()
    {
        $bookingId = $this->argument('booking_id');
        
        if ($bookingId) {
            // Kirim untuk booking tertentu
            $booking = Booking::with(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule'])->find($bookingId);
            if (!$booking) {
                $this->error("Booking dengan ID {$bookingId} tidak ditemukan");
                return 1;
            }
            $this->sendEmail($booking);
        } else {
            // Kirim untuk semua booking dengan status_proses 'pending'
            $bookings = Booking::with(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule'])
                ->where('status_proses', 'pending')
                ->get();
            $this->info("Ditemukan " . $bookings->count() . " booking dengan status proses 'pending'");
            foreach ($bookings as $booking) {
                $this->sendEmail($booking);
            }
        }
        return 0;
    }

    
    
    private function sendEmail(Booking $booking)
    {
        try {
            if ($booking->merchant && $booking->merchant->user) {
                $this->info("Mengirim email notifikasi pesanan baru ke: " . $booking->merchant->user->email . " untuk booking #" . $booking->id);
                Mail::to($booking->merchant->user->email)
                    ->send(new NewOrderNotification($booking));
                $this->info("Email berhasil dikirim!");
            } else {
                $this->error("Data merchant atau user tidak lengkap untuk booking #" . $booking->id);
            }
        } catch (\Exception $e) {
            $this->error("Gagal mengirim email untuk booking #" . $booking->id . ": " . $e->getMessage());
        }
    }
}