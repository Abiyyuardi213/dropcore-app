<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardMasterController extends Controller
{
    public function index()
    {
        $totalPeran = Role::count();
        $totalPengguna = User::count();
        $totalProvinsi = Provinsi::count();
        $totalKota = Kota::count();

        return view('dashboard-master', compact(
            'totalPeran',
            'totalPengguna',
            'totalProvinsi',
            'totalKota',
        ));
    }
}
