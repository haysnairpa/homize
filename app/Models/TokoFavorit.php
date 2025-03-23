<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokoFavorit extends Model
{

    public $table = "toko_favorit";

    protected $fillable = [
        'id_user',
        'id_merchant'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'id_merchant', 'id');
    }
}
