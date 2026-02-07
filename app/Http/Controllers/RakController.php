<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RakGudang;
use App\Models\Gudang;
use App\Models\AreaGudang;

class RakController extends Controller
{
    public function index()
    {
        $raks = RakGudang::orderBy('created_at', 'asc')->get();
        return view('rak-gudang.index', compact('raks'));
    }

    public function create()
    {
        $gudangs = Gudang::where('gudang_status', 1)->get();
        $areas = AreaGudang::where('area_status', 1)->get();
        return view('rak-gudang.create', compact('gudangs', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gudang_id'     => 'required|exists:gudang,id',
            'area_id'       => 'required|exists:area_gudang,id',
            'kode_rak'      => 'nullable|string|max:255|unique:rak_gudang,kode_rak',
            'jenis_rak'     => 'nullable|string|max:100',
            'posisi'        => 'nullable|string|max:255',
            'kapasitas_max' => 'nullable|string|max:100',
            'dimensi'       => 'nullable|string|max:100',
            'bahan_rak'     => 'nullable|string|max:100',
            'keterangan'    => 'nullable|string',
            'rak_status'    => 'required|boolean',
        ]);

        RakGudang::createRak($request->all());

        return redirect()->route('rak-gudang.index')->with('success', 'Rak gudang berhasil ditambahkan.');
    }

    public function show($id)
    {
        $rak = RakGudang::with(['gudang', 'area'])->findOrFail($id);
        return view('rak-gudang.show', compact('rak'));
    }

    public function edit($id)
    {
        $rak = RakGudang::findOrFail($id);
        $gudangs = Gudang::where('gudang_status', 1)->get();
        $areas = AreaGudang::where('area_status', 1)->get();
        return view('rak-gudang.edit', compact('rak', 'gudangs', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gudang_id'     => 'required|exists:gudang,id',
            'area_id'       => 'required|exists:area_gudang,id',
            'kode_rak'      => 'nullable|string|max:255|unique:rak_gudang,kode_rak,' . $id,
            'jenis_rak'     => 'nullable|string|max:100',
            'posisi'        => 'nullable|string|max:255',
            'kapasitas_max' => 'nullable|string|max:100',
            'dimensi'       => 'nullable|string|max:100',
            'bahan_rak'     => 'nullable|string|max:100',
            'keterangan'    => 'nullable|string',
            'rak_status'    => 'required|boolean',
        ]);

        $rak = RakGudang::findOrFail($id);
        $rak->updateRak($request->all());

        return redirect()->route('rak-gudang.index')->with('success', 'Rak gudang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rak = RakGudang::findOrFail($id);
        $rak->deleteRak();

        return redirect()->route('rak-gudang.index')->with('success', 'Rak gudang berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $rak = RakGudang::findOrFail($id);
            $rak->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status rak gudang berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}
