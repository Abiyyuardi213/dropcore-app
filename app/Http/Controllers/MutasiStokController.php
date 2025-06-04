<?php

namespace App\Http\Controllers;

use App\Models\MutasiStok;
use App\Models\Products;
use App\Models\Gudang;
use App\Models\AreaGudang;
use App\Models\RakGudang;
use App\Models\Stok;
use Illuminate\Http\Request;

class MutasiStokController extends Controller
{
    public function index()
    {
        $mutasi = MutasiStok::with([
            'produk',
            'gudangAsal',
            'areaAsal',
            'rakAsal',
            'gudangTujuan',
            'areaTujuan',
            'rakTujuan'
        ])->latest()->get();

        return view('mutasi-stok.index', compact('mutasi'));
    }

    public function create()
    {
        $products = Products::all();
        $gudangs = Gudang::all();
        $areas = AreaGudang::all();
        $raks = RakGudang::all();

        return view('mutasi-stok.create', compact('products', 'gudangs', 'areas', 'raks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:products,id',
            'gudang_asal_id' => 'required|exists:gudang,id',
            'area_asal_id' => 'nullable|exists:area_gudang,id',
            'rak_asal_id' => 'nullable|exists:rak_gudang,id',
            'gudang_tujuan_id' => 'required|exists:gudang,id',
            'area_tujuan_id' => 'nullable|exists:area_gudang,id',
            'rak_tujuan_id' => 'nullable|exists:rak_gudang,id',
            'quantity' => 'required|integer|min:1',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        MutasiStok::createMutasi($validated);

        return redirect()->route('mutasi-stok.index')->with('success', 'Mutasi stok berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mutasi = MutasiStok::with([
            'produk', 'gudangAsal', 'areaAsal', 'rakAsal', 'gudangTujuan', 'areaTujuan', 'rakTujuan'
        ])->findOrFail($id);

        $products = Products::all();
        $gudangs = Gudang::all();
        $areas = AreaGudang::all();
        $raks = RakGudang::all();

        return view('mutasi-stok.edit', compact('mutasi', 'products', 'gudangs', 'areas', 'raks'));
    }

    public function update(Request $request, $id)
    {
        $mutasi = MutasiStok::findOrFail($id);

        $validated = $request->validate([
            'produk_id' => 'required|exists:products,id',
            'gudang_asal_id' => 'required|exists:gudang,id',
            'area_asal_id' => 'nullable|exists:area_gudang,id',
            'rak_asal_id' => 'nullable|exists:rak_gudang,id',
            'gudang_tujuan_id' => 'required|exists:gudang,id',
            'area_tujuan_id' => 'nullable|exists:area_gudang,id',
            'rak_tujuan_id' => 'nullable|exists:rak_gudang,id',
            'quantity' => 'required|integer|min:1',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $mutasi->updateMutasi($validated);

        return redirect()->route('mutasi-stok.index')->with('success', 'Mutasi stok berhasil diperbarui.');
    }

    public function lokasiAsalProduk($produk_id)
    {
        $lokasiList = Stok::with(['gudang', 'area', 'rak'])
            ->where('produk_id', $produk_id)
            ->get()
            ->map(function ($stok) {
                return [
                    'gudang_id' => $stok->gudang_id,
                    'area_id' => $stok->area_id,
                    'rak_id' => $stok->rak_id,
                    'gudang_nama' => $stok->gudang->nama_gudang ?? '',
                    'area_kode' => $stok->area->kode_area ?? '',
                    'rak_kode' => $stok->rak->kode_rak ?? '',
                ];
            });

        return response()->json([
            'semua_lokasi' => $lokasiList,
            'lokasi_utama' => $lokasiList->first()
        ]);
    }

    public function getStokByProduk($produk_id)
    {
        $stok = Stok::with(['gudang', 'area', 'rak'])
                    ->where('produk_id', $produk_id)
                    ->get();

        return response()->json($stok);
    }

    public function destroy($id)
    {
        $mutasi = MutasiStok::findOrFail($id);
        $mutasi->delete();

        return redirect()->route('mutasi-stok.index')->with('success', 'Mutasi stok berhasil dihapus.');
    }

    public function show($id)
    {
        $mutasi = MutasiStok::with([
            'produk',
            'gudangAsal',
            'areaAsal',
            'rakAsal',
            'gudangTujuan',
            'areaTujuan',
            'rakTujuan'
        ])->findOrFail($id);

        return view('mutasi-stok.show', compact('mutasi'));
    }
}
