<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function navigation_data()
    {
        $navigation = DB::select("SELECT j.name AS jasa_name, 
                                    GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS category_names
                                FROM jasa_category j
                                JOIN category c ON c.id_category = j.id
                                GROUP BY j.name;
");
        return view('home.home', compact('navigation'));
    }
}
