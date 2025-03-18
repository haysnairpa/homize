<?php

namespace Database\Seeders;

use App\Models\JasaCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JasaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jasa_category = [
            [
                "name" => "Jasa Rumah Tangga",
            ],
            [
                "name" => "Jasa Perbaikan & Instalasi",
            ],
            [
                "name" => "Jasa Pendidikan & Bimbingan",
            ],
            [
                "name" => "Jasa Kesehatan & Kecantikan",
            ],
            [
                "name" => "Jasa Kreatif & Digital"
            ],
            [
                "name" => "Jasa Event Organizer"
            ],
            [
                "name" => "Jasa Penyewaan Barang",
            ],
        ];

        foreach ($jasa_category as $category) {
            JasaCategory::create($category);
        }
    }
}
