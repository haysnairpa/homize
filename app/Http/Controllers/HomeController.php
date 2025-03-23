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
        $kategori = DB::select("SELECT nama, id FROM kategori");
        $sub_kategori = DB::select("SELECT s.nama, s.seri_sub_kategori, s.id, s.id_kategori
                                    FROM sub_kategori s");

        $ids = DB::select("SELECT `id` FROM `sub_kategori`;");

        $bottomNavigation = DB::select("SELECT c.nama AS category_name
                                        FROM sub_kategori c");

        return view('home.home', compact('kategori', 'sub_kategori', 'bottomNavigation', 'ids'));
    }
}
