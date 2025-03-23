<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ShopServices;

class HomeController extends Controller
{
    public function navigation_data()
    {
        $navigation = DB::select("SELECT j.id, j.nama AS jasa_name, GROUP_CONCAT(c.nama
                                ORDER BY c.nama SEPARATOR ', ') AS category_names
                                FROM kategori j
                                JOIN sub_kategori c ON c.id_kategori = j.id
                                GROUP BY j.id, j.nama;
                                ");

        $ids = DB::select("SELECT `id` FROM `sub_kategori`;");

        $bottomNavigation = DB::select("SELECT c.nama AS category_name
                                        FROM sub_kategori c");

        // Query untuk mendapatkan layanan populer
        $layanan = DB::table('layanan as l')
            ->select([
                'l.*',
                'tl.harga',
                'tl.satuan', 
                DB::raw('COALESCE(AVG(r.rate), 0) as rating_avg'),
                DB::raw('COUNT(DISTINCT r.id) as rating_count')
            ])
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->groupBy([
                'l.id',
                'l.id_merchant',
                'l.id_jam_operasional',
                'l.id_sub_kategori', 
                'l.nama_layanan',
                'l.deskripsi_layanan',
                'l.pengalaman',
                'l.created_at',
                'l.updated_at',
                'tl.harga',
                'tl.satuan'
            ])
            ->orderBy('rating_avg', 'desc')
            ->limit(8)
            ->get();

        return view('home.home', compact('navigation', 'bottomNavigation', 'ids', 'layanan'));
    }
}
