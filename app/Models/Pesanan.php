<?php

namespace App\Models;

use App\Enums\StatusPesananEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_user',
        'id_ongkir',
        'waktu_pesanan',
        'subtotal_produk',
        'grand_total',
        'status_pesanan',
        'penerima_nama',
        'penerima_telepon',
        'alamat_lengkap',
    ];

    protected $casts = [
        'waktu_pesanan' => 'datetime',
        'subtotal_produk' => 'float',
        'grand_total' => 'float',
        'status_pesanan' => StatusPesananEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function ongkir(): BelongsTo
    {
        return $this->belongsTo(Ongkir::class, 'id_ongkir', 'id_ongkir');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PesananDetail::class, 'id_pesanan', 'id_pesanan');
    }

    // Relasi untuk langsung dapat produk dari pesanan
    public function produkDetails(): BelongsToMany
    {
        return $this->belongsToMany(
            ProdukDetail::class,
            'pesanan_detail',      // Nama tabel pivot
            'id_pesanan',          // Foreign key di pivot untuk Model ini
            'id_produk_detail'     // Foreign key di pivot untuk Model yang di-join
        )->withPivot('kuantitas', 'harga_beli', 'subtotal_item');
    }
}