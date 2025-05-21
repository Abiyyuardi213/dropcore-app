<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index()
    {
        $gudangs = Gudang::orderBy('created_at', 'asc')->get();
        return view('gudang.index', compact('gudangs'));
    }

    public function create()
    {
        return view('gudang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gudang_status' => 'required|boolean',
        ]);

        Gudang::createGudang($request->all());

        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $gudang = Gudang::findOrFail($id);
        return view('gudang.edit', compact('gudang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gudang_status' => 'required|boolean',
        ]);

        $gudang = Gudang::findOrFail($id);
        $gudang->updateGudang($request->all());

        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gudang = Gudang::findOrFail($id);
        $gudang->deleteGudang();

        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $gudang = Gudang::findOrFail($id);
            $gudang->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status gudang berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}
