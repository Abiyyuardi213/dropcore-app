<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Stok;

class DashboardProdukController extends Controller
{
    public function index()
    {
        $totalProduk = Products::count();
        $totalStok = Stok::count();

        return view('dashboardProduk', compact(
            'totalProduk',
            'totalStok',
        ));
    }
}
