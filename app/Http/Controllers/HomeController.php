<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Kategori;
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
                'a.media_url',
                DB::raw('COALESCE(AVG(r.rate), 0) as rating_avg'),
                DB::raw('COUNT(DISTINCT r.id) as rating_count')
            ])
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->leftJoin('aset as a', 'l.id', '=', 'a.id_layanan')
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
                'tl.satuan',
                'a.media_url'
            ])
            ->orderBy('rating_avg', 'desc')
            ->limit(8)
            ->get();

        // Ambil semua kategori untuk filter
        $allKategori = Kategori::all();

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

        // Update the popular merchants query
        $popularMerchants = DB::table('merchant as m')
            ->select([
                'm.id',
                'm.nama_usaha',
                'm.profile_url',
                DB::raw('COUNT(DISTINCT tf.id_user) as followers_count'),
                DB::raw('COALESCE(AVG(r.rate), 0) as rating_avg'),
                DB::raw('COUNT(DISTINCT r.id) as rating_count'),
                DB::raw('COUNT(DISTINCT l.id) as services_count')
            ])
            ->leftJoin('toko_favorit as tf', 'm.id', '=', 'tf.id_merchant')
            ->leftJoin('layanan as l', 'm.id', '=', 'l.id_merchant')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->groupBy('m.id', 'm.nama_usaha', 'm.profile_url')
            ->orderBy('followers_count', 'desc')
            ->orderBy('rating_avg', 'desc') // Secondary sort by rating when followers are equal
            ->limit(4)
            ->get();

        // Share the navigation data with all views
        $sharedData = [
            'kategori' => $kategori,
            'sub_kategori' => $sub_kategori,
            'navigation' => $navigation,
            'bottomNavigation' => $bottomNavigation,
            'ids' => $ids,
            'layanan' => $layanan,
            'wishlists' => $wishlists,
            'allKategori' => $allKategori,
            'popularMerchants' => $popularMerchants
        ];

        view()->share($sharedData);

        return view('home.home', $sharedData);
    }

    public function filterLayanan(Request $request)
    {
        $kategoriId = $request->kategori_id;
        $sortBy = $request->sort_by;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;

        $query = DB::table('layanan as l')
            ->select([
                'l.*',
                'tl.harga',
                'tl.satuan',
                'sk.id_kategori',
                DB::raw('COALESCE(AVG(r.rate), 0) as rating_avg'),
                DB::raw('COUNT(DISTINCT r.id) as rating_count')
            ])
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->leftJoin('sub_kategori as sk', 'l.id_sub_kategori', '=', 'sk.id');

        if ($kategoriId) {
            $query->where('sk.id_kategori', $kategoriId);
        }

        if ($minPrice) {
            $query->where('tl.harga', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('tl.harga', '<=', $maxPrice);
        }

        $query->groupBy([
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
            'tl.satuan',
            'sk.id_kategori'
        ]);

        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('tl.harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('tl.harga', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating_avg', 'desc');
                break;
            default:
                $query->orderBy('l.created_at', 'desc');
                break;
        }

        $layanan = $query->limit(20)->get();

        return response()->json([
            'html' => view('home.partials.layanan-grid', compact('layanan'))->render()
        ]);
    }
}
