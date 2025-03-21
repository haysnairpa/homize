<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    public $table = "aset";

    protected $fillable = [
        "id_layanan",
        "deskripsi",
        "media_url",
    ];

    // one to one from aset to layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, "id_layanan", "id");
    }
}
