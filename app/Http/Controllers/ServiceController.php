<?php

namespace App\Http\Controllers;

use App\Models\Services;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function show(Services $service)
    {
        $service->load(['shop_services.shop.category']);
        $ids = DB::select("SELECT `id` FROM `category`;");

        // Get shop IDs that offer this service
        $shopIds = $service->shop_services->pluck('shop.id');

        // Get rates for these shops
        $rates = Rate::with(['customer'])
            ->whereIn('id_shop', $shopIds)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get navigation data
        $navigation = DB::select("SELECT j.name AS jasa_name, 
                                GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS category_names
                                FROM jasa_category j
                                JOIN category c ON c.id_category = j.id
                                GROUP BY j.name;");

        $bottomNavigation = DB::select("SELECT c.name AS category_name 
                                        FROM category c");

        return view('services.show', compact('service', 'rates', 'navigation', 'bottomNavigation', 'ids'));
    }
}
