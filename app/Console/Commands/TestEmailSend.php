<?php

namespace App\Console\Commands;

use App\Mail\NewOrderNotification;
use App\Mail\OrderCompletedNotification;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailSend extends Command
{
    protected $signature = 'email:test {booking_id} {type=new}';
    protected $description = 'Test email sending';

    public function handle()
    {
        $bookingId = $this->argument('booking_id');
        $type = $this->argument('type');
        
        $booking = Booking::with(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule'])
            ->find($bookingId);
            
        if (!$booking) {
            $this->error("Booking dengan ID {$bookingId} tidak ditemukan");
            return 1;
        }
        
        try {
            if ($type === 'new') {
                if (!$booking->merchant || !$booking->merchant->user) {
                    $this->error("Data merchant atau user tidak lengkap");
                    return 1;
                }
                
                $this->info("Mengirim email notifikasi pesanan baru ke: " . $booking->merchant->user->email);
                Mail::to($booking->merchant->user->email)
                    ->send(new NewOrderNotification($booking));
            } else {
                if (!$booking->user) {
                    $this->error("Data user tidak lengkap");
                    return 1;
                }
                
                $this->info("Mengirim email notifikasi pesanan selesai ke: " . $booking->user->email);
                Mail::to($booking->user->email)
                    ->send(new OrderCompletedNotification($booking));
            }
            
            $this->info("Email berhasil dikirim!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Gagal mengirim email: " . $e->getMessage());
            return 1;
        }
    }
} 