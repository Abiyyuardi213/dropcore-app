<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

use App\Models\Stok;
use App\Models\PenerimaanBarang;
use App\Models\PengeluaranBarang;
use App\Models\MutasiStok;
use App\Models\Order;
use App\Models\RiwayatAktivitasLog;
use Illuminate\Support\Facades\DB;

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

        // Extra stats for fuller dashboard
        $lowStockProducts = Products::with('stok')
            ->get()
            ->filter(function ($product) {
                return $product->total_stock <= $product->min_stock;
            })
            ->take(5);

        $recentMutations = MutasiStok::with(['produk', 'user', 'gudangAsal', 'gudangTujuan'])
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(5)
            ->get();

        $recentLogs = RiwayatAktivitasLog::with('user')
            ->latest()
            ->take(10)
            ->get();


        return view('dashboard', compact(
            'totalPeran',
            'totalPengguna',
            'totalProduk',
            'totalStok',
            'totalPenerimaan',
            'totalPengeluaran',
            'lowStockProducts',
            'recentMutations',
            'recentOrders',
            'recentLogs'
        ));
    }
}
