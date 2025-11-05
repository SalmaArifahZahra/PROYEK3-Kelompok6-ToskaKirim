<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentModel extends Model
{
use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'id_payments';

    protected $fillable = [
        'id_order',
        'payments_date',
        'bukti_tf',
    ];

    /**
* Casting tipe data untuk atribut.
*/
    protected $casts = [
        'payments_date' => 'datetime',
    ];

    /**
     * Relasi one-to-one (inverse): Satu Payment milik satu Order.
     */
    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'id_order', 'id_order');
    }
}
