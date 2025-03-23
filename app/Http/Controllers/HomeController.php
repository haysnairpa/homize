<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ShopServices;

class HomeController extends Controller
{
    public function navigation_data()
    {
        $navigation = DB::select("SELECT j.id, j.nama AS jasa_name, GROUP_CONCAT(c.nama
                                ORDER BY c.nama SEPARATOR ', ') AS category_names
                                FROM kategori j
                                JOIN sub_kategori c ON c.id_kategori = j.id
                                GROUP BY j.id, j.nama;
                                ");

        $ids = DB::select("SELECT `id` FROM `sub_kategori`;");

        $bottomNavigation = DB::select("SELECT c.nama AS category_name
                                        FROM sub_kategori c");

        return view('home.home', compact('navigation', 'bottomNavigation', 'ids'));
    }
}
