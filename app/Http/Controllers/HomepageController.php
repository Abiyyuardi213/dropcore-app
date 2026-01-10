<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class HomepageController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (in_array($user->role->role_name, ['admin', 'staff'])) {
                return redirect()->route('dashboard');
            }
        }

        return view('homepage');
    }
}
