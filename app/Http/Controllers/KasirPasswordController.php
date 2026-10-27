<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KasirPasswordController extends Controller
{
    public function edit()
    {
        return view('kasir.password');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', 'min:8', 'max:128', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
        ]);
        $request->user()->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();
        $request->session()->regenerate(true);
        $request->session()->regenerateToken();
        return redirect()->route('kasir.password')->with('success', 'Password berhasil diperbarui. Gunakan password baru saat login berikutnya.');
    }
}
