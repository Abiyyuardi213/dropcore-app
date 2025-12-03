<?php

namespace App\Http\Controllers;

use App\Models\SumberKeuangan;
use Illuminate\Http\Request;

class SumberKeuanganController extends Controller
{
    public function index()
    {
        $data = SumberKeuangan::orderBy('nama_sumber', 'asc')->get();

        return view('keuangan.sumber_keuangan.index', compact('data'));
    }

    public function create()
    {
        return view('keuangan.sumber_keuangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sumber' => 'required|string|max:255'
        ]);

        SumberKeuangan::create([
            'nama_sumber' => $request->nama_sumber
        ]);

        return redirect()->route('sumber-keuangan.index')
            ->with('success', 'Sumber keuangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sumber = SumberKeuangan::findOrFail($id);

        return view('keuangan.sumber_keuangan.edit', compact('sumber'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sumber' => 'required|string|max:255'
        ]);

        $sumber = SumberKeuangan::findOrFail($id);
        $sumber->update([
            'nama_sumber' => $request->nama_sumber
        ]);

        return redirect()->route('sumber-keuangan.index')
            ->with('success', 'Sumber keuangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sumber = SumberKeuangan::findOrFail($id);
        $sumber->delete();

        return redirect()->route('sumber-keuangan.index')
            ->with('success', 'Sumber keuangan berhasil dihapus.');
    }
}
