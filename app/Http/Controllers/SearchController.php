<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        app(HomeController::class)->navigation_data();

        $query = $request->get('query');
        
        if (empty($query)) {
            return view('search.results', ['services' => [], 'merchants' => [], 'query' => '']);
        }

        // Search services
        $services = DB::table('layanan as l')
            ->select([
                'l.id',
                'l.nama_layanan',
                'l.deskripsi_layanan',
                'm.profile_url',
                'm.nama_usaha',
                'sk.nama as kategori',
                'tl.harga',
                'tl.satuan',
                DB::raw('COALESCE(AVG(r.rate), 0) as rating'),
                DB::raw('COUNT(DISTINCT r.id) as review_count')
            ])
            ->join('merchant as m', 'l.id_merchant', '=', 'm.id')
            ->join('sub_kategori as sk', 'l.id_sub_kategori', '=', 'sk.id')
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->where(function($q) use ($query) {
                $words = explode(' ', $query);
                foreach($words as $word) {
                    $q->where(function($q) use ($word) {
                        $q->where('l.nama_layanan', 'LIKE', "%{$word}%")
                          ->orWhere('l.deskripsi_layanan', 'LIKE', "%{$word}%");
                    });
                }
            })
            ->groupBy([
                'l.id',
                'l.nama_layanan', 
                'l.deskripsi_layanan',
                'm.profile_url',
                'm.nama_usaha',
                'sk.nama',
                'tl.harga',
                'tl.satuan'
            ])
            ->orderByDesc('rating')
            ->paginate(6);

        // Search merchants
        $merchants = DB::table('merchant as m')
            ->select([
                'm.id',
                'm.nama_usaha', 
                'm.alamat',
                'm.profile_url',
                DB::raw('COALESCE(AVG(r.rate), 0) as rating'),
                DB::raw('COUNT(DISTINCT r.id) as review_count'),
                DB::raw('COUNT(DISTINCT l.id) as service_count')
            ])
            ->leftJoin('layanan as l', 'm.id', '=', 'l.id_merchant')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->where(function($q) use ($query) {
                $words = explode(' ', $query);
                foreach($words as $word) {
                    $q->where(function($q) use ($word) {
                        $q->where('m.nama_usaha', 'LIKE', "%{$word}%")
                          ->orWhere('m.alamat', 'LIKE', "%{$word}%");
                    });
                }
            })
            ->groupBy([
                'm.id',
                'm.nama_usaha',
                'm.alamat', 
                'm.profile_url'
            ])
            ->orderByDesc('rating')
            ->paginate(4);

        return view('search.results', [
            'services' => $services,
            'merchants' => $merchants, 
            'query' => $query
        ]);
    }
}