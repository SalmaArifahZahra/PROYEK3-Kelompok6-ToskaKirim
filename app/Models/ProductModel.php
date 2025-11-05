<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id_products';

    protected $fillable = [
        'id_categories',
        'name',
        'deskripsi',
        'image',
        'jenis',
        'tipe',
        'price',
        'stock',
    ];

    /**
     * Casting tipe data untuk atribut.
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Relasi many-to-one: Satu Product milik satu Category.
     */
    public function category()
    {
        // Parameter kedua ('id_categories') adalah foreign key di tabel 'products'
        return $this->belongsTo(CategoryModel::class, 'id_categories', 'id_categories');
    }

    /**
     * Relasi one-to-many: Satu User bisa memiliki banyak Order.
     */
    public function orders()
    {
        return $this->hasMany(OrderModel::class, 'id_user', 'id_user');
    }

    /**
     * Relasi one-to-many: Satu User bisa memiliki banyak item di Cart.
     */
    public function carts()
    {
        return $this->hasMany(CartsModel::class, 'id_user', 'id_user');
    }
}
