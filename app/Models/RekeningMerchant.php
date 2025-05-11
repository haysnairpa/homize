<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningMerchant extends Model
{
    use HasFactory;

    protected $table = 'rekening_merchant';

    protected $fillable = [
        'id_merchant',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'id_merchant');
    }

    // Satu rekening merchant bisa punya banyak penarikan
    public function penarikan()
    {
        return $this->hasMany(Penarikan::class, 'rekening_merchant_id');
    }
}
