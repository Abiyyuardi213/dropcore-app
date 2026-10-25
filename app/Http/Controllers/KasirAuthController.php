<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KasirAuthController extends Controller
{
    public function create()
    {
        return view('auth.login-kasir');
    }

    public function store(Request $request)
    {
        $request->validate(['login' => 'required|string', 'password' => 'required|string']);
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::with('role')->where($field, $request->login)->first();
        if (!$user || !Hash::check($request->password, $user->password) || !$user->role?->isCashier()) {
            return back()->withErrors(['login' => 'Username/email atau password salah, atau akun tidak memiliki akses kasir.'])->withInput($request->only('login'));
        }
        if ($user->status_kepegawaian !== 'aktif' || !$user->role->role_status) {
            return back()->withErrors(['login' => 'Akun atau role kasir sedang dinonaktifkan.'])->withInput($request->only('login'));
        }
        Auth::login($user);
        $request->session()->regenerate();
        // Do not follow an intended URL from another portal.
        $request->session()->forget('url.intended');
        return redirect()->route('kasir.index');
    }
}
