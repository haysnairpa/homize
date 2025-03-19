<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JasaCategory extends Model
{
    public $table = "jasa_category";

    protected $fillable = [
        "name",
    ];

    // one to one from jasa category to category
    public function category()
    {
        return $this->belongsTo("App\Models\Category", "id", "id_category");
    }

    public function categories()
    {
        return $this->hasMany("App\Models\Category", "id_category", "id");
    }
}
