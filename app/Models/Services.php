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

    // one to one from services to shop services
    public function shop_services()
    {
        return $this->belongsTo("App\Models\ShopServices", "id", "id_services");
    }
}
