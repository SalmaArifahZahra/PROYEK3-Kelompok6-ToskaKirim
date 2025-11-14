<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'peran',
    ];

    protected $casts = [
        'peran' => RoleEnum::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function alamatUser(): HasMany
    {
        return $this->hasMany(AlamatUser::class, 'id_user', 'id_user');
    }

    public function keranjang(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'id_user', 'id_user');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }

    public function setAsUtama(): void
    {
        $user = $this->user;
        $user->alamatUser()->update(['is_utama' => false]);
        $this->update(['is_utama' => true]);
    }
}