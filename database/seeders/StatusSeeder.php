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
        $status = [
            [
                "nama_status" => "Diproses"
            ],
            [
                "nama_status" => "Berlangsung"
            ],
            [
                "nama_status" => "Selesai"
            ],
            [
                "nama_status" => "Dibatalkan"
            ]
        ];

        Status::insert($status);
    }
}
