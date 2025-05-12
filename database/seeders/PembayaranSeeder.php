<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use App\Models\Booking;
use App\Models\Status;
use App\Models\TarifLayanan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = Booking::all();
        $statuses = ['Pending', 'Selesai', 'Dibatalkan'];
        $paymentMethods = ['Bank Transfer', 'Credit Card', 'E-Wallet', 'Cash'];

        if ($bookings->isEmpty() || $statuses->isEmpty()) {
            throw new \Exception('Required data not found. Please run previous seeders first.');
        }

        foreach ($bookings as $booking) {
            // Get the service tariff for this booking's layanan
            $tarifLayanan = TarifLayanan::where('id_layanan', $booking->id_layanan)->first();

            if (!$tarifLayanan) {
                throw new \Exception("No tariff found for layanan ID: {$booking->id_layanan}");
            }

            // Determine payment status based on booking status
            $paymentStatus = match ($booking->status_proses) {
    'Selesai' => 'Selesai',
    'Dibatalkan' => 'Dibatalkan',
    default => 'Pending',
};

            // Generate payment date based on booking date
            $paymentDate = Carbon::parse($booking->tanggal_booking);
            if ($paymentStatus->nama_status === 'Payment Completed') {
                // If payment is completed, set payment date to booking date
                $paymentDate = Carbon::parse($booking->tanggal_booking);
            } elseif ($paymentStatus->nama_status === 'Payment Failed') {
                // If payment failed, set payment date to after booking date
                $paymentDate = Carbon::parse($booking->tanggal_booking)->addDays(rand(1, 3));
            } else {
                // If pending, set payment date to booking date (since it's required)
                $paymentDate = Carbon::parse($booking->tanggal_booking);
            }

            // Always set a payment method, even for pending or failed payments
            $method = $paymentMethods[array_rand($paymentMethods)];

            Pembayaran::create([
                'id_booking' => $booking->id,
                'amount' => $tarifLayanan->harga,
                'method' => $method,
                'status_pembayaran' => $paymentStatus,
                'payment_date' => $paymentDate
            ]);
        }
    }
}
