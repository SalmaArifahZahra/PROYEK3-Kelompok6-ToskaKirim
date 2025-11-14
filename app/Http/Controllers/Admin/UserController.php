<?php

namespace App\Http\Controllers\Admin;

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
    /**
     * Menampilkan dashboard utama Superadmin.
     * Sesuai rute: GET /superadmin/dashboard
     */
    public function dashboard(): View
    {
        return view('superadmin.dashboard');
    }

    /**
     * Menampilkan daftar semua user.
     * Sesuai rute: GET /superadmin/users
     */
    public function index(): View
    {
        $users = User::orderBy('nama')->get();
        
        // Asumsi view ada di: resources/views/superadmin/users/dashboard.blade.php
        return view('superadmin.users.dashboard', [
            'users' => $users
        ]);
    }

    /**
     * Menampilkan form untuk membuat user baru.
     * Sesuai rute: GET /superadmin/users/create
     */
    public function create(): View
    {
        // Asumsi view ada di: resources/views/superadmin/users/create.blade.php
        return view('superadmin.users.create');
    }

    /**
     * Menyimpan user baru (Admin/Customer) ke database.
     * Sesuai rute: POST /superadmin/users
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)],
            'peran' => ['required', Rule::in(RoleEnum::ADMIN, RoleEnum::CUSTOMER)],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        // Redirect ke halaman daftar user dengan pesan sukses
        return redirect()->route('superadmin.users.dashboard')
                         ->with('success', 'User baru berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit user.
     * Sesuai rute: GET /superadmin/users/{user}/edit
     */
    public function edit(User $user): View // Route Model Binding
    {
        // Asumsi view ada di: resources/views/superadmin/users/edit.blade.php
        return view('superadmin.users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Memproses update user.
     * Sesuai rute: PUT /superadmin/users/{user}
     */
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
            'password' => ['nullable', 'sometimes', Password::min(8)], // Password opsional
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Hanya update password jika diisi
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Hapus dari data agar tidak menimpa password lama
        }

        $user->update($data);

        return redirect()->route('superadmin.users.dashboard')
                         ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Menghapus user.
     * Sesuai rute: DELETE /superadmin/users/{user}
     */
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