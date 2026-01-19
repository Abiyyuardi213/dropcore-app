<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Wilayah;

class ProvinsiController extends Controller
{
    public function index()
    {
        // Just fetch all data
        // If too many, maybe paginate
        $provinsis = Provinsi::with('wilayah')->orderBy('name', 'asc')->get();
        return view('provinsi.index', compact('provinsis'));
    }
}
