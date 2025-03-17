<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $table = "room";

    protected $dates = [
        "created_at",
        "deleted_at",
        "updated_at",
    ];

    protected $fillable = [
        "id_shop",
        "id_customer",
    ];

    // many to one from room to customer
    public function customer()
    {
        return $this->belongsTo("App\Models\Customer", "id_customer", "id");
    }

    // one to many from room to content
    public function content()
    {
        return $this->hasMany("App\Models\Content", "id_room");
    }

    // many to one from room to shop
    public function shop()
    {
        return $this->belongsTo("App\Models\Shop", "id_shop", "id");
    }
}
