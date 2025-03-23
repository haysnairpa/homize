<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExploreServicesController extends Controller
{
    public function show_services($ids)
    {
        $services = DB::select("SELECT 
                                l.id, 
                                l.nama_layanan, 
                                l.pengalaman,
                                l.id_sub_kategori,
                                sk.nama as sub_kategori_nama,
                                k.id as kategori_id,
                                k.nama as kategori_nama,
                                tl.harga, 
                                tl.satuan, 
                                jo.jam_buka, 
                                jo.jam_tutup, 
                                jo.id_hari, 
                                m.nama_usaha, 
                                m.profile_url,
                                m.id_sub_kategori as merchant_sub_kategori
                            FROM layanan l
                            LEFT JOIN tarif_layanan tl ON tl.id_layanan = l.id
                            LEFT JOIN jam_operasional jo ON jo.id = l.id_jam_operasional
                            LEFT JOIN layanan_merchant lm ON lm.id_layanan = l.id
                            LEFT JOIN merchant m ON m.id = lm.id_merchant
                            JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                            JOIN kategori k ON k.id = sk.id_kategori
                            WHERE k.id = ?", [$ids]);

        dd($services);
    }
}
