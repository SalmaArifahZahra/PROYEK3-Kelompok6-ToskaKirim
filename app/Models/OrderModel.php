<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderModel extends Model
{
use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'id_user',
        'id_shipping_costs',
        'order_date',
        'total_price',
        'status_order',
    ];

    /**
     * Casting tipe data untuk atribut.
     */
    protected $casts = [
        'total_price' => 'decimal:2',
        'order_date' => 'datetime',
    ];

    /**
     * Relasi many-to-one: Satu Order dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi many-to-one: Satu Order memiliki satu ShippingCost.
     */
    public function shippingCost()
    {
        return $this->belongsTo(ShippingCostModel::class, 'id_shipping_costs', 'id_shipping_costs');
    }

    /**
     * Relasi one-to-one: Satu Order memiliki satu Payment.
     */
    public function payment()
    {
        return $this->hasOne(PaymentModel::class, 'id_order', 'id_order');
    }

    /**
     * Relasi many-to-many: Satu Order bisa memiliki banyak Product.
     */
    public function products()
    {
        return $this->belongsToMany(
            ProductModel::class,
            'orders_item',
            'id_order',
            'id_products'
        )->withPivot('quantity');
    }
}
