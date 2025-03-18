<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table = "category";

    protected $fillable = [
        "name",
        "series_number",
        "id_category",
    ];

    // one to many from category to category form
    public function category_form()
    {
        return $this->hasMany("App\Models\CategoryForm", "id_category");
    }

    // one to one from category to shop
    public function shop()
    {
        return $this->belongsTo("App\Models\Shop", "id", "id_category");
    }

    // one to one from category to jasa category
    public function jasa_category()
    {
        return $this->belongsTo("App\Models\JasaCategory", "id_category", "id");
    }
}
