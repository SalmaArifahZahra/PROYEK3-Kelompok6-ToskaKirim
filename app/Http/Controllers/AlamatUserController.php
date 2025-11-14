<?php

namespace App\Http\Controllers;

use App\Models\AlamatUser;
use App\Http\Requests\StoreAlamatUserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AlamatUserController extends Controller
{
    /**
     * Menampilkan daftar alamat milik user.
     */
    public function index(): View
    {
        $alamatList = Auth::user()->alamatUser()
                        ->orderBy('is_utama', 'desc')
                        ->get();

        return view('customer.alamat.index', [
            'alamatList' => $alamatList
        ]);
    }

    /**
     * Menampilkan form untuk melengkapi profil (membuat alamat pertama).
     */
    public function create(): View
    {
        return view('customer.profile.complete_profile');
    }

    /**
     * Menyimpan alamat baru.
     * Perhatikan 'StoreAlamatUserRequest' menggantikan 'Request'.
     */
    public function store(StoreAlamatUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();

        // Cek apakah user sudah punya alamat sebelumnya
        $userHasAddress = $user->alamatUser()->count() > 0;

        $data['id_user'] = $user->id_user;
        
        // Jika user belum punya alamat, set alamat pertama ini sebagai utama
        if (!$userHasAddress) {
            $data['is_utama'] = true;
        } elseif ($request->input('is_utama')) {
            // Atau jika user explicitly set alamat ini sebagai utama
            $data['is_utama'] = true;
        }
        
        $alamat = AlamatUser::create($data);

        // Jika ini alamat utama, unset alamat utama lainnya
        if ($data['is_utama']) {
            $alamat->setAsUtama();
        }

        return redirect()->route('customer.dashboard')
                         ->with('success', 'Selamat datang! Alamat Anda berhasil disimpan.');
    }

    /**
     * Menampilkan form untuk mengedit alamat.
     */
    public function edit(AlamatUser $alamat): View
    {
        $this->authorize('update', $alamat); 

        return view('customer.alamat.edit', [
            'alamat' => $alamat
        ]);
    }

    /**
     * Memproses update alamat.
     * Perhatikan 'StoreAlamatUserRequest' dipakai lagi (validasi sama).
     */
    public function update(StoreAlamatUserRequest $request, AlamatUser $alamat): RedirectResponse
    {
        $this->authorize('update', $alamat);

        $alamat->update($request->validated());

        return redirect()->route('customer.alamat.index')
                         ->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Menghapus alamat.
     */
    public function destroy(AlamatUser $alamat): RedirectResponse
    {
        $this->authorize('delete', $alamat);

        if ($alamat->is_utama) {
            $alamatLain = Auth::user()->alamatUser()->where('id_alamat', '!=', $alamat->id_alamat)->first();
            if ($alamatLain) {
                $alamatLain->setAsUtama();
            }
        }

        $alamat->delete();

        return redirect()->route('customer.alamat.index')
                         ->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Meng-set satu alamat sebagai alamat utama.
     */
    public function setUtama(AlamatUser $alamat): RedirectResponse
    {
        $this->authorize('setUtama', $alamat);

        $alamat->setAsUtama();

        return redirect()->route('customer.alamat.index')
                         ->with('success', 'Alamat utama berhasil diubah.');
    }
}