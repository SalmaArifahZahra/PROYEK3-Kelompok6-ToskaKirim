<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';

    //composite key
    public $incrementing = false;
    protected $primaryKey = null;
    protected $keyType = 'string';

    protected $fillable = [
        'id_user',
        'id_produk_detail',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function produkDetail(): BelongsTo
    {
        return $this->belongsTo(ProdukDetail::class, 'id_produk_detail', 'id_produk_detail');
    }
}
