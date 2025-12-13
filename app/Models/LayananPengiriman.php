<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LayananPengiriman extends Model
{
    use HasFactory;

    protected $table = 'layanan_pengiriman';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nama_layanan', 'tarif_per_km', 'is_active'];

    protected $casts = [
        'tarif_per_km' => 'integer',
        'is_active' => 'boolean',
    ];

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_layanan_pengiriman', 'id');
    }
}