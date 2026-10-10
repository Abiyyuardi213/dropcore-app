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

        // Monthly Sales Trend (Last 6 Months)
        $salesChartLabels = [];
        $salesChartData = [];
        $pengeluaranChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->translatedFormat('M Y');
            $salesChartLabels[] = $monthName;

            // Total sales amount from Orders
            $orderTotal = Order::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->where('status', '!=', 'cancelled')
                ->sum('total_price');

            $salesChartData[] = (float) $orderTotal;

            // Total outgoing stock transaction count / value
            $pengeluaranCount = PengeluaranBarang::whereMonth('tanggal_pengeluaran', $month->month)
                ->whereYear('tanggal_pengeluaran', $month->year)
                ->count();

            $pengeluaranChartData[] = $pengeluaranCount;
        }

        $totalPenjualanBulanIni = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

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
            'recentLogs',
            'salesChartLabels',
            'salesChartData',
            'pengeluaranChartData',
            'totalPenjualanBulanIni'
        ));
    }
}
