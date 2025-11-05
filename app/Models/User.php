<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Primary key untuk model ini.
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'address',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Relasi one-to-many: Satu User bisa memiliki banyak Order.
     */
    public function orders()
    {
        // Parameter kedua ('id_user') adalah foreign key di tabel 'orders'
        return $this->hasMany(OrderModel::class, 'id_user', 'id_user');
    }

    /**
     * Relasi one-to-many: Satu User bisa memiliki banyak item di Cart.
     */
    public function carts()
    {
        // Parameter kedua ('id_user') adalah foreign key di tabel 'carts'
        return $this->hasMany(CartsModel::class, 'id_user', 'id_user');
    }
}