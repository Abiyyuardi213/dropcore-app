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

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginType, $request->login)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            // Perketat keamanan: Cek status kepegawaian
            if ($user->status_kepegawaian !== 'aktif') {
                return back()->withErrors([
                    'login' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
                ])->withInput();
            }

            // Perketat keamanan: Cek status role
            if ($user->role && !$user->role->role_status) {
                return back()->withErrors([
                    'login' => 'Role akses Anda sedang dinonaktifkan.',
                ])->withInput();
            }

            Auth::login($user);
            $request->session()->regenerate(); // Regenerate session ID to prevent fixation

            if ($user->role->role_name === 'admin') {
                return redirect()->intended('/dashboard')->with('success', 'Login berhasil! Selamat datang kembali.');
            }

            if ($user->role->role_name === 'customer') {
                return redirect()->intended('/homepage')->with('success', 'Login berhasil! Selamat datang kembali.');
            }

            return redirect()->intended('/')->with('success', 'Login berhasil! Selamat datang kembali.');
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

            // Cek jika bukan customer
            if ($user->role->role_name !== 'customer') {
                return back()->withErrors([
                    'login' => 'Akun ini bukan akun customer. Gunakan halaman login admin.',
                ]);
            }

            // Cek status akun
            if ($user->status_kepegawaian !== 'aktif') {
                return back()->withErrors([
                    'login' => 'Akun Anda telah dinonaktifkan.',
                ])->withInput();
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/homepage')->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()->withErrors([
            'login-customer' => 'Kredensial salah atau tidak ditemukan.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        $roleName = Auth::user() ? Auth::user()->role->role_name : null;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($roleName === 'customer') {
            return redirect('/login-customer')->with('success', 'Logout berhasil! Sampai jumpa lagi.');
        }

        return redirect('/login')->with('success', 'Logout berhasil! Sampai jumpa lagi.');
    }
}
