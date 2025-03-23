<?php

namespace Database\Seeders;

use App\Models\Sertifikasi;
use App\Models\Layanan;
use App\Models\SubKategori;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SertifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $layananIds = Layanan::pluck('id')->toArray();
        
        $sertifikasi = [];
        
        // Create certifications for about 40% of services
        $selectedLayanan = array_rand(array_flip($layananIds), ceil(count($layananIds) * 0.4));
        
        foreach ($selectedLayanan as $layananId) {
            $layanan = Layanan::find($layananId);
            $subKategori = SubKategori::find($layanan->id_sub_kategori);
            
            // Generate 1-2 certifications per service
            $numCerts = rand(1, 2);
            
            for ($i = 0; $i < $numCerts; $i++) {
                $certType = $this->getCertificationForService($subKategori->nama);
                
                $sertifikasi[] = [
                    'id_layanan' => $layananId,
                    'nama_sertifikasi' => $certType,
                    'media_url' => $faker->imageUrl(640, 480, 'certificate'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($sertifikasi, 100) as $chunk) {
            Sertifikasi::insert($chunk);
        }
    }

    private function getCertificationForService($subKategoriName)
    {
        // Define specific certifications based on service category
        return match (true) {
            str_contains($subKategoriName, 'Asisten Rumah Tangga') => 
                fake()->randomElement([
                    'Sertifikasi Pelatihan ART Profesional',
                    'Sertifikat Keterampilan Rumah Tangga',
                    'Sertifikat Pelatihan Kebersihan'
                ]),
            
            str_contains($subKategoriName, 'Teknisi') => 
                fake()->randomElement([
                    'Sertifikasi Teknisi Professional',
                    'Sertifikat Keahlian Teknik',
                    'Lisensi Teknisi Resmi'
                ]),
            
            str_contains($subKategoriName, 'Make-up') => 
                fake()->randomElement([
                    'Sertifikasi MUA Professional',
                    'Beauty Artist Certificate',
                    'Sertifikat Kecantikan International'
                ]),
            
            str_contains($subKategoriName, 'Fotografer') => 
                fake()->randomElement([
                    'Sertifikasi Fotografer Professional',
                    'Photography Master Certificate',
                    'Digital Imaging Expert'
                ]),
            
            str_contains($subKategoriName, 'Les') => 
                fake()->randomElement([
                    'Sertifikasi Pengajar Professional',
                    'Teaching Certificate',
                    'Lisensi Tutor'
                ]),
            
            str_contains($subKategoriName, 'Security') => 
                fake()->randomElement([
                    'Sertifikasi Gada Pratama',
                    'Sertifikat Keamanan Professional',
                    'Security Training Certificate'
                ]),
            
            default => fake()->randomElement([
                'Sertifikasi Professional',
                'Sertifikat Keahlian Dasar',
                'Basic Training Certificate'
            ])
        };
    }
}
