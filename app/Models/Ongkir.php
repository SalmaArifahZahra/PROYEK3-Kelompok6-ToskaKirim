<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ongkir extends Model
{
    use HasFactory;

    protected $table = 'ongkir';
    protected $primaryKey = 'id_ongkir';

    protected $fillable = [
        'jarak',
        'jarak_before',
        'tarif_per_km',
        'total_ongkir',
        'promo_name',
    ];

    protected $casts = [
        'jarak' => 'float',
        'jarak_before' => 'float',
        'tarif_per_km' => 'float',
        'total_ongkir' => 'float',
    ];

    public function pesanan(): HasOne
    {
        return $this->hasOne(Pesanan::class, 'id_ongkir', 'id_ongkir');
    }
}