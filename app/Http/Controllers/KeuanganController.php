<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\SumberKeuangan;
use App\Models\KasPusat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($request) {

            $transaksi = Keuangan::create($request->all());

            $kas = KasPusat::first();

            if ($request->jenis_transaksi == 'pemasukkan') {
                $kas->saldo_saat_ini += $request->jumlah;
            } else {
                $kas->saldo_saat_ini -= $request->jumlah;
            }

            $kas->save();
        });

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil ditambahkan & saldo kas pusat diperbarui.');
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

        DB::transaction(function () use ($request, $id) {

            $transaksi = Keuangan::findOrFail($id);
            $kas = KasPusat::first();

            if ($transaksi->jenis_transaksi == 'pemasukkan') {
                $kas->saldo_saat_ini -= $transaksi->jumlah;
            } else {
                $kas->saldo_saat_ini += $transaksi->jumlah;
            }

            $transaksi->update($request->all());

            if ($request->jenis_transaksi == 'pemasukkan') {
                $kas->saldo_saat_ini += $request->jumlah;
            } else {
                $kas->saldo_saat_ini -= $request->jumlah;
            }

            $kas->save();
        });

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil diperbarui & saldo kas pusat diperbaiki.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $transaksi = Keuangan::findOrFail($id);
            $kas = KasPusat::first();

            if ($transaksi->jenis_transaksi == 'pemasukkan') {
                $kas->saldo_saat_ini -= $transaksi->jumlah;
            } else {
                $kas->saldo_saat_ini += $transaksi->jumlah;
            }

            $kas->save();

            $transaksi->delete();
        });

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil dihapus & saldo kas pusat dikembalikan.');
    }
}
