<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Wilayah;

class ProvinsiController extends Controller
{
    public function index()
    {
        $provinsis = Provinsi::with('wilayah')->orderBy('name', 'asc')->get();
        return view('provinsi.index', compact('provinsis'));
    }

    public function sync(\App\Services\IndoRegionService $regionService)
    {
        try {
            $count = $regionService->syncProvinces();
            return redirect()->route('provinsi.index')->with('success', "Sinkronisasi Berhasil. {$count} Provinsi diperbarui.");
        } catch (\Exception $e) {
            return redirect()->route('provinsi.index')->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }

    // Placeholder for toggleStatus if needed based on route definition, 
    // but sticking to user request for now.
    public function toggleStatus($id)
    {
        // Implementation skipped as not requested, but keeping method to avoid route error if invoked
        return response()->json(['success' => false, 'message' => 'Not implemented']);
    }
}
