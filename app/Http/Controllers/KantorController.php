<?php

namespace App\Http\Controllers;

use App\Models\Kantor;
use App\Models\Kota;
use Illuminate\Http\Request;

class KantorController extends Controller
{
    public function index()
    {
        $kantors = Kantor::with('kota')->orderBy('created_at', 'asc')->get();
        return view('kantor.index', compact('kantors'));
    }

    public function create()
    {
        $kotas = Kota::orderBy('kota', 'asc')->get();
        return view('kantor.create', compact('kotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kantor'  => 'required|string|max:255',
            'kota_id'      => 'required|exists:kota,id',
            'alamat'       => 'nullable|string|max:255',
            'telepon'      => 'nullable|string|max:50',
            'jenis_kantor' => 'required|integer',
            'status'       => 'required|boolean',
        ]);

        Kantor::createKantor($request->all());

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kantor = Kantor::findOrFail($id);
        $kotas = Kota::orderBy('kota', 'asc')->get();

        return view('kantor.edit', compact('kantor', 'kotas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kantor'  => 'required|string|max:255',
            'kota_id'      => 'required|exists:kota,id',
            'alamat'       => 'nullable|string|max:255',
            'telepon'      => 'nullable|string|max:50',
            'jenis_kantor' => 'required|integer',
            'status'       => 'required|boolean',
        ]);

        $kantor = Kantor::findOrFail($id);
        $kantor->updateKantor($request->all());

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kantor = Kantor::findOrFail($id);
        $kantor->deleteKantor();

        return redirect()->route('kantor.index')->with('success', 'Kantor berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $kantor = Kantor::findOrFail($id);
            $kantor->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status kantor berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}
