<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\AreaGudang;
use App\Models\RakGudang;

class DashboardGudangController extends Controller
{
    public function index()
    {
        $totalGudang = Gudang::count();
        $totalAreaGudang = AreaGudang::count();
        $totalRakGudang = RakGudang::count();

        return view('dashboardGudang', compact(
            'totalGudang',
            'totalAreaGudang',
            'totalRakGudang',
        ));
    }
}
