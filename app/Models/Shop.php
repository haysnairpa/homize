<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public $table = "shop";

    protected $fillable = [
        "name",
        "email",
        "address",
        "id_category",
        "profile_url",
    ];

    // many to one from shop to desired shop
    public function shop()
    {
        return $this->belongsTo("App\Models\DesiredShop", "id", "id_shop");
    }

    // one to many from shop to shop_services
    public function shop_services()
    {
        return $this->hasMany("App\Models\ShopServices", "id_shop");
    }

    // one to many from shop to room
    public function room()
    {
        return $this->hasMany("App\Models\Room", "id_shop");
    }

    // one to one from shop to category
    public function category()
    {
        return $this->belongsTo("App\Models\Category", "id_category", "id");
    }
}
