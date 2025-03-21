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
                "nama" => "Jasa Rumah Tangga",
            ],
            [
                "nama" => "Jasa Perbaikan & Instalasi",
            ],
            [
                "nama" => "Jasa Pendidikan & Bimbingan",
            ],
            [
                "nama" => "Jasa Kesehatan & Kecantikan",
            ],
            [
                "nama" => "Jasa Kreatif & Digital"
            ],
            [
                "nama" => "Jasa Event Organizer"
            ],
            [
                "nama" => "Jasa Penyewaan Barang",
            ],
        ];

        Kategori::insert($kategori);
    }
}
