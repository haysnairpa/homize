<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merchant;

class MerchantController extends Controller
{
    // Delete a merchant by ID
    public function destroy($id)
    {
        $merchant = Merchant::findOrFail($id);
        $merchant->delete();
        return response()->json(['success' => true]);
    }
}
