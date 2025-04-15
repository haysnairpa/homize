<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SubKategori;
use App\Models\Layanan;
use App\Models\Merchant;

class JasaController extends Controller
{
    public function get_jasa(Request $request, $ids)
    {
        // Start building the query
        $query = DB::table('layanan as l')
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
                DB::raw('GROUP_CONCAT(DISTINCT h.nama_hari ORDER BY h.id) as hari'),
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
            ->leftJoin('jam_operasional_hari as joh', 'jo.id', '=', 'joh.id_jam_operasional')
            ->leftJoin('hari as h', 'joh.id_hari', '=', 'h.id')
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->leftJoin('rating as r', 'l.id', '=', 'r.id_layanan')
            ->where('l.id_sub_kategori', $ids);

        // Apply rating filter if provided
        if ($request->has('rating') && !empty($request->rating)) {
            $ratingFilters = $request->rating;
            if (!empty($ratingFilters)) {
                $query->havingRaw('ROUND(rating_avg) IN (' . implode(',', $ratingFilters) . ')');
            }
        }

        // Apply price sorting if provided
        if ($request->has('price') && !empty($request->price)) {
            if ($request->price === 'lowest') {
                $query->orderBy('tl.harga', 'asc');
            } elseif ($request->price === 'highest') {
                $query->orderBy('tl.harga', 'desc');
            }
        }

        // Apply sorting if provided
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('l.created_at', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('rating_avg', 'desc');
                    break;
                case 'price_low':
                    $query->orderBy('tl.harga', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('tl.harga', 'desc');
                    break;
                default:
                    // Default sorting
                    $query->orderBy('l.id', 'desc');
            }
        } else {
            // Default sorting if no sort parameter
            $query->orderBy('l.id', 'desc');
        }

        // Group by the required fields
        $jasa = $query->groupBy([
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
            'sk.nama',
            'tl.harga',
            'tl.satuan',
            'tl.tipe_durasi'
        ])->get();

        // Get the category name for display
        $kategoriData = SubKategori::find($ids);

        // Get navigation data
        app(HomeController::class)->navigation_data();

        return view('jasa.index', [
            'jasa' => $jasa,
            'kategoriData' => $kategoriData,
            'filters' => [
                'rating' => $request->rating ?? [],
                'price' => $request->price ?? '',
                'sort' => $request->sort ?? 'newest'
            ]
        ]);
    }
}
