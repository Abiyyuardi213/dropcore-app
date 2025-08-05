<?php

namespace App\Http\Controllers;

use App\Models\KondisiBarang;
use Illuminate\Http\Request;

class KondisiBarangController extends Controller
{
    public function index()
    {
        $kondisis = KondisiBarang::orderBy('created_at', 'asc')->get();
        return view('kondisi-barang.index', compact('kondisis'));
    }

    public function create()
    {
        return view('kondisi-barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kondisi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        KondisiBarang::createKondisi($request->all());

        return redirect()->route('kondisi-barang.index')->with('success', 'Kondisi barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kondisiBarang = KondisiBarang::findOrFail($id);
        return view('kondisi-barang.edit', compact('kondisi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kondisi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kondisiBarang = KondisiBarang::findOrFail($id);
        $kondisiBarang->updateKondisi($request->all());

        return redirect()->route('kondisi-barang.index')->with('success', 'Kondisi barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kondisiBarang = KondisiBarang::findOrFail($id);
        $kondisiBarang->deleteKondisi();

        return redirect()->route('kondisi-barang.index')->with('success', 'Kondisi barang berhasil dihapus.');
    }
}
