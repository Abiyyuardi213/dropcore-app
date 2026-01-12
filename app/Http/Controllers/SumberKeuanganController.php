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
            'nama_sumber' => 'required|string|max:255',
            'jenis' => 'required|in:bank,tunai,e-wallet',
            'nomor_rekening' => 'nullable|string|max:50',
            'atas_nama' => 'nullable|string|max:255',
            'saldo' => 'nullable|numeric|min:0'
        ]);

        SumberKeuangan::create($request->all());

        return redirect()->route('sumber-keuangan.index')
            ->with('success', 'Akun keuangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sumber = SumberKeuangan::findOrFail($id);

        return view('keuangan.sumber_keuangan.edit', compact('sumber'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sumber' => 'required|string|max:255',
            'jenis' => 'required|in:bank,tunai,e-wallet',
            'nomor_rekening' => 'nullable|string|max:50',
            'atas_nama' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        $sumber = SumberKeuangan::findOrFail($id);

        // Exclude saldo from direct update to prevent manipulation without transaction
        $sumber->update($request->except(['saldo']));

        return redirect()->route('sumber-keuangan.index')
            ->with('success', 'Akun keuangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sumber = SumberKeuangan::findOrFail($id);
        $sumber->delete();

        return redirect()->route('sumber-keuangan.index')
            ->with('success', 'Sumber keuangan berhasil dihapus.');
    }
}
