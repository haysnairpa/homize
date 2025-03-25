<?php

namespace Database\Seeders;

use App\Models\BookingSchedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 500 booking schedules (matching the number of bookings)
        for ($i = 0; $i < 500; $i++) {
            // Generate random start time between 8 AM and 6 PM
            $startTime = Carbon::createFromTime(rand(8, 18), rand(0, 59));
            // End time will be 1-3 hours after start time
            $endTime = $startTime->copy()->addHours(rand(1, 3));

            BookingSchedule::create([
                'waktu_mulai' => $startTime->format('H:i:s'),
                'waktu_selesai' => $endTime->format('H:i:s')
            ]);
        }
    }
}
