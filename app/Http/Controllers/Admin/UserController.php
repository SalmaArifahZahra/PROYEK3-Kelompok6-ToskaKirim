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
    // Menampilkan halaman dashboard superadmin.
    public function dashboard(): View
    {
        return view('superadmin.dashboard');
    }

    // Menampilkan daftar user.
    public function index(): View
    {
        $users = User::orderBy('nama')->get();
        
        return view('superadmin.users.dashboard', [
            'users' => $users
        ]);
    }

    // Menampilkan form untuk membuat user baru.
    public function create(): View
    {
        return view('superadmin.users.create');
    }

    // Menyimpan user baru.
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

        return redirect()->route('superadmin.users.dashboard')
                         ->with('success', 'User baru berhasil dibuat.');
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