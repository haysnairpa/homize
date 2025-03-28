<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\CheckExpiredPayments;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Jalankan job setiap jam untuk cek pembayaran yang expired
        $schedule->job(new CheckExpiredPayments)->hourly();
    }
}