<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Layanan;
use App\Models\SubKategori;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $layananIds = Layanan::pluck('id')->toArray();
        
        $aset = [];
        
        // Create assets for about 70% of services
        $selectedLayanan = array_rand(array_flip($layananIds), ceil(count($layananIds) * 0.7));
        
        foreach ($selectedLayanan as $layananId) {
            $layanan = Layanan::find($layananId);
            $subKategori = SubKategori::find($layanan->id_sub_kategori);
            
            // Generate 1-3 assets per service
            $numAssets = rand(1, 3);
            
            for ($i = 0; $i < $numAssets; $i++) {
                $assetType = $this->getAssetTypeForService($subKategori->nama);
                
                $aset[] = [
                    'id_layanan' => $layananId,
                    'deskripsi' => $assetType . ' ' . ($i + 1),
                    'media_url' => $faker->imageUrl(640, 480, str_replace(' ', '', $assetType)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($aset, 100) as $chunk) {
            Aset::insert($chunk);
        }
    }

    private function getAssetTypeForService($subKategoriName)
    {
        // Define specific asset types based on service category
        return match (true) {
            str_contains($subKategoriName, 'Asisten Rumah Tangga') => 
                fake()->randomElement(['Peralatan Kebersihan', 'Vacuum Cleaner', 'Peralatan Dapur']),
            
            str_contains($subKategoriName, 'Teknisi') => 
                fake()->randomElement(['Toolkit', 'Multimeter', 'Peralatan Teknis']),
            
            str_contains($subKategoriName, 'Make-up') => 
                fake()->randomElement(['Makeup Kit', 'Kuas Makeup', 'Peralatan Salon']),
            
            str_contains($subKategoriName, 'Fotografer') => 
                fake()->randomElement(['Kamera DSLR', 'Lighting Kit', 'Tripod']),
            
            str_contains($subKategoriName, 'Les') => 
                fake()->randomElement(['Buku Materi', 'Laptop', 'Alat Peraga']),
            
            str_contains($subKategoriName, 'Laundry') => 
                fake()->randomElement(['Mesin Cuci', 'Setrika', 'Peralatan Laundry']),
            
            default => fake()->randomElement(['Peralatan Standar', 'Toolkit Basic', 'Perlengkapan Kerja'])
        };
    }
}
