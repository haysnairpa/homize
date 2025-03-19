<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JasaController extends Controller
{
    public function get_jasa($ids)
    {
        $jasa = DB::select("SELECT c.`name` AS category_name, s.`name` AS seller_name, s.`id` AS seller_id, se.`id` AS services_id, se.`name` AS services_name, se.`price` AS services_price, se.`image_url`
                    FROM `category` c
                    JOIN `shop` s ON c.id = s.id_category
                    JOIN `shop_services` sh ON sh.id_shop = s.id
                    JOIN `services` se ON se.id = sh.id_services
                    WHERE c.id = ?", [$ids]);
        dd($jasa);
    }
}
