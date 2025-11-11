<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index_login()
    {
        return view('auth.login');
    }

    public function action_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(to: '/login')
                ->withErrors($validator)
                ->withInput();
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();

            $user = Auth::user();
            $peran = $user->peran ?? '';

            if ($peran === 'admin') {
                return redirect()->intended('/admin/home');
            } elseif ($peran === 'customer') {
                return redirect()->intended('/customer/home');
            }

            return redirect('/');
        }


        return back()->withErrors(['username' => 'Login gagal: username/password salah'])->withInput();
    }

    public function index_register()
    {
        return view('auth.register');
    }
}
