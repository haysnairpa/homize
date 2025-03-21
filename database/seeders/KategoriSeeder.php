<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
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

        Kategori::insert($kategori);
    }
}
