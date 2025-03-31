<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Merchant;
use App\Models\JamOperasional;
use App\Models\SubKategori;
use App\Models\Kategori;
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

        // Get all merchants and sub-categories
        $merchants = Merchant::all();
        $subKategories = SubKategori::all();
        $jamOperasional = JamOperasional::first();

        $layanan = [];

        // First, ensure each merchant has services
        foreach ($merchants as $merchant) {
            $numServices = rand(3, 5);
            // Get sub-categories that belong to the merchant's kategori
            $subKategori = SubKategori::where('id_kategori', $merchant->id_kategori)->inRandomOrder()->first();

            if ($subKategori) {
                // Generate service names based on sub-category
                $this->generateServicesForSubCategory($layanan, $merchant, $subKategori, $jamOperasional, $numServices, $faker);
            }
        }

        // Then, create additional services to reach 500 and cover all sub-categories
        $remainingCount = 500 - count($layanan);

        while ($remainingCount > 0) {
            foreach ($subKategories as $subKategori) {
                // Get random merchant that matches this sub-category's kategori
                $merchant = Merchant::where('id_kategori', $subKategori->id_kategori)->inRandomOrder()->first();

                if (!$merchant) {
                    // If no merchant exists for this kategori, create services with random merchant
                    $merchant = $merchants->random();
                }

                $numServices = min(rand(2, 4), $remainingCount);
                $this->generateServicesForSubCategory($layanan, $merchant, $subKategori, $jamOperasional, $numServices, $faker);

                $remainingCount = 500 - count($layanan);
                if ($remainingCount <= 0) break;
            }
        }

        // Shuffle the array to randomize the order
        shuffle($layanan);

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($layanan, 100) as $chunk) {
            Layanan::insert($chunk);
        }
    }

    private function generateServicesForSubCategory(&$layanan, $merchant, $subKategori, $jamOperasional, $numServices, $faker)
    {
        // Define service names based on sub-category type
        $serviceNames = $this->getServiceNamesForCategory($subKategori->nama);
        $prefixes = ['Jasa', 'Layanan', 'Paket'];
        $suffixes = ['Basic', 'Premium', 'Gold', 'Express', 'Regular', 'Pro', 'Expert', 'Standard'];

        for ($i = 0; $i < $numServices; $i++) {
            $serviceName = $faker->randomElement($serviceNames);

            $layanan[] = [
                'id_merchant' => $merchant->id,
                'id_jam_operasional' => $jamOperasional->id,
                'id_sub_kategori' => $subKategori->id,
                'nama_layanan' => $faker->randomElement($prefixes) . ' ' .
                    $serviceName . ' ' .
                    $faker->randomElement($suffixes),
                'deskripsi_layanan' => $this->generateDescription($serviceName, $faker),
                'pengalaman' => $faker->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    private function getServiceNamesForCategory($categoryName)
    {
        // Define specific service names based on category
        return match (true) {
            str_contains($categoryName, 'Asisten Rumah Tangga') => [
                'Pembersihan Rumah',
                'Memasak',
                'Mencuci & Setrika',
                'Pengasuhan Anak',
                'Perawatan Lansia',
                'Kebersihan Dapur',
                'Perapian Kamar'
            ],
            str_contains($categoryName, 'Teknisi Listrik') => [
                'Instalasi Listrik',
                'Perbaikan MCB',
                'Pemasangan Lampu',
                'Pengecekan Arus',
                'Instalasi Stop Kontak',
                'Perbaikan Kabel',
                'Maintenance Panel'
            ],
            str_contains($categoryName, 'Servis AC') => [
                'Cuci AC',
                'Isi Freon',
                'Perbaikan AC',
                'Instalasi AC Baru',
                'Maintenance AC',
                'Ganti Sparepart AC',
                'Check-up AC'
            ],
            str_contains($categoryName, 'Make-up') => [
                'Make-up Wedding',
                'Make-up Wisuda',
                'Make-up Party',
                'Make-up Natural',
                'Make-up Foto Shoot',
                'Make-up Engagement',
                'Tutorial Make-up'
            ],
            str_contains($categoryName, 'Les') => [
                'Bimbingan Privat',
                'Persiapan Ujian',
                'Pelajaran Tambahan',
                'Konsultasi Materi',
                'Latihan Soal',
                'Program Intensif',
                'Kelas Regular'
            ],
            // Add more specific categories as needed
            default => [
                $categoryName . ' Regular',
                $categoryName . ' Premium',
                $categoryName . ' Spesial',
                $categoryName . ' Custom',
                $categoryName . ' Profesional'
            ]
        };
    }

    private function generateDescription($serviceName, $faker)
    {
        $qualities = ['Profesional', 'Berpengalaman', 'Terpercaya', 'Berkualitas', 'Handal'];
        $benefits = ['hasil maksimal', 'kepuasan pelanggan', 'garansi layanan', 'harga bersaing', 'pelayanan ramah'];

        return sprintf(
            "Layanan %s yang %s dengan fokus pada %s. %s",
            $serviceName,
            $faker->randomElement($qualities),
            $faker->randomElement($benefits),
            $faker->realText(100)
        );
    }
}
