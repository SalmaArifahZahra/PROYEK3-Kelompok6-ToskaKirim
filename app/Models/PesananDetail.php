<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananDetail extends Model
{
    use HasFactory;

    protected $table = 'pesanan_detail';
    
    //composite key
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'id_pesanan',
        'id_produk_detail',
        'kuantitas',
        'harga_saat_beli',
        'subtotal_item',
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'harga_saat_beli' => 'float',
        'subtotal_item' => 'float',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function produkDetail(): BelongsTo
    {
        return $this->belongsTo(ProdukDetail::class, 'id_produk_detail', 'id_produk_detail');
    }
}