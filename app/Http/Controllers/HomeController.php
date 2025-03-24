<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ShopServices;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function navigation_data()
    {
        $kategori = DB::select("SELECT nama, id FROM kategori");
        $sub_kategori = DB::select("SELECT s.nama, s.seri_sub_kategori, s.id, s.id_kategori
                                    FROM sub_kategori s");
        $navigation = DB::select("SELECT j.id, j.nama AS jasa_name, GROUP_CONCAT(c.nama
        ORDER BY c.nama SEPARATOR ', ') AS category_names
        FROM kategori j
        JOIN sub_kategori c ON c.id_kategori = j.id
        GROUP BY j.id, j.nama;
        ");

        $ids = DB::select("SELECT `id` FROM `sub_kategori`;");

        $bottomNavigation = DB::select("SELECT c.nama AS category_name 
                                        FROM sub_kategori c");

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

        $wishlists = [];
        if (Auth::check()) {
            $wishlists = DB::table('wishlists as w')
            ->select([
                'w.id_layanan', 
                'w.id_user',
                'l.nama_layanan',
                'l.deskripsi_layanan',
                'm.nama_usaha',
                'm.profile_url',
                'tl.harga',
                'tl.satuan',
                'tl.tipe_durasi',
                'tl.durasi'
            ])
            ->leftJoin('layanan as l', 'w.id_layanan', '=', 'l.id')
            ->leftJoin('merchant as m', 'l.id_merchant', '=', 'm.id')
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->where('w.id_user', Auth::id())
            ->get();
        }

        // Share the navigation data with all views
        view()->share([
            'kategori' => $kategori,
            'sub_kategori' => $sub_kategori,
            'navigation' => $navigation,
            'bottomNavigation' => $bottomNavigation,
            'ids' => $ids,
            'layanan' => $layanan,
            'wishlists' => $wishlists
        ]);

        return view('home.home', compact('kategori', 'sub_kategori', 'navigation', 'bottomNavigation', 'ids', 'layanan', 'wishlists'));
    }
}
