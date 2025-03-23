<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JasaController extends Controller
{
    public function get_jasa($ids)
    {
        $jasa = DB::select("SELECT l.nama_layanan, l.pengalaman, l.id, jo.id_hari, jo.jam_buka, jo.jam_tutup, m.nama_usaha, m.profile_url
                            FROM `sub_kategori` s
                            JOIN `layanan` l ON l.id_sub_kategori = s.id
                            JOIN `merchant` m ON m.id_sub_kategori = s.id
                            JOIN `jam_operasional` jo ON jo.id = l.id_jam_operasional
                            WHERE s.id = ?", [$ids]);
        dd($jasa);
    }
}
