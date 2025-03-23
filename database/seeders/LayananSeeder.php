<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Merchant;
use App\Models\JamOperasional;
use App\Models\SubKategori;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all merchants
        $merchants = Merchant::all();
        // Get default jam operasional
        $jamOperasional = JamOperasional::first();

        $layanan = [];

        // Create services for each merchant
        foreach ($merchants as $merchant) {
            // Create 3-5 services per merchant
            $numServices = rand(3, 5);

            // Get the SubKategori directly using the id
            $subKategori = SubKategori::find($merchant->id_sub_kategori);

            for ($i = 0; $i < $numServices; $i++) {
                $prefixes = ['Jasa', 'Layanan', 'Paket'];
                $suffixes = ['Basic', 'Premium', 'Gold', 'Express', 'Regular'];

                $layanan[] = [
                    'id_merchant' => $merchant->id,
                    'id_jam_operasional' => $jamOperasional->id,
                    'id_sub_kategori' => $merchant->id_sub_kategori,
                    'nama_layanan' => $faker->randomElement($prefixes) . ' ' .
                        $subKategori->nama . ' ' .
                        $faker->randomElement($suffixes),
                    'deskripsi_layanan' => $faker->paragraph(2),
                    'pengalaman' => $faker->numberBetween(1, 5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Layanan::insert($layanan);
    }
}
