<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use Illuminate\Http\Request;
use App\Models\Provinsi;

class KotaController extends Controller
{
    public function index()
    {
        $kotas = Kota::with('provinsi.wilayah')->orderBy('name', 'asc')->get();
        $provinsis = Provinsi::orderBy('name', 'asc')->get();

        return view('kota.index', compact('kotas', 'provinsis'));
    }

    public function sync(\App\Services\IndoRegionService $regionService)
    {
        try {
            $count = $regionService->syncCities();
            return redirect()->route('kota.index')->with('success', "Sinkronisasi Berhasil. {$count} Kota/Kabupaten diperbarui.");
        } catch (\Exception $e) {
            return redirect()->route('kota.index')->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}
