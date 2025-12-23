<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'nama_bank',
        'jenis',
        'nomor_rekening',
        'atas_nama',
        'gambar',
        'is_active'
    ];
}