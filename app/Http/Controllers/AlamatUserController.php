<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AlamatUser;
use App\Http\Requests\StoreAlamatUserRequest;
use Illuminate\Support\Facades\Auth;

class AlamatUserController extends Controller
{
    // Simpan alamat baru
    public function store(StoreAlamatUserRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 401);
        }
        
        $data['id_user'] = $user->id_user;
        $countAlamat = AlamatUser::where('id_user', $data['id_user'])->count();

        // alamat pertama otomatis utama
        if ($countAlamat == 0) {
            $data['is_utama'] = true;
        } else {
            $data['is_utama'] = $request->boolean('is_utama');
        }

        // Jika diset sebagai utama, reset alamat lain
        if ($data['is_utama']) {
            AlamatUser::where('id_user', $data['id_user'])->update(['is_utama' => false]);
        }

        AlamatUser::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil disimpan.'
        ]);
    }

    // Ambil semua alamat user yang sedang login
    public function getAllUserAddresses()
    {
        $alamats = Auth::user()->alamatUser()
            ->orderBy('is_utama', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $alamats
        ]);
    }

    // Ambil detail alamat tertentu (JSON)
    public function showApi($alamat)
    {
        $alamat = AlamatUser::where('id_alamat', $alamat)->first();

        if (!$alamat) {
            return response()->json(['success' => false, 'message' => 'Alamat tidak ditemukan'], 404);
        }

        if ($alamat->id_user !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $alamat
        ]);
    }

    // Compatibility wrapper for route name getAddressDetail
    public function getAddressDetail($alamat)
    {
        return $this->showApi($alamat);
    }

    // Update alamat
    public function updateAddress(StoreAlamatUserRequest $request, AlamatUser $alamat)
    {
        if ($alamat->id_user !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();

        if ($request->boolean('is_utama')) {
             AlamatUser::where('id_user', Auth::id())->update(['is_utama' => false]);
             $data['is_utama'] = true;
        } else {
            if ($alamat->is_utama) {
                $data['is_utama'] = true; 
            }
        }

        $alamat->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diperbarui',
            'data' => $alamat
        ]);
    }

    // Hapus alamat
    public function deleteAddress(AlamatUser $alamat)
    {
        if ($alamat->id_user !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $jumlahAlamat = AlamatUser::where('id_user', Auth::id())->count();

        if ($jumlahAlamat <= 1) {
            // Gagal hapus karena ini satu-satunya alamat
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus. Anda wajib memiliki minimal satu alamat pengiriman.'
            ]);
        }

        if ($alamat->is_utama) {
            $alamatLain = AlamatUser::where('id_user', Auth::id())
                ->where('id_alamat', '!=', $alamat->id_alamat)
                ->orderBy('created_at', 'desc')
                ->first();
                
            if ($alamatLain) {
                $alamatLain->update(['is_utama' => true]);
            }
        }

        $alamat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus'
        ]);
    }

    // Set alamat sebagai utama
    public function setUtamaApi(AlamatUser $alamat)
    {
        if ($alamat->id_user !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($alamat->is_utama) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat ini sudah dipilih sebagai alamat utama.'
            ]);
        }

        AlamatUser::where('id_user', Auth::id())->update(['is_utama' => false]);

        $alamat->update(['is_utama' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat utama berhasil diubah',
            'data' => $alamat
        ]);
    }
}