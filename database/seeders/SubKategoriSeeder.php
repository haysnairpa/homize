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
                "nama" => "Asisten Rumah Tangga",
                "seri_sub_kategori" => "ART",
                "id_kategori" => "1",
            ],
            [
                "nama" => "Pengasuh Anak / Lansia",
                "seri_sub_kategori" => "PAL",
                "id_kategori" => "1",
            ],
            [
                "nama" => "Layanan Kebersihan",
                "seri_sub_kategori" => "LYK",
                "id_kategori" => "1",
            ],
            [
                "nama" => "Tukang Kebun / Tanaman",
                "seri_sub_kategori" => "TKT",
                "id_kategori" => "1",
            ],
            [
                "nama" => "Layanan Cuci Mobil / Motor",
                "seri_sub_kategori" => "LCM",
                "id_kategori" => "1",
            ],
            [
                "nama" => "Laundry Antar Jemput",
                "seri_sub_kategori" => "LAJ",
                "id_kategori" => "1",
            ],
            [
                "nama" => "Security / Satpam Pribadi",
                "seri_sub_kategori" => "SSP",
                "id_kategori" => "1",
            ],
            [
                "nama" => "Tukang Jahit / Permak",
                "seri_sub_kategori" => "TJP",
                "id_kategori" => "1",
            ],
            [
                "nama" => "Ahli Servis Otomatif",
                "seri_sub_kategori" => "ASO",
                "id_kategori" => "2",
            ],
            [
                "nama" => "Teknisi Listrik",
                "seri_sub_kategori" => "TNL",
                "id_kategori" => "2",
            ],
            [
                "nama" => "Spesialis Saluran Air",
                "seri_sub_kategori" => "SSA",
                "id_kategori" => "2",
            ],
            [
                "nama" => "Konstruksi / Renovasi Rumah",
                "seri_sub_kategori" => "KRR",
                "id_kategori" => "2",
            ],
            [
                "nama" => "Servis AC, Kulkas, Televisi",
                "seri_sub_kategori" => "SKT",
                "id_kategori" => "2",
            ],
            [
                "nama" => "Servis Handphone / Laptop",
                "seri_sub_kategori" => "SHL",
                "id_kategori" => "2",
            ],
            [
                "nama" => "Les Akademik & Persiapan Ujian Privat",
                "seri_sub_kategori" => "LPU",
                "id_kategori" => "3",
            ],
            [
                "nama" => "Pengajar Al-Qur'an Privat",
                "seri_sub_kategori" => "PAP",
                "id_kategori" => "3",
            ],
            [
                "nama" => "Tutor Bahasa Asing",
                "seri_sub_kategori" => "TBA",
                "id_kategori" => "3",
            ],
            [
                "nama" => "Kursus Bela Diri",
                "seri_sub_kategori" => "KBD",
                "id_kategori" => "3",
            ],
            [
                "nama" => "Les Seni & Olahraga (Tari, Musik, Renang)",
                "seri_sub_kategori" => "TSO",
                "id_kategori" => "3",
            ],
            [
                "nama" => "Kursus Mengemudi",
                "seri_sub_kategori" => "KSM",
                "id_kategori" => "3",
            ],
            [
                "nama" => "Medical Check Up Home-to-Home",
                "seri_sub_kategori" => "MCU",
                "id_kategori" => "4",
            ],
            [
                "nama" => "Perawat atau Pendamping Pasien",
                "seri_sub_kategori" => "PPP",
                "id_kategori" => "4",
            ],
            [
                "nama" => "Spesialis Fisioterapi atau Pijat",
                "seri_sub_kategori" => "SFP",
                "id_kategori" => "4",
            ],
            [
                "nama" => "Konsultan Psikologi",
                "seri_sub_kategori" => "KSP",
                "id_kategori" => "4",
            ],
            [
                "nama" => "Yoga & Fitness Trainer",
                "seri_sub_kategori" => "YFT",
                "id_kategori" => "4",
            ],
            [
                "nama" => "Facial Treatment",
                "seri_sub_kategori" => "SFP",
                "id_kategori" => "4",
            ],
            [
                "nama" => "Make-up Artists",
                "seri_sub_kategori" => "MUA",
                "id_kategori" => "4",
            ],
            [
                "nama" => "Pangkas Rumbut Home-to-Home",
                "seri_sub_kategori" => "PRH",
                "id_kategori" => "4",
            ],
            [
                "nama" => "Percetakan Digital",
                "seri_sub_kategori" => "PTD",
                "id_kategori" => "5",
            ],
            [
                "nama" => "Desain Grafis dan Ilustrasi",
                "seri_sub_kategori" => "DGI",
                "id_kategori" => "5",
            ],
            [
                "nama" => "Editing Foto / Video",
                "seri_sub_kategori" => "EFV",
                "id_kategori" => "5",
            ],
            [
                "nama" => "Konsultan Digital Marketing",
                "seri_sub_kategori" => "KDM",
                "id_kategori" => "5",
            ],
            [
                "nama" => "Software Engineer",
                "seri_sub_kategori" => "SER",
                "id_kategori" => "5",
            ],
            [
                "nama" => "Konten Writing & Copywriting",
                "seri_sub_kategori" => "KWC",
                "id_kategori" => "5",
            ],
            [
                "nama" => "Dekorator Acara",
                "seri_sub_kategori" => "DKA",
                "id_kategori" => "6",
            ],
            [
                "nama" => "MC / Host",
                "seri_sub_kategori" => "MCH",
                "id_kategori" => "6",
            ],
            [
                "nama" => "Band / Performer Musik",
                "seri_sub_kategori" => "BPM",
                "id_kategori" => "6",
            ],
            [
                "nama" => "Performer Dance / Tari",
                "seri_sub_kategori" => "PDT",
                "id_kategori" => "6",
            ],
            [
                "nama" => "Photographer / Videographer",
                "seri_sub_kategori" => "PGV",
                "id_kategori" => "6",
            ],
            [
                "nama" => "Sewa Kendaraan (Sepeda, Mobil, Motor)",
                "seri_sub_kategori" => "SWK",
                "id_kategori" => "7",
            ],
            [
                "nama" => "Perlengkapan Acara / Konten (Sound System, Proyektor, Kamera,dll)",
                "seri_sub_kategori" => "PAK",
                "id_kategori" => "7",
            ],
            [
                "nama" => "Sewa Kendaraan (Sepeda, Mobil, Motor)",
                "seri_sub_kategori" => "SWK",
                "id_kategori" => "7",
            ],
            [
                "nama" => "Sewa Wardrobe (Jas, Dress, Baju Adat)",
                "seri_sub_kategori" => "SAW",
                "id_kategori" => "7",
            ],
            [
                "nama" => "Sewa Peralatan Camping",
                "seri_sub_kategori" => "SPC",
                "id_kategori" => "7",
            ],
            [
                "nama" => "Sewa Peralatan Masuk",
                "seri_sub_kategori" => "SPM",
                "id_kategori" => "7",
            ],
            [
                "nama" => "Sewa Peralatan Masak",
                "seri_sub_kategori" => "SPM",
                "id_kategori" => "7",
            ],
            [
                "nama" => "Sewa Console Game",
                "seri_sub_kategori" => "SCG",
                "id_kategori" => "7",
            ]
        ];

        SubKategori::insert($sub_kategori);
    }
}
