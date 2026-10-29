<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\AreaGudang;
use App\Models\RakGudang;
use App\Models\Stok;
use App\Models\MutasiStok;
use Illuminate\Support\Facades\DB;

class DashboardGudangController extends Controller
{
    public function index(\App\Services\WarehouseCapacityService $capacityService)
    {
        $totalGudang = Gudang::count();
        $totalAreaGudang = AreaGudang::count();
        $totalRakGudang = RakGudang::count();

        $totalStokReal = Stok::sum('quantity');

        // Group stock by Warehouse
        $stokPerGudang = Gudang::withCount(['stok as total_items' => function ($query) {
            $query->select(DB::raw('SUM(quantity)'));
        }])->get();

        $recentMutations = MutasiStok::with(['produk', 'gudangAsal', 'gudangTujuan'])
            ->latest()
            ->take(5)
            ->get();

        $warehouseMap = $capacityService->warehouses();
        $rackCapacities = $warehouseMap->flatMap(fn ($warehouse) => $warehouse['areas']->flatMap(fn ($area) => $area['racks']->pluck('capacity')));
        $capacitySummary = [
            'free' => $rackCapacities->where('status', 'free')->count(),
            'near' => $rackCapacities->where('status', 'near')->count(),
            'full' => $rackCapacities->where('status', 'full')->count(),
            'unknown' => $rackCapacities->where('known', false)->count(),
        ];

        return view('dashboardGudang', compact(
            'totalGudang',
            'totalAreaGudang',
            'totalRakGudang',
            'totalStokReal',
            'stokPerGudang',
            'recentMutations',
            'warehouseMap',
            'capacitySummary'
        ));
    }
}
