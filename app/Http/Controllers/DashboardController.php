<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

use App\Models\Stok;
use App\Models\PenerimaanBarang;
use App\Models\PengeluaranBarang;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPeran = Role::count();
        $totalPengguna = User::count();
        $totalProduk = Products::count();
        $totalStok = Stok::sum('quantity');
        $totalPenerimaan = PenerimaanBarang::count();
        $totalPengeluaran = PengeluaranBarang::count();

        return view('dashboard', compact(
            'totalPeran',
            'totalPengguna',
            'totalProduk',
            'totalStok',
            'totalPenerimaan',
            'totalPengeluaran'
        ));
    }
}
