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
                    'media_url' => $this->getImageUrlForAsset($assetType),
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

    private function getImageUrlForAsset($assetType): string
    {
        // Using picsum.photos for realistic images
        $width = 640;
        $height = 480;
        $imageId = rand(1, 1000); // Picsum has about 1000 images

        return match (true) {
            str_contains($assetType, 'Kamera') => 
                "https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w={$width}&h={$height}",
            
            str_contains($assetType, 'Makeup') => 
                "https://images.unsplash.com/photo-1596462502278-27bfdc403348?w={$width}&h={$height}",
            
            str_contains($assetType, 'Peralatan Kebersihan') => 
                "https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w={$width}&h={$height}",
            
            str_contains($assetType, 'Laptop') => 
                "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w={$width}&h={$height}",
            
            str_contains($assetType, 'Mesin Cuci') => 
                "https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w={$width}&h={$height}",
            
            default => "https://picsum.photos/id/{$imageId}/{$width}/{$height}"
        };
    }
}
