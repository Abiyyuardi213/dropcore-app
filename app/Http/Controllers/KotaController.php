<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use Illuminate\Http\Request;
use App\Models\Provinsi;

class KotaController extends Controller
{
    public function index()
    {
        // Fetch all cities, might be heavy. For now, fetch all.
        // Eager load provinsi.wilayah for display
        $kotas = Kota::with('provinsi.wilayah')->orderBy('name', 'asc')->get();

        // Pass provinces for filter if needed (though we remove filters for simplicity or keep them compatible)
        $provinsis = Provinsi::orderBy('name', 'asc')->get();

        return view('kota.index', compact('kotas', 'provinsis'));
    }
}
