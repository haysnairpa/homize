<?php

namespace Database\Seeders;

use App\Models\SubKategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_kategori = [
            [
                "name" => "Asisten Rumah Tangga",
                "series_number" => "ART",
                "id_category" => "1",
            ],
            [
                "name" => "Pengasuh Anak / Lansia",
                "series_number" => "PAL",
                "id_category" => "1",
            ],
            [
                "name" => "Layanan Kebersihan",
                "series_number" => "LYK",
                "id_category" => "1",
            ],
            [
                "name" => "Tukang Kebun / Tanaman",
                "series_number" => "TKT",
                "id_category" => "1",
            ],
            [
                "name" => "Layanan Cuci Mobil / Motor",
                "series_number" => "LCM",
                "id_category" => "1",
            ],
            [
                "name" => "Laundry Antar Jemput",
                "series_number" => "LAJ",
                "id_category" => "1",
            ],
            [
                "name" => "Security / Satpam Pribadi",
                "series_number" => "SSP",
                "id_category" => "1",
            ],
            [
                "name" => "Tukang Jahit / Permak",
                "series_number" => "TJP",
                "id_category" => "1",
            ],
            [
                "name" => "Ahli Servis Otomatif",
                "series_number" => "ASO",
                "id_category" => "2",
            ],
            [
                "name" => "Teknisi Listrik",
                "series_number" => "TNL",
                "id_category" => "2",
            ],
            [
                "name" => "Spesialis Saluran Air",
                "series_number" => "SSA",
                "id_category" => "2",
            ],
            [
                "name" => "Konstruksi / Renovasi Rumah",
                "series_number" => "KRR",
                "id_category" => "2",
            ],
            [
                "name" => "Servis AC, Kulkas, Televisi",
                "series_number" => "SKT",
                "id_category" => "2",
            ],
            [
                "name" => "Servis Handphone / Laptop",
                "series_number" => "SHL",
                "id_category" => "2",
            ],
            [
                "name" => "Les Akademik & Persiapan Ujian Privat",
                "series_number" => "LPU",
                "id_category" => "3",
            ],
            [
                "name" => "Pengajar Al-Qur'an Privat",
                "series_number" => "PAP",
                "id_category" => "3",
            ],
            [
                "name" => "Tutor Bahasa Asing",
                "series_number" => "TBA",
                "id_category" => "3",
            ],
            [
                "name" => "Kursus Bela Diri",
                "series_number" => "KBD",
                "id_category" => "3",
            ],
            [
                "name" => "Les Seni & Olahraga (Tari, Musik, Renang)",
                "series_number" => "TSO",
                "id_category" => "3",
            ],
            [
                "name" => "Kursus Mengemudi",
                "series_number" => "KSM",
                "id_category" => "3",
            ],
            [
                "name" => "Medical Check Up Home-to-Home",
                "series_number" => "MCU",
                "id_category" => "4",
            ],
            [
                "name" => "Perawat atau Pendamping Pasien",
                "series_number" => "PPP",
                "id_category" => "4",
            ],
            [
                "name" => "Spesialis Fisioterapi atau Pijat",
                "series_number" => "SFP",
                "id_category" => "4",
            ],
            [
                "name" => "Konsultan Psikologi",
                "series_number" => "KSP",
                "id_category" => "4",
            ],
            [
                "name" => "Yoga & Fitness Trainer",
                "series_number" => "YFT",
                "id_category" => "4",
            ],
            [
                "name" => "Facial Treatment",
                "series_number" => "SFP",
                "id_category" => "4",
            ],
            [
                "name" => "Make-up Artists",
                "series_number" => "MUA",
                "id_category" => "4",
            ],
            [
                "name" => "Pangkas Rumbut Home-to-Home",
                "series_number" => "PRH",
                "id_category" => "4",
            ],
            [
                "name" => "Percetakan Digital",
                "series_number" => "PTD",
                "id_category" => "5",
            ],
            [
                "name" => "Desain Grafis dan Ilustrasi",
                "series_number" => "DGI",
                "id_category" => "5",
            ],
            [
                "name" => "Editing Foto / Video",
                "series_number" => "EFV",
                "id_category" => "5",
            ],
            [
                "name" => "Konsultan Digital Marketing",
                "series_number" => "KDM",
                "id_category" => "5",
            ],
            [
                "name" => "Software Engineer",
                "series_number" => "SER",
                "id_category" => "5",
            ],
            [
                "name" => "Konten Writing & Copywriting",
                "series_number" => "KWC",
                "id_category" => "5",
            ],
            [
                "name" => "Dekorator Acara",
                "series_number" => "DKA",
                "id_category" => "6",
            ],
            [
                "name" => "MC / Host",
                "series_number" => "MCH",
                "id_category" => "6",
            ],
            [
                "name" => "Band / Performer Musik",
                "series_number" => "BPM",
                "id_category" => "6",
            ],
            [
                "name" => "Performer Dance / Tari",
                "series_number" => "PDT",
                "id_category" => "6",
            ],
            [
                "name" => "Photographer / Videographer",
                "series_number" => "PGV",
                "id_category" => "6",
            ],
            [
                "name" => "Sewa Kendaraan (Sepeda, Mobil, Motor)",
                "series_number" => "SWK",
                "id_category" => "7",
            ],
            [
                "name" => "Perlengkapan Acara / Konten (Sound System, Proyektor, Kamera,dll)",
                "series_number" => "PAK",
                "id_category" => "7",
            ],
            [
                "name" => "Sewa Kendaraan (Sepeda, Mobil, Motor)",
                "series_number" => "SWK",
                "id_category" => "7",
            ],
            [
                "name" => "Sewa Wardrobe (Jas, Dress, Baju Adat)",
                "series_number" => "SAW",
                "id_category" => "7",
            ],
            [
                "name" => "Sewa Peralatan Camping",
                "series_number" => "SPC",
                "id_category" => "7",
            ],
            [
                "name" => "Sewa Peralatan Masuk",
                "series_number" => "SPM",
                "id_category" => "7",
            ],
            [
                "name" => "Sewa Peralatan Masak",
                "series_number" => "SPM",
                "id_category" => "7",
            ],
            [
                "name" => "Sewa Console Game",
                "series_number" => "SCG",
                "id_category" => "7",
            ]
        ];

        SubKategori::insert($sub_kategori);
    }
}
