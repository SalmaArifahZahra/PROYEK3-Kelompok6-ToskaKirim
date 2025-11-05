<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItemModel extends Model
{
    use HasFactory;

    protected $table = 'orders_item';

    protected $fillable = [
        'id_order',
        'id_products',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'id_order');
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'id_products');
    }
}
