<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlamatUser extends Model
{
    use HasFactory;

    protected $table = 'alamat_user';
    protected $primaryKey = 'id_alamat';

    protected $fillable = [
        'id_user',
        'label_alamat',
        'nama_penerima',
        'telepon_penerima',
        'kota_kabupaten',
        'kecamatan',
        'kelurahan',
        'rt',
        'rw',
        'no_rumah',
        'jalan_patokan',
        'is_utama',
    ];

    protected $casts = [
        'is_utama' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}