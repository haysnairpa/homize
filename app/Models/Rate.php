<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public $table = "rate";

    protected $fillable = [
        "id_customer",
        "id_shop",
        "rate",
        "message",
        "media_url"
    ];

    // many to one from rate to customer
    public function customer()
    {
        return $this->belongsTo("App\Models\Customer", "id_customer", "id");
    }

    // many to one from rate to shop
    public function shop()
    {
        return $this->belongsTo("App\Models\Shop", "id_shop", "id");
    }
}
