<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    public $table = "sub_kategori";

    protected $fillable = [
        "id_kategori",
        "nama",
        "seri_sub_kategori",
    ];

    // one to one from sub_kategori to merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, "id_sub_kategori");
    }

    // one to one from sub_kategori to layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, "id", "id_sub_kategori");
    }

    // many to one from sub_kategori to kategori
    public function sub_kategori()
    {
        return $this->belongsTo(Kategori::class, "id_kategori", "id");
    }
}
