<?php

namespace Database\Seeders;

use App\Models\TarifLayanan;
use App\Models\Layanan;
use App\Models\Revisi;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TarifLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // First, create a default revision
        $revisi = Revisi::create([
            'harga' => 0,
            'durasi' => 0,
            'tipe_durasi' => 'Jam',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $layananIds = Layanan::pluck('id')->toArray();
        $satuan = ['Unit', 'Kg', 'Pcs'];
        $tipeDurasi = ['Jam', 'Hari', 'Pertemuan'];

        $tarifLayanan = [];

        foreach ($layananIds as $layananId) {
            // Generate appropriate durasi based on tipe_durasi
            $tipeDurasiSelected = $faker->randomElement($tipeDurasi);
            $durasi = match ($tipeDurasiSelected) {
                'Jam' => $faker->numberBetween(1, 8),
                'Hari' => $faker->numberBetween(1, 30),
                'Pertemuan' => $faker->numberBetween(1, 12),
            };

            $tarifLayanan[] = [
                'id_revisi' => $revisi->id,
                'id_layanan' => $layananId,
                'harga' => $faker->numberBetween(50000, 1000000),
                'satuan' => $faker->randomElement($satuan),
                'durasi' => $durasi,
                'tipe_durasi' => $tipeDurasiSelected,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        TarifLayanan::insert($tarifLayanan);
    }
}
