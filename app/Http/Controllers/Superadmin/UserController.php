<?php

namespace App\Http\Controllers\Superadmin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WilayahPengiriman;
use App\Models\Pengaturan;
use App\Models\Pesanan;
use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function dashboard()
    {
        // 1. STATISTIK USER
        $totalAdmin = User::where('peran', 'admin')->count();
        $totalCustomer = User::where('peran', 'customer')->count();
        
        // 2. TOTAL PENDAPATAN 
        $totalPendapatan = 0;

        if (Schema::hasTable('pesanan')) {
            try {
                $totalPendapatan = Pesanan::where('status_pesanan', 'selesai')
                                          ->sum('grand_total'); 
            } catch (\Exception $e) {
                $totalPendapatan = 0;
            }
        }

        // 3. STATISTIK LOGISTIK 
        $totalWilayah = WilayahPengiriman::count();
        $wilayahTerisi = WilayahPengiriman::where('jarak_km', '>', 0)->count();
        
        $persenLogistik = $totalWilayah > 0 ? round(($wilayahTerisi / $totalWilayah) * 100, 1) : 0;

        // 4. DATA LAINNYA
        $latestAdmins = User::where('peran', 'admin')->latest()->take(5)->get();

        $cekWA = Pengaturan::where('key', 'nomor_wa')->exists();
        $cekAlamat = Pengaturan::where('key', 'alamat_toko')->exists();
        $cekPayment = MetodePembayaran::count() > 0;
        
        $tokoSiap = $cekWA && $cekAlamat && $cekPayment;

        return view('superadmin.dashboard', compact(
            'totalAdmin', 
            'totalCustomer', 
            'totalPendapatan', 
            'persenLogistik', 
            'wilayahTerisi', 
            'totalWilayah',
            'latestAdmins', 
            'tokoSiap'
        ));
    }

    // Menampilkan Daftar Admin 
    public function index()
    {
        $admins = User::where('peran', 'admin')->orderBy('nama')->get(); 
        return view('superadmin.users.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'peran' => 'required|in:admin', 
        ]);

        User::create([
            'nama' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => $request->peran,
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'Admin berhasil ditambahkan');
    }


    // Menampilkan form untuk mengedit user.
    public function edit(User $user): View
    {
        return view('superadmin.users.edit', [
            'user' => $user
        ]);
    }

    // Memperbarui data user.
    public function update(Request $request, User $user): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id_user, 'id_user')
            ],
            'peran' => ['required', Rule::in(RoleEnum::ADMIN, RoleEnum::CUSTOMER)],
            'password' => ['nullable', 'sometimes', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.dashboard')
                         ->with('success', 'Data user berhasil diperbarui.');
    }

    // Menghapus user
    public function destroy(User $user): RedirectResponse
    {
        // Safety check
        if ($user->id_user === Auth::id()) {
             return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();
        
        return redirect()->route('superadmin.users.dashboard')
                         ->with('success', 'User berhasil dihapus.');
    }
}