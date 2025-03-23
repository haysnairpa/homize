<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SubKategori;
use App\Models\Layanan;
use App\Models\Merchant;

class JasaController extends Controller
{
    public function get_jasa($ids)
    {
        // dd($ids);
        // Get services with their related data
        $jasa = DB::table('layanan as l')
            ->select([
                'l.id',
                'l.nama_layanan',
                'l.deskripsi_layanan',
                'l.pengalaman',
                'm.id as merchant_id',
                'm.nama_usaha',
                'm.profile_url',
                'm.alamat',
                'jo.jam_buka',
                'jo.jam_tutup',
                'jo.id_hari',
                'sk.nama as kategori_nama',
                'tl.harga',
                'tl.satuan',
                'tl.tipe_durasi',
                DB::raw('COALESCE(AVG(r.rate), 0) as rating_avg'),
                DB::raw('COUNT(DISTINCT r.id) as rating_count')
            ])
            ->join('merchant as m', 'l.id_merchant', '=', 'm.id')
            ->join('sub_kategori as sk', 'l.id_sub_kategori', '=', 'sk.id')
            ->join('jam_operasional as jo', 'l.id_jam_operasional', '=', 'jo.id')
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->where('l.id_sub_kategori', $ids)
            ->groupBy([
                'l.id',
                'l.nama_layanan',
                'l.deskripsi_layanan',
                'l.pengalaman',
                'm.id',
                'm.nama_usaha',
                'm.profile_url',
                'm.alamat',
                'jo.jam_buka',
                'jo.jam_tutup',
                'jo.id_hari',
                'sk.nama',
                'tl.harga',
                'tl.satuan',
                'tl.tipe_durasi'
            ])
            ->get();

        // Get the category name for display
        $kategoriData = SubKategori::find($ids);
        
        // Get navigation data
        app(HomeController::class)->navigation_data();
        
        return view('jasa.index', [
            'jasa' => $jasa,
            'kategoriData' => $kategoriData
        ]);
    }
}
