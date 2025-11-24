<?php

namespace App\Http\Controllers;

use App\Models\AreaGudang;
use App\Models\DetailPenerimaanBarang;
use App\Models\Gudang;
use App\Models\PenerimaanBarang;
use App\Models\Products;
use App\Models\RakGudang;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DetailPenerimaanBarangController extends Controller
{
    public function index($penerimaan_id)
    {
        $gudang = Gudang::all();
        $area   = AreaGudang::all();
        $rak    = RakGudang::all();

        $penerimaan = PenerimaanBarang::findOrFail($penerimaan_id);
        $details    = DetailPenerimaanBarang::where('penerimaan_id', $penerimaan_id)->get();
        $produk     = Products::all();

        return view('detail_penerimaan.index', compact(
            'penerimaan',
            'details',
            'produk',
            'gudang',
            'area',
            'rak'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penerimaan_id' => 'required',
            'produk_id'     => 'required',
            'qty'           => 'required|numeric|min:1',
            'harga'         => 'required|numeric|min:0',
            'gudang_id'     => 'required',
            'area_id'       => 'required',
            'rak_id'        => 'required',
        ]);

        $subtotal = $request->qty * $request->harga;

        // Tambah detail penerimaan
        $detail = DetailPenerimaanBarang::create([
            'id'            => Str::uuid(),
            'penerimaan_id' => $request->penerimaan_id,
            'produk_id'     => $request->produk_id,
            'qty'           => $request->qty,
            'harga'         => $request->harga,
            'subtotal'      => $subtotal,
            'gudang_id'     => $request->gudang_id,
            'area_id'       => $request->area_id,
            'rak_id'        => $request->rak_id,
        ]);

        // Update stok
        $stok = Stok::firstOrCreate(
            [
                'produk_id' => $request->produk_id,
                'gudang_id' => $request->gudang_id,
                'area_id'   => $request->area_id,
                'rak_id'    => $request->rak_id,
            ],
            [
                'quantity' => 0
            ]
        );

        $stok->quantity += $request->qty;
        $stok->save();

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke penerimaan.');
    }

    public function destroy($id)
    {
        $detail = DetailPenerimaanBarang::findOrFail($id);

        // Kurangi stok
        $stok = Stok::where('produk_id', $detail->produk_id)
                    ->where('gudang_id', $detail->gudang_id)
                    ->where('area_id', $detail->area_id)
                    ->where('rak_id', $detail->rak_id)
                    ->first();

        if ($stok) {
            $stok->quantity -= $detail->qty;
            if ($stok->quantity < 0) $stok->quantity = 0;
            $stok->save();
        }

        // Hapus detail
        $detail->delete();

        return redirect()->back()->with('success', 'Detail penerimaan berhasil dihapus.');
    }
}
