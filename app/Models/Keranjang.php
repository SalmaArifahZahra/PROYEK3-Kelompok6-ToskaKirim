<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';
    protected $primaryKey = 'id_produk_detail';
    public $incrementing = false;
    protected $keyType = 'int';

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

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public static function totalCartCount()
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return 0;
        }
        return self::where('id_user', \Illuminate\Support\Facades\Auth::id())
            ->sum('quantity');
    }
}
