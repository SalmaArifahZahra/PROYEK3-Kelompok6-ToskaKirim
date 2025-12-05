<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PromoOngkir extends Model
{
    protected $table = 'promo_ongkir';
    protected $fillable = ['nama_promo', 'min_belanja', 'potongan_jarak', 'is_active'];
}