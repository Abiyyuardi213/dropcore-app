<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelurahan::with(['kecamatan.kota.provinsi.wilayah']);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        } elseif ($request->filled('kota_id')) {
            $query->whereHas('kecamatan', function ($q) use ($request) {
                $q->where('kota_id', $request->kota_id);
            });
        } elseif ($request->filled('provinsi_id')) {
            $query->whereHas('kecamatan.kota', function ($q) use ($request) {
                $q->where('provinsi_id', $request->provinsi_id);
            });
        }

        $kelurahans = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        $provinsis = \App\Models\Provinsi::orderBy('name')->get();

        $kotas = $request->filled('provinsi_id')
            ? \App\Models\Kota::where('provinsi_id', $request->provinsi_id)->orderBy('name')->get()
            : \App\Models\Kota::orderBy('name')->get();

        if ($request->filled('kota_id')) {
            $kecamatans = Kecamatan::where('kota_id', $request->kota_id)->orderBy('name')->get();
        } elseif ($request->filled('provinsi_id')) {
            $kecamatans = Kecamatan::whereHas('kota', function ($q) use ($request) {
                $q->where('provinsi_id', $request->provinsi_id);
            })->orderBy('name')->get();
        } else {
            $kecamatans = Kecamatan::orderBy('name')->get();
        }

        return view('kelurahan.index', compact('kelurahans', 'provinsis', 'kotas', 'kecamatans'));
    }

    public function sync(\App\Services\IndoRegionService $regionService)
    {
        try {
            // This is extremely heavy. 
            $count = $regionService->syncVillages();
            return redirect()->route('kelurahan.index')->with('success', "Sinkronisasi Berhasil. {$count} Kelurahan diperbarui.");
        } catch (\Exception $e) {
            return redirect()->route('kelurahan.index')->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}
