<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'parent_id',
        'nama_kategori',
        'foto',
    ];

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'id_kategori', 'id_kategori');
    }

    public function parent()
    {
        return $this->belongsTo(Kategori::class, 'parent_id', 'id_kategori');
    }

    public function children()
    {
        return $this->hasMany(Kategori::class, 'parent_id', 'id_kategori');
    }
}