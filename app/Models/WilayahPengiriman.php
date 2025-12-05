<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WilayahPengiriman extends Model
{
    protected $table = 'wilayah_pengiriman';
    protected $fillable = ['kota_kabupaten', 'kecamatan', 'kelurahan', 'jarak_km'];
}