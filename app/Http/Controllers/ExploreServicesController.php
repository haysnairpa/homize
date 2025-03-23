<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExploreServicesController extends Controller
{
    public function show_services($ids)
    {
        // Get navigation data
        app(HomeController::class)->navigation_data();

        $services = DB::table('layanan as l')
            ->select([
                'l.id',
                'l.nama_layanan',
                'l.deskripsi_layanan',
                'l.pengalaman',
                'm.nama_usaha',
                'm.profile_url',
                'sk.nama as kategori_nama',
                'k.nama as main_kategori',
                'tl.harga',
                'tl.satuan',
                DB::raw('COALESCE(AVG(r.rate), 0) as rating_avg'),
                DB::raw('COUNT(DISTINCT r.id) as rating_count')
            ])
            ->join('merchant as m', 'l.id_merchant', '=', 'm.id')
            ->join('sub_kategori as sk', 'l.id_sub_kategori', '=', 'sk.id')
            ->join('kategori as k', 'sk.id_kategori', '=', 'k.id')
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->where('k.id', '=', $ids)
            ->groupBy([
                'l.id',
                'l.nama_layanan',
                'l.deskripsi_layanan',
                'l.pengalaman',
                'm.nama_usaha',
                'm.profile_url',
                'sk.nama',
                'k.nama',
                'tl.harga',
                'tl.satuan'
            ])
            ->get();

        $kategoriData = DB::table('kategori')->where('id', $ids)->first();

        return view('services.show_services', compact('services', 'kategoriData'));
    }
}