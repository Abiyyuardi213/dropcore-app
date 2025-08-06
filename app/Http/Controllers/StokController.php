<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Gudang;
use App\Models\AreaGudang;
use App\Models\KondisiBarang;
use App\Models\RakGudang;
use App\Models\Stok;

class StokController extends Controller
{
    public function index()
    {
        $stoks = Stok::orderBy('created_at', 'asc')->get();
        $kondisis = KondisiBarang::all();
        return view('stok.index', compact('stoks', 'kondisis'));
    }

    public function create()
    {
        $produks = Products::all();
        $gudangs = Gudang::all();
        $areas = AreaGudang::all();
        $raks = RakGudang::all();
        $kondisis = KondisiBarang::all();

        return view('stok.create', compact('produks', 'gudangs', 'areas', 'raks', 'kondisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:products,id',
            'gudang_id' => 'required|exists:gudang,id',
            'area_id' => 'required|exists:area_gudang,id',
            'rak_id' => 'required|exists:rak_gudang,id',
            'quantity' => 'required|integer|min:1',
            'kondisi_id' => 'nullable|exists:kondisi_barang,id'
        ]);

        Stok::createStok($request->all());

        return redirect()->route('stok.index')->with('success', 'Stok produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $stok = Stok::findOrFail($id);
        $produks = Products::all();
        $gudangs = Gudang::all();
        $areas = AreaGudang::all();
        $raks = RakGudang::all();
        $kondisis = KondisiBarang::all();

        return view('stok.edit', compact('stok', 'produks', 'gudangs', 'areas', 'raks', 'kondisis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:products,id',
            'gudang_id' => 'required|exists:gudang,id',
            'area_id' => 'required|exists:area_gudang,id',
            'rak_id' => 'required|exists:rak_gudang,id',
            'quantity' => 'required|integer|min:1',
            'kondisi_id' => 'nullable|exists:kondisi_barang,id'
        ]);

        $stok = Stok::findOrFail($id);
        $stok->updateStok($request->all());

        return redirect()->route('stok.index')->with('success', 'Stok produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = Stok::findOrFail($id);
        $stok->deleteStok();

        return redirect()->route('stok.index')->with('success', 'Stok produk berhasil dihapus.');
    }

    public function updateKondisi(Request $request, $id)
    {
        $request->validate([
            'kondisi_id' => 'nullable|exists:kondisi_barang,id'
        ]);

        $stok = Stok::findOrFail($id);
        $stok->update(['kondisi_id' => $request->kondisi_id]);

        return response()->json(['status' => 'ok']);
    }
}
