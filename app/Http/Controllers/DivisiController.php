<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::orderBy('created_at', 'asc')->get();
        return view('divisi.index', compact('divisis'));
    }

    public function create()
    {
        return view('divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'      => 'required|string|max:50|unique:divisi,kode',
            'name'      => 'required|string|max:255|unique:divisi,name',
            'deskripsi' => 'nullable|string',
            'status'    => 'required|boolean',
        ]);

        Divisi::createDivisi($request->all());

        return redirect()->route('divisi.index')
                         ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('divisi.edit', compact('divisi'));
    }

    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $request->validate([
            'kode'      => 'required|string|max:50|unique:divisi,kode,' . $id,
            'name'      => 'required|string|max:255|unique:divisi,name,' . $id,
            'deskripsi' => 'nullable|string',
            'status'    => 'required|boolean',
        ]);

        $divisi->updateDivisi($request->all());

        return redirect()->route('divisi.index')
                         ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);
        $divisi->deleteDivisi();

        return redirect()->route('divisi.index')
                         ->with('success', 'Divisi berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $divisi = Divisi::findOrFail($id);
            $divisi->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status divisi berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}
