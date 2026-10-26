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
        $totalPengguna = User::count();
        $totalPesananDistributor = Order::count();
        $totalStok = Stok::sum('quantity');
        $totalPenerimaan = PenerimaanBarang::count();
        $totalPengeluaran = PengeluaranBarang::where('status', 'completed')->count();

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

        $trend = app(\App\Services\DashboardSalesService::class)->trend();
        $salesChartLabels = $trend['salesChartLabels'];
        $salesChartData = $trend['salesChartData'];
        $pengeluaranChartData = $trend['pengeluaranChartData'];
        $totalPenjualanBulanIni = $trend['totalPenjualanBulanIni'];

        return view('dashboard', compact(
            'totalPengguna',
            'totalPesananDistributor',
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
