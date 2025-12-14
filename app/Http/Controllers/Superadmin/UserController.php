<?php

namespace App\Http\Controllers\Superadmin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
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
        // Hitung data untuk ringkasan (opsional, biar dashboard gak kosong)
        $totalAdmin = User::where('peran', 'admin')->count();
        $totalPayment = 0;
        return view('superadmin.dashboard', compact('totalAdmin', 'totalPayment'));
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