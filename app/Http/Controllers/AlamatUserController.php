<?php

namespace App\Http\Controllers;

use App\Models\AlamatUser;
use App\Models\User;
use App\Http\Requests\StoreAlamatUserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AlamatUserController extends Controller
{
    // Menampilkan daftar alamat user
    public function index(): View
    {
        $alamatList = Auth::user()->alamatUser()
                        ->orderBy('is_utama', 'desc')
                        ->get();

        return view('customer.alamat.index', [
            'alamatList' => $alamatList
        ]);
    }

    // Menampilkan form untuk menambahkan alamat baru
    // Bisa diakses tanpa auth jika sedang registrasi
    public function create(): View
    {
        return view('customer.profile.complete_profile');
    }

    // Memproses penyimpanan alamat baru
    public function store(StoreAlamatUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Cek registrasi baru
        $isNewRegistration = session()->has('temp_user_id');

        if ($isNewRegistration) {
            // Untuk user baru, ambil user ID dari session
            $userId = session('temp_user_id');
            $user = User::find($userId);

            if (!$user) {
                return redirect()->route('register')
                                 ->withErrors(['error' => 'Sesi registrasi tidak valid. Silakan daftar ulang.']);
            }

            $data['id_user'] = $userId;
        } else {
            // Untuk user yang sudah login
            $user = $request->user();
            if (!$user) {
                return redirect()->route('login')
                                 ->withErrors(['error' => 'Anda harus login terlebih dahulu.']);
            }
            $data['id_user'] = $user->id_user;
        }

        $userHasAddress = $user->alamatUser()->count() > 0;
        
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

        if ($isNewRegistration) {
            session()->forget('temp_user_id');
            return redirect()->route('login')
                             ->with('success', 'Registrasi dan profil berhasil disimpan! Silakan login dengan email dan password yang telah Anda buat.');
        }

        return redirect()->route('customer.dashboard')
                         ->with('success', 'Alamat berhasil disimpan!');
    }

    // Menampilkan form untuk mengedit alamat
    public function edit(AlamatUser $alamat): View
    {
        $this->authorize('update', $alamat); 

        return view('customer.alamat.edit', [
            'alamat' => $alamat
        ]);
    }

    // Memperbarui data alamat
    public function update(StoreAlamatUserRequest $request, AlamatUser $alamat): RedirectResponse
    {
        $this->authorize('update', $alamat);

        $alamat->update($request->validated());

        return redirect()->route('customer.alamat.index')
                         ->with('success', 'Alamat berhasil diperbarui.');
    }

    // Menghapus alamat
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

    // Menetapkan alamat sebagai alamat utama
    public function setUtama(AlamatUser $alamat): RedirectResponse
    {
        $this->authorize('setUtama', $alamat);

        $alamat->setAsUtama();

        return redirect()->route('customer.alamat.index')
                         ->with('success', 'Alamat utama berhasil diubah.');
    }
}