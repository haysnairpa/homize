<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopServices extends Model
{
    public $table = "shop_services";

    protected $fillable = [
        "id_shop",
        "id_services",
    ];

    // one to one from shop services to order
    public function order()
    {
        return $this->belongsTo("App\Models\Order", "id", "id_services");
    }

    // many to one from shop services to shop
    public function shop()
    {
        return $this->belongsTo("App\Models\Shop", "id_shop", "id");
    }

    // one to one from shop services to services
    public function services()
    {
        return $this->belongsTo("App\Models\Services", "id_services", "id");
    }
}
