<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'login' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    //     $user = User::where($loginType, $request->login)->first();

    //     if ($user && Hash::check($request->password, $user->password)) {
    //         Auth::login($user);
    //         return redirect()->intended('/dashboard');
    //     }

    //     return back()->withErrors([
    //         'login' => 'Kredensial salah atau tidak ditemukan.',
    //     ])->withInput();
    // }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginType, $request->login)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            if ($user->role->role_name === 'admin') {
                return redirect()->intended('/dashboard');
            }

            if ($user->role->role_name === 'customer') {
                return redirect()->intended('/homepage');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'login' => 'Kredensial salah atau tidak ditemukan.',
        ])->withInput();
    }

    public function loginCustomer(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginType, $request->login)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            if ($user->role->role_name !== 'customer') {
                return back()->withErrors([
                    'login' => 'Akun ini bukan akun customer. Gunakan halaman login admin.',
                ]);
            }

            Auth::login($user);
            return redirect('/homepage');
        }

        return back()->withErrors([
            'login-customer' => 'Kredensial salah atau tidak ditemukan.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login-customer');
    }
}
