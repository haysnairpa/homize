<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesiredShop extends Model
{
    public $table = "desired_shop";

    protected $fillable = [
        "id_customer",
        "id_shop",
    ];

    // many to one from desired shop to customer
    public function customer()
    {
        return $this->belongsTo("App\Models\Customer", "id_customer", "id");
    }

    // many to one from desired shop to shop
    public function shop()
    {
        return $this->hasMany("App\Models\Shop", "id");
    }
}
