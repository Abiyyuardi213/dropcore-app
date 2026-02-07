<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Gudang;
use App\Models\Supplier;
use App\Models\Distributor;
use App\Models\RiwayatAktivitasLog;

class DashboardMasterController extends Controller
{
    public function index()
    {
        // User & Role Stats
        $totalPeran = Role::count();
        $totalPengguna = User::count();

        // Regional Stats
        $totalProvinsi = Provinsi::count();
        $totalKota = Kota::count();
        $totalKecamatan = Kecamatan::count();
        $totalKelurahan = Kelurahan::count();

        // Network/Facility Stats
        $totalGudang = Gudang::count();
        $totalSupplier = Supplier::count();
        $totalDistributor = Distributor::count();

        // Recent Data
        $recentUsers = User::with('role')->latest()->take(5)->get();
        $recentLogs = RiwayatAktivitasLog::with('user')->latest()->take(10)->get();

        return view('dashboard-master', compact(
            'totalPeran',
            'totalPengguna',
            'totalProvinsi',
            'totalKota',
            'totalKecamatan',
            'totalKelurahan',
            'totalGudang',
            'totalSupplier',
            'totalDistributor',
            'recentUsers',
            'recentLogs'
        ));
    }
}
