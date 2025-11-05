<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CartsModel extends Model
{
use HasFactory;

    protected $table = 'carts';
    protected $primaryKey = 'id_carts';

    protected $fillable = [
        'id_user',
        'id_products',
        'quantity',
    ];

    /**
     * Relasi many-to-one: Satu item Cart dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi many-to-one: Satu item Cart merujuk ke satu Product.
     */
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'id_products', 'id_products');
    }
}
