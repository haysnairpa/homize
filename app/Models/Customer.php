<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    public $table = "customer";

    protected $dates = [
        "created_at",
        "deleted_at",
        "updated_at",
    ];

    protected $fillable = [
        "name",
        "email",
    ];

    // one to many from customer to desired shop
    public function desired_shop()
    {
        return $this->hasMany("App\Models\DesiredShop", "id_customer");
    }

    // one to many from customer to order
    public function order()
    {
        return $this->hasMany("App\Models\Order", "id_customer");
    }
}
