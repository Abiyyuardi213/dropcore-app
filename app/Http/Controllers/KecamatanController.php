<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\Wilayah;

class KecamatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kecamatan::with(['kota.provinsi.wilayah']);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kota_id')) {
            $query->where('kota_id', $request->kota_id);
        } elseif ($request->filled('provinsi_id')) {
            $query->whereHas('kota', function ($q) use ($request) {
                $q->where('provinsi_id', $request->provinsi_id);
            });
        }

        $kecamatans = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        $provinsis = Provinsi::orderBy('name')->get();

        $kotas = $request->filled('provinsi_id')
            ? Kota::where('provinsi_id', $request->provinsi_id)->orderBy('name')->get()
            : Kota::orderBy('name')->get();

        return view('kecamatan.index', compact('kecamatans', 'provinsis', 'kotas'));
    }

    public function sync(\App\Services\IndoRegionService $regionService)
    {
        try {
            // This might take a while, consider increasing max_execution_time
            $count = $regionService->syncDistricts();
            return redirect()->route('kecamatan.index')->with('success', "Sinkronisasi Berhasil. {$count} Kecamatan diperbarui.");
        } catch (\Exception $e) {
            return redirect()->route('kecamatan.index')->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}
