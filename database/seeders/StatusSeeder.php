<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['nama_status' => 'Pending'],
            ['nama_status' => 'Confirmed'],
            ['nama_status' => 'In Progress'],
            ['nama_status' => 'Completed'],
            ['nama_status' => 'Cancelled'],
            ['nama_status' => 'Payment Pending'],
            ['nama_status' => 'Payment Completed'],
            ['nama_status' => 'Payment Failed']
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
