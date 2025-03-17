<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public $table = "admin";

    protected $fillable = [
        "email",
        "password",
    ];
}
