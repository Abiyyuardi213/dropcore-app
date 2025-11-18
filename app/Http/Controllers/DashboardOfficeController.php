<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Kantor;
use Illuminate\Http\Request;

class DashboardOfficeController extends Controller
{
    public function index()
    {
        $totalKantor = Kantor::count();
        $totalDivisi = Divisi::count();

        return view('dashboardOffice', compact(
            'totalKantor',
            'totalDivisi',
        ));
    }
}
