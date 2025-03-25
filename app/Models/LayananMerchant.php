<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LayananMerchant extends Model
{
    public $table = "layanan_merchant";

    protected $fillable = [
        "id_layanan",
        "id_merchant",
    ];

    // one to one from layanan_merchant to layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, "id_layanan", "id");
    }

    // many to one from layanan_merchant to merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, "id_merchant", "id");
    }
}
