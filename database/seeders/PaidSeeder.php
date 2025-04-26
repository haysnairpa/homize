<?php

namespace Database\Seeders;

use App\Models\Paid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paid = [
            [
                "status_pembayaran" => "Belum Dibayar",
            ],
            [
                "status_pembayaran" => "Sudah Dibayar",
            ]
        ];

        Paid::insert($paid);
    }
}
