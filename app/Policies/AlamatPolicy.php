<?php

namespace App\Policies;

use App\Models\AlamatUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AlamatPolicy
{
    /**
     * Tentukan apakah user boleh mengelola alamat ini (update, delete, set-utama).
     * Kita akan gabungkan semua cek di sini.
     */
    private function canManage(User $user, AlamatUser $alamat): bool
    {
        return $user->id_user === $alamat->id_user;
    }

    /**
     * Tentukan apakah user boleh melihat daftar alamat (tidak dipakai, tapi standar).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Tentukan apakah user boleh mengedit alamat.
     */
    public function update(User $user, AlamatUser $alamat): bool
    {
        return $this->canManage($user, $alamat);
    }

    /**
     * Tentukan apakah user boleh menghapus alamat.
     */
    public function delete(User $user, AlamatUser $alamat): bool
    {
        return $this->canManage($user, $alamat);
    }
    
    /**
     * Tentukan apakah user boleh set-utama alamat.
     * (Kita tambahkan method kustom 'setUtama')
     */
    public function setUtama(User $user, AlamatUser $alamat): bool
    {
        return $this->canManage($user, $alamat);
    }
}