<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\SumberKeuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Keuangan::with('sumber')->orderBy('tanggal_transaksi', 'desc');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $data = $query->get();

        return view('keuangan.keuangan.index', compact('data'));
    }

    public function create()
    {
        $sumberKeuangan = SumberKeuangan::orderBy('nama_sumber', 'asc')->get();
        return view('keuangan.keuangan.create', compact('sumberKeuangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_transaksi' => 'required|in:pemasukkan,pengeluaran',
            'jumlah' => 'required|numeric|min:0.01',
            'sumber_id' => 'nullable|exists:sumber_keuangan,id',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Keuangan::create($request->all());

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Keuangan::findOrFail($id);
        $sumberKeuangan = SumberKeuangan::orderBy('nama_sumber', 'asc')->get();

        return view('keuangan.keuangan.edit', compact('data', 'sumberKeuangan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_transaksi' => 'required|in:pemasukkan,pengeluaran',
            'jumlah' => 'required|numeric|min:0.01',
            'sumber_id' => 'nullable|exists:sumber_keuangan,id',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $keuangan = Keuangan::findOrFail($id);
        $keuangan->update($request->all());

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->delete();

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil dihapus.');
    }
}
