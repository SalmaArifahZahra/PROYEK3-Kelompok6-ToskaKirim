<?php

namespace App\Models;

use App\Enums\StatusPembayaranEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pesanan',
        'bukti_bayar',
        'jumlah_bayar',
        'tanggal_bayar',

        'status_pembayaran',
        'catatan_admin',
    ];

    protected $casts = [
        'jumlah_bayar' => 'float',
        'tanggal_bayar' => 'date',
        'status_pembayaran' => StatusPembayaranEnum::class,
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}