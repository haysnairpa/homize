<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    public $table = 'kategori';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
    ];

    // one to many from kategori to sub_kategori
    public function sub_kategori()
    {
        return $this->hasMany(SubKategori::class, "id_kategori");
    }
}
