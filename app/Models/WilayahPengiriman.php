<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WilayahPengiriman extends Model
{
    use HasFactory;

    protected $table = 'wilayah_pengiriman';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['kota_kabupaten', 'kecamatan', 'kelurahan', 'jarak_km'];

    protected $casts = [
        'jarak_km' => 'float',
    ];
}