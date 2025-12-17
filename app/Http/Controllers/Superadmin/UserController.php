<?php

namespace App\Http\Controllers\Superadmin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MetodePembayaran;
use App\Models\LayananPengiriman;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pengaturan;
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
    // Menampilkan Dashboard Superadmin (Sesuai Figma)
    public function dashboard()
    {
        // Data statistik
        $totalAdmin = User::where('peran', 'admin')->count();
        $totalPayment = MetodePembayaran::count();

        // Checklist data penting untuk operasional toko
        $checklist = [
            [
                'id' => 'metode_pembayaran',
                'title' => 'Metode Pembayaran',
                'description' => 'Atur metode pembayaran (COD, Transfer Bank, E-Wallet)',
                'count' => MetodePembayaran::count(),
                'required' => true,
                'url' => route('superadmin.payments.index'),
                'icon' => 'fa-credit-card'
            ],
            [
                'id' => 'layanan_pengiriman',
                'title' => 'Layanan Pengiriman',
                'description' => 'Atur layanan pengiriman dan tarif per km',
                'count' => LayananPengiriman::count(),
                'required' => true,
                'url' => route('superadmin.layanan.index'),
                'icon' => 'fa-truck'
            ],
            [
                'id' => 'kategori_produk',
                'title' => 'Kategori Produk',
                'description' => 'Buat kategori produk utama',
                'count' => Kategori::whereNull('parent_id')->count(),
                'required' => true,
                'url' => route('admin.kategori.index'),
                'icon' => 'fa-folder'
            ],
            [
                'id' => 'produk',
                'title' => 'Produk',
                'description' => 'Tambahkan produk dan varian ke katalog',
                'count' => Produk::count(),
                'required' => false,
                'url' => route('admin.produk.selectKategori'),
                'icon' => 'fa-box'
            ],
            [
                'id' => 'pengaturan_toko',
                'title' => 'Pengaturan Toko',
                'description' => 'Atur informasi dan kebijakan toko',
                'count' => Pengaturan::count(),
                'required' => false,
                'url' => route('superadmin.kontrol_toko.index'),
                'icon' => 'fa-cog'
            ]
        ];

        // Hitung status checklist
        $completedCount = 0;
        foreach ($checklist as $item) {
            if ($item['count'] > 0) {
                $completedCount++;
            }
        }
        $totalRequired = count(array_filter($checklist, fn($item) => $item['required']));

        return view('superadmin.dashboard', compact('totalAdmin', 'totalPayment', 'checklist', 'completedCount', 'totalRequired'));
    }

    // Menampilkan Daftar Admin (Sesuai Figma "Daftar Admin")
    public function index()
    {
        // Hanya ambil user yang role-nya 'admin'
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
            'peran' => 'required|in:admin', // Superadmin cuma bikin Admin
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
        // Validasi
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

    // Menghapus user.
    public function destroy(User $user): RedirectResponse
    {
        // Safety check: Superadmin tidak boleh menghapus dirinya sendiri
        if ($user->id_user === Auth::id()) {
             return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();
        
        return redirect()->route('superadmin.users.dashboard')
                         ->with('success', 'User berhasil dihapus.');
    }
}