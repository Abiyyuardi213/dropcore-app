<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPeran = Role::count();
        $totalPengguna = User::count();
        $totalProduk = Products::count();

        return view('dashboard', compact(
            'totalPeran',
            'totalPengguna',
            'totalProduk',
        ));
    }
}
