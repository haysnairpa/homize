<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Support\Facades\DB;

class LayananController extends Controller
{
    public function show($id)
    {
        // Ambil data navigasi dari HomeController
        app(HomeController::class)->navigation_data();

        $layanan = DB::table('layanan as l')
            ->select([
                'l.*',
                'm.id as id_merchant',
                'm.nama_usaha',
                'm.profile_url',
                'jo.jam_buka',
                'jo.jam_tutup',
                'jo.id_hari',
                'tl.harga',
                'tl.satuan',
                'tl.id_revisi',
                DB::raw('COALESCE(AVG(r.rate), 0) as rating_avg'),
                DB::raw('COUNT(DISTINCT r.id) as rating_count')
            ])
            ->join('merchant as m', 'l.id_merchant', '=', 'm.id')
            ->join('jam_operasional as jo', 'l.id_jam_operasional', '=', 'jo.id')
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->where('l.id', $id)
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
                'm.id',
                'm.nama_usaha',
                'm.profile_url',
                'jo.jam_buka',
                'jo.jam_tutup',
                'jo.id_hari',
                'tl.harga',
                'tl.satuan',
                'tl.id_revisi'
            ])
            ->first();

        // Ambil rating terpisah untuk menghindari masalah GROUP BY
        $ratings = DB::table('rating as r')
            ->select(['r.*', 'u.nama as user_name']) // Ubah name menjadi nama
            ->leftJoin('users as u', 'r.id_user', '=', 'u.id')
            ->where('r.id_layanan', $id)
            ->orderBy('r.created_at', 'desc')
            ->get();

        $ratingStats = [
            5 => $ratings->where('rate', 5)->count(),
            4 => $ratings->where('rate', 4)->count(),
            3 => $ratings->where('rate', 3)->count(),
            2 => $ratings->where('rate', 2)->count(),
            1 => $ratings->where('rate', 1)->count(),
        ];

        return view('layanan.detail', compact('layanan', 'ratings', 'ratingStats'));
    }
}
