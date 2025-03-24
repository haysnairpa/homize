<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $user_id = Auth::id();
        $layanan_id = $request->layanan_id;

        $existing = Wishlist::where('id_user', $user_id)
            ->where('id_layanan', $layanan_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        Wishlist::create([
            'id_user' => $user_id,
            'id_layanan' => $layanan_id
        ]);

        return response()->json(['status' => 'added']);
    }

    public function getContent()
    {
        if (!Auth::check()) {
            return view('components.wishlist-content', [
                'wishlists' => [],
                'isAuthenticated' => false
            ]);
        }

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

        return view('components.wishlist-content', [
            'wishlists' => $wishlists,
            'isAuthenticated' => true
        ]);
    }
}
