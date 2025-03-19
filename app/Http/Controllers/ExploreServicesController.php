<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExploreServicesController extends Controller
{
    public function show_services($ids)
    {
        $services = DB::select("SELECT se.name AS service_name, se.price AS service_price, se.image_url AS service_image, c.`name` AS category_name, s.name AS shop_name, s.profile_url AS shop_profile
                    FROM jasa_category jc
                    JOIN category c ON c.id_category = jc.id
                    JOIN shop s ON s.id_category = c.id
                    JOIN shop_services sh ON sh.id_shop = s.id
                    JOIN services se ON se.id = sh.id_services
                    WHERE jc.id = ?", [$ids]);

        dd($services);
    }
}
