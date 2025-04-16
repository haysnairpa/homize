<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExploreServicesController extends Controller
{
    public function show_services(Request $request, $ids)
    {
        // Check if category exists first
        $kategoriData = DB::table('kategori')->where('id', $ids)->first();
        
        if (!$kategoriData) {
            abort(404, 'Category not found');
        }

        // Get navigation data
        app(HomeController::class)->navigation_data();

        // Start building the query
        $query = DB::table('layanan as l')
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
            ->where('k.id', '=', $ids);

        // Apply rating filter if provided
        if ($request->has('rating') && !empty($request->rating)) {
            if ($request->rating === '4') {
                $query->havingRaw('rating_avg >= 4');
            } elseif ($request->rating === '3') {
                $query->havingRaw('rating_avg >= 3');
            }
        }

        // Apply price filter if provided
        if ($request->has('price') && !empty($request->price)) {
            if ($request->price === 'low') {
                $query->where('tl.harga', '<', 100000);
            } elseif ($request->price === 'medium') {
                $query->whereBetween('tl.harga', [100000, 500000]);
            } elseif ($request->price === 'high') {
                $query->where('tl.harga', '>', 500000);
            }
        }

        // Group by the required fields
        $query->groupBy([
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
        ]);

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

        $services = $query->get();

        // Pass the filter values to the view
        $filters = [
            'rating' => $request->rating ?? '',
            'price' => $request->price ?? '',
            'sort' => $request->sort ?? 'newest'
        ];

        return view('services.show_services', compact('services', 'kategoriData', 'filters'));
    }
}