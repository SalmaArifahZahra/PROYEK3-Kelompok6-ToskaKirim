<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoOngkir extends Model
{
    use HasFactory;

    protected $table = 'promo_ongkir';
    
    protected $fillable = [
        'nama_promo', 'deskripsi', 
        'min_belanja', 'nilai_potongan', 
        'mekanisme', 'maksimum_potongan',
        'tanggal_mulai', 'tanggal_selesai', 'is_active'
    ];

    protected $casts = [
        'min_belanja' => 'decimal:2',
        'nilai_potongan' => 'decimal:2',
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // --- OTAK PENGHITUNG PROMO (Logic Smart) ---
    public function hitungBenefit($totalBelanja)
    {
        // 1. Cek Syarat Dasar
        if ($totalBelanja < $this->min_belanja) {
            return 0;
        }

        $benefit = 0;

        // 2. Cek Mekanisme
        if ($this->mekanisme == 'kelipatan') {
            // Logic: Floor(Total / Syarat) * Nilai
            // Contoh: 250rb / 125rb = 2. Benefit = 2 * 3km = 6km
            $faktor = floor($totalBelanja / $this->min_belanja);
            $benefit = $faktor * $this->nilai_potongan;
        } else {
            // Logic Flat: Langsung kasih nilai
            $benefit = $this->nilai_potongan;
        }

        // 3. Cek Batas Maksimal (Safety)
        if ($this->maksimum_potongan && $benefit > $this->maksimum_potongan) {
            return $this->maksimum_potongan;
        }

        return $benefit;
    }
}