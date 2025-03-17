<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = "order";

    protected $dates = [
        "created_at",
        "deleted_at",
        "updated_at",
    ];

    protected $fillable = [
        "id_category",
        "id_customer",
        "customer_address",
        "id_services",
    ];

    // one to one from order to category
    public function category()
    {
        return $this->belongsTo("App\Models\Category", "id_category", "id");
    }

    // many to one from order to customer
    public function customer()
    {
        return $this->belongsTo("App\Models\Customer", "id_customer", "id");
    }

    // one to one from order to shop services
    public function shop_services()
    {
        return $this->belongsTo("App\Models\ShopServices", "id_services", "id");
    }

    // one to one from order to status
    public function status()
    {
        return $this->belongsTo("App\Models\Status", "id_status", "id");
    }
}
