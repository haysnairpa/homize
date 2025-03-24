<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = DB::table('layanan as l')
            ->select([
                'l.id',
                'l.nama_layanan as name',
                'l.deskripsi_layanan as description',
                'm.profile_url as image',
                'm.nama_usaha as merchant',
                'sk.nama as category',
                'tl.harga as price',
                'tl.satuan'
            ])
            ->join('merchant as m', 'l.id_merchant', '=', 'm.id')
            ->join('sub_kategori as sk', 'l.id_sub_kategori', '=', 'sk.id')
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->where(function($q) use ($query) {
                $q->where('l.nama_layanan', 'LIKE', "%{$query}%")
                  ->orWhere('l.deskripsi_layanan', 'LIKE', "%{$query}%")
                  ->orWhere('m.nama_usaha', 'LIKE', "%{$query}%")
                  ->orWhere('sk.nama', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'image' => $item->image ?? asset('images/default-merchant.png'),
                    'merchant' => $item->merchant,
                    'category' => $item->category,
                    'price' => $item->price,
                    'satuan' => $item->satuan,
                    'url' => route('layanan.detail', $item->id)
                ];
            });

        return response()->json($results);
    }
} 