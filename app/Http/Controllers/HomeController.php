<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Services;

class HomeController extends Controller
{
    public function navigation_data()
    {
        $navigation = DB::select("SELECT j.name AS jasa_name, 
                                    GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS category_names
                                FROM jasa_category j
                                JOIN category c ON c.id_category = j.id
                                GROUP BY j.name;");

        $bottomNavigation = DB::select("SELECT c.name AS category_name
                                        FROM category c");

        $featuredServices = Services::with(['shop_services.shop.category'])
                            ->inRandomOrder()
                            ->limit(3)
                            ->get();

        $popularServices = Services::with(['shop_services.shop.category'])
                            ->inRandomOrder()
                            ->limit(4)
                            ->get();

        return view('home.home', compact('navigation', 'bottomNavigation', 'featuredServices', 'popularServices'));
    }


}
