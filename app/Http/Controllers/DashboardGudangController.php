<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\AreaGudang;

class DashboardGudangController extends Controller
{
    public function index()
    {
        $totalGudang = Gudang::count();
        $totalAreaGudang = AreaGudang::count();

        return view('dashboardGudang', compact(
            'totalGudang',
            'totalAreaGudang',
        ));
    }
}
