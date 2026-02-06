<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        $wilayahs = Wilayah::orderBy('created_at', 'asc')->get();
        return view('wilayah.index', compact('wilayahs'));
    }

    public function create()
    {
        return view('wilayah.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'negara' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status_wilayah' => 'required|boolean',
        ]);

        Wilayah::createWilayah($request->all());

        return redirect()->route('wilayah.index')->with('success', 'Negara berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        return view('wilayah.index', compact('wilayah'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'negara' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status_wilayah' => 'required|boolean',
        ]);

        $wilayah = Wilayah::findOrFail($id);
        $wilayah->updateWilayah($request->all());

        return redirect()->route('wilayah.index')->with('success', 'Negara berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $wilayah->deleteWilayah();

        return redirect()->route('wilayah.index')->with('success', 'Wilayah berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $wilayah = Wilayah::findOrFail($id);
            $wilayah->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status negara berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }

    public function sync(\App\Services\IndoRegionService $regionService)
    {
        try {
            $regionService->syncWilayah();
            return redirect()->route('wilayah.index')->with('success', 'Sinkronisasi Wilayah Berhasil.');
        } catch (\Exception $e) {
            return redirect()->route('wilayah.index')->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}
