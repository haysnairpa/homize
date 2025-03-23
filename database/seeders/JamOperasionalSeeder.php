<?php

namespace Database\Seeders;

use App\Models\JamOperasional;
use App\Models\Hari;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JamOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create operating hours for each day
        $hariIds = Hari::pluck('id')->toArray();

        foreach ($hariIds as $hariId) {
            JamOperasional::create([
                'id_hari' => $hariId,
                'jam_buka' => '08:00:00',
                'jam_tutup' => '17:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
