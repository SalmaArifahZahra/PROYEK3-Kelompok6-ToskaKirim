<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';
    protected $primaryKey = 'id_pengaturan';

    //tabel ini tidak punya created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'tarif_per_km',
        'foto_qris',
    ];

    protected $casts = [
        'tarif_per_km' => 'float',
    ];
}