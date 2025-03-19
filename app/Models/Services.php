<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    public $table = "services";

    protected $fillable = [
        "name",
        "price",
        "image_url",
    ];

    // Get all shop_services relationships
    public function shop_services()
    {
        return $this->hasMany(ShopServices::class, 'id_services', 'id');
    }

    // Get shops through shop_services
    public function shops()
    {
        return $this->hasManyThrough(
            Shop::class,
            ShopServices::class,
            'id_services', // Foreign key on shop_services table
            'id', // Foreign key on shops table
            'id', // Local key on services table
            'id_shop' // Local key on shop_services table
        );
    }
}
