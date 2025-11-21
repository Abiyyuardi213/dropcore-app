<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardOfficeController extends Controller
{
    public function index()
    {
        $totalKantor = Kantor::count();
        $totalDivisi = Divisi::count();
        $totalJabatan = Jabatan::count();

        $totalPegawai = User::whereHas('role', function ($q) {
            $q->where('role_name', 'staff');
        })->count();

        return view('dashboardOffice', compact(
            'totalKantor',
            'totalDivisi',
            'totalJabatan',
            'totalPegawai',
        ));
    }
}

