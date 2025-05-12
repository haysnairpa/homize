<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Models\Merchant;
use App\Models\Layanan;
use App\Models\Status;
use App\Models\BookingSchedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $merchants = Merchant::all();
        $layanans = Layanan::all();
        $schedules = BookingSchedule::all();
        $statuses = ['Pending', 'Dikonfirmasi', 'Sedang diproses', 'Selesai', 'Dibatalkan'];

        if ($users->isEmpty() || $merchants->isEmpty() || $layanans->isEmpty() || $schedules->isEmpty()) {
            throw new \Exception('Required data not found. Please run previous seeders first.');
        }

        // Calculate how many bookings each layanan should have
        $bookingsPerLayanan = ceil(500 / $layanans->count());
        
        // Create bookings with equal distribution of layanans
        $bookingCount = 0;
        foreach ($layanans as $layanan) {
            // Create multiple bookings for this layanan
            for ($i = 0; $i < $bookingsPerLayanan && $bookingCount < 500; $i++) {
                $user = $users->random();
                $merchant = $merchants->random();
                $schedule = $schedules->random();
                $status = $statuses[array_rand($statuses)];

                // Generate random coordinates within Indonesia
                $latitude = rand(-6, 6) . '.' . rand(100000, 999999);
                $longitude = rand(95, 141) . '.' . rand(100000, 999999);

                Booking::create([
                    'id_user' => $user->id,
                    'id_merchant' => $merchant->id,
                    'id_layanan' => $layanan->id,
                    'status_proses' => $status,
                    'id_booking_schedule' => $schedule->id,
                    'tanggal_booking' => $schedule->waktu_mulai,
                    'catatan' => 'Catatan untuk booking #' . ($bookingCount + 1),
                    'alamat_pembeli' => 'Jalan Contoh No. ' . rand(1, 100) . ', Kota Contoh',
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]);

                $bookingCount++;
            }
        }
    }
}
