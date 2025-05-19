<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSaldoMerchant extends Model
{
    use HasFactory;
    
    protected $table = 'riwayat_saldo_merchant';
    
    protected $fillable = [
        'id_merchant',
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'tipe',
        'keterangan',
    ];
    
    /**
     * Get the merchant that owns the transaction.
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'id_merchant');
    }
}
