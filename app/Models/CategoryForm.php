<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryForm extends Model
{
    public $table = "category_form";

    protected $fillable = [
        "id_category",
        "form_name",
        "form_input",
    ];

    // many to one from category form to category
    public function category()
    {
        return $this->belongsTo("App\Models\Category", "id_customer", "id");
    }
}
