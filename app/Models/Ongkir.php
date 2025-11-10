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
        'tarif_per_km',
        'total_ongkir',
    ];

    protected $casts = [
        'jarak' => 'float',
        'tarif_per_km' => 'float',
        'total_ongkir' => 'float',
    ];

    public function pesanan(): HasOne
    {
        return $this->hasOne(Pesanan::class, 'id_ongkir', 'id_ongkir');
    }
}