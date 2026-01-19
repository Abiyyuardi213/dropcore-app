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

        // Search by name
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // Filter by Kota
        if ($request->filled('kota_id')) {
            $query->where('kota_id', $request->kota_id);
        } elseif ($request->filled('provinsi_id')) {
            // Filter by Provinsi only if Kota is not selected
            $query->whereHas('kota', function ($q) use ($request) {
                $q->where('provinsi_id', $request->provinsi_id);
            });
        }

        $kecamatans = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        $provinsis = Provinsi::orderBy('name')->get();

        // Fetch cities based on province if selected, otherwise fetch all cities
        $kotas = $request->filled('provinsi_id')
            ? Kota::where('provinsi_id', $request->provinsi_id)->orderBy('name')->get()
            : Kota::orderBy('name')->get();

        // If a city is selected but province wasn't (edge case or direct link), try to load relevant cities or just the one.
        // But the UI will drive this. We'll stick to Dependent Dropdown logic.

        return view('kecamatan.index', compact('kecamatans', 'provinsis', 'kotas'));
    }
}
