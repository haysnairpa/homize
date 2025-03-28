<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CheckBookingRelations extends Command
{
    protected $signature = 'booking:check-relations {booking_id}';
    protected $description = 'Check booking relations';

    public function handle()
    {
        $bookingId = $this->argument('booking_id');
        
        $booking = Booking::with(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule'])
            ->find($bookingId);
            
        if (!$booking) {
            $this->error("Booking dengan ID {$bookingId} tidak ditemukan");
            return 1;
        }
        
        $this->info("Booking #" . $booking->id);
        $this->info("User: " . ($booking->user ? $booking->user->email : 'tidak ada'));
        $this->info("Merchant: " . ($booking->merchant ? $booking->merchant->nama_merchant : 'tidak ada'));
        $this->info("Merchant User: " . ($booking->merchant && $booking->merchant->user ? $booking->merchant->user->email : 'tidak ada'));
        
        return 0;
    }
}