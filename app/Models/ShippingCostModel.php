<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingCostModel extends Model
{
    use HasFactory;

    protected $table = 'shipping_costs';
    protected $primaryKey = 'id_shipping_costs';

    protected $fillable = [
        'jarak',
        'tarif',
        'shipping_costs',
    ];

    /**
     * Casting tipe data untuk atribut.
     */
    protected $casts = [
        'jarak' => 'decimal:2',
        'tarif' => 'decimal:2',
        'shipping_costs' => 'decimal:2',
    ];

    /**
     * Relasi one-to-many: Satu ShippingCost bisa dipakai di banyak Order.
     */
    public function orders()
    {
        return $this->hasMany(OrderModel::class, 'id_shipping_costs', 'id_shipping_costs');
    }
}