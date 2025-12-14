<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    // Beritahu Laravel nama tabelnya (jika tidak standar)
    protected $table = 'payment_methods';

    // Izinkan kolom-kolom ini diisi
    protected $fillable = [
        'nama_bank',
        'jenis',
        'nomor_rekening',
        'atas_nama',
        'gambar',
        'is_active'
    ];
}