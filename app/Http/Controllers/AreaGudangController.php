<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\AreaGudang;

class AreaGudangController extends Controller
{
    public function index()
    {
        $areas = AreaGudang::orderBy('created_at', 'asc')->get();
        return view('areaGudang.index', compact('areas'));
    }

    public function create()
    {
        $gudangs = Gudang::where('gudang_status', 1)->get();
        return view('areaGudang.create', compact('gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gudang_id'      => 'required|exists:gudang,id',
            'kode_area'      => 'nullable|string|max:255|unique:area_gudang,kode_area',
            'nama_area'      => 'required|string|max:255',
            'jenis_area'     => 'nullable|string|max:100',
            'pic'            => 'nullable|string|max:255',
            'kapasitas_area' => 'nullable|string|max:100',
            'suhu'           => 'nullable|string|max:50',
            'kelembaban'     => 'nullable|string|max:50',
            'keterangan'     => 'nullable|string',
            'area_status'    => 'required|boolean',
        ]);

        AreaGudang::createArea($request->all());

        return redirect()->route('areaGudang.index')->with('success', 'Area gudang berhasil ditambahkan.');
    }

    public function show($id)
    {
        $area = AreaGudang::with('gudang')->findOrFail($id);
        return view('areaGudang.show', compact('area'));
    }

    public function edit($id)
    {
        $area = AreaGudang::findOrFail($id);
        $gudangs = Gudang::where('gudang_status', 1)->get();
        return view('areaGudang.edit', compact('area', 'gudangs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gudang_id'      => 'required|exists:gudang,id',
            'kode_area'      => 'nullable|string|max:255|unique:area_gudang,kode_area,' . $id,
            'nama_area'      => 'required|string|max:255',
            'jenis_area'     => 'nullable|string|max:100',
            'pic'            => 'nullable|string|max:255',
            'kapasitas_area' => 'nullable|string|max:100',
            'suhu'           => 'nullable|string|max:50',
            'kelembaban'     => 'nullable|string|max:50',
            'keterangan'     => 'nullable|string',
            'area_status'    => 'required|boolean',
        ]);

        $area = AreaGudang::findOrFail($id);
        $area->updateArea($request->all());

        return redirect()->route('areaGudang.index')->with('success', 'Area gudang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $area = AreaGudang::findOrFail($id);
        $area->deleteArea();

        return redirect()->route('areaGudang.index')->with('success', 'Area gudang berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $area = AreaGudang::findOrFail($id);
            $area->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status area gudang berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}
