<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\PenerimaanBarang;
use App\Models\PengeluaranBarang;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        // Field Reports
        $laporans = Laporan::with('user')->latest()->get();

        // Dashboard Stats (Simple Daily Summary)
        $today = now()->format('Y-m-d');

        $penerimaanCount = PenerimaanBarang::whereDate('tanggal_penerimaan', $today)->count();
        $pengeluaranCount = PengeluaranBarang::whereDate('tanggal_pengeluaran', $today)->count();
        $totalStok = Stok::sum('quantity');

        return view('laporan.index', compact('laporans', 'penerimaanCount', 'pengeluaranCount', 'totalStok'));
    }

    public function create()
    {
        return view('laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'lokasi'          => 'nullable|string|max:255',
            'kondisi_cuaca'   => 'nullable|string|max:100',
            'kategori'        => 'required|in:operasional,insiden,lain_lain',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('laporan-foto', 'public');
            }

            Laporan::create([
                'tanggal_laporan' => $request->tanggal_laporan,
                'judul'           => $request->judul,
                'deskripsi'       => $request->deskripsi,
                'lokasi'          => $request->lokasi,
                'kondisi_cuaca'   => $request->kondisi_cuaca,
                'kategori'        => $request->kategori,
                'foto'            => $fotoPath,
                'status'          => 'pending', // Default
            ]);

            DB::commit();

            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);
        return view('laporan.show', compact('laporan'));
    }

    public function destroy($id)
    {
        try {
            $laporan = Laporan::findOrFail($id);

            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }

            $laporan->delete();

            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
