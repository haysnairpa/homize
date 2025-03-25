<?php

namespace App\Http\Controllers;

use App\Models\TokoFavorit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokoFavoritController extends Controller
{
    public function toggle(Request $request)
    {
        $user_id = Auth::id();
        $merchant_id = $request->merchant_id;

        $existing = TokoFavorit::where('id_user', $user_id)
            ->where('id_merchant', $merchant_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'unfollowed']);
        }

        TokoFavorit::create([
            'id_user' => $user_id,
            'id_merchant' => $merchant_id
        ]);

        return response()->json(['status' => 'followed']);
    }
}
