<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'nama',
        'deskripsi',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(ProdukDetail::class, 'id_produk', 'id_produk');
    }

    //testing default detail,foto,harga
    public function detailDefault()
    {
        return $this->hasOne(ProdukDetail::class, 'id_produk', 'id_produk')->oldest();
    }

    public function getFotoUrlAttribute()
    {
        $detail = $this->detail->first();

        if ($detail && $detail->foto) {
            if (str_starts_with($detail->foto, 'produk/')) {
                return asset($detail->foto);
            }

            return asset('produk/' . $detail->foto);
        }

        return asset('images/icon_toska.png');
    }


    public function getHargaAttribute()
    {
        return $this->detail->first()->harga_jual ?? 0;
    }
}
