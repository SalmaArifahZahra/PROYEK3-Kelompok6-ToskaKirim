<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukDetail extends Model
{
    use HasFactory;

    protected $table = 'produk_detail';
    protected $primaryKey = 'id_produk_detail';

    protected $fillable = [
        'id_produk',
        'nama_varian',
        'foto',
        'harga_modal',
        'harga_jual',
        'stok',
    ];

    protected $casts = [
        'harga_modal' => 'float',
        'harga_jual' => 'float',
        'stok' => 'integer',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}