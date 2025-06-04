<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use app\Models\Wilayah;

class ProvinsiController extends Controller
{
    public function index()
    {
        $provinsis = Provinsi::orderBy('created_at', 'asc')->get();
        return view('provinsi.index', compact('provinsis'));
    }

    public function create()
    {
        $wilayahs = Wilayah::where('status_wilayah', 1)->get();
        return view('provinsi.create', compact('wilayahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wilayah_id' => 'required|exists:wilayah,id',
            'provinsi' => 'required|string|max:255|unique:provinsi,provinsi',
            'deskripsi' => 'nullable|string',
            'status_provinsi' => 'required|boolean',
        ]);

        Provinsi::createProvinsi($request->all());

        return redirect()->route('provinsi.index')->with('success', 'Provinsi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $provinsi = Provinsi::findOrFail($id);
        $wilayahs = Wilayah::all();
        return view('provinsi.edit', compact('provinsi', 'wilayahs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'wilayah_id' => 'required|exists:wilayah,id',
            'provinsi' => 'required|string|max:255|unique:provinsi,provinsi,' . $id,
            'deskripsi' => 'nullable|string',
            'status_provinsi' => 'required|boolean',
        ]);

        $provinsi = Provinsi::findOrFail($id);
        $provinsi->updateProvinsi($request->all());

        return redirect()->route('provinsi.index')->with('success', 'Provinsi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $provinsi = Provinsi::findOrFail($id);
        $provinsi->deleteProvinsi();

        return redirect()->route('provinsi.index')->with('success', 'Provinsi berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $provinsi = Provinsi::findOrFail($id);
            $provinsi->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status provinsi berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}
