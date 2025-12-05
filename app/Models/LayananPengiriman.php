<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LayananPengiriman extends Model
{
    protected $table = 'layanan_pengiriman';
    protected $fillable = ['nama_layanan', 'tarif_per_km', 'is_active'];
}