<?php

namespace App\Http\Controllers;

use App\Models\AreaGudang;
use App\Models\Gudang;
use App\Models\PengeluaranBarang;
use App\Models\PengeluaranBarangDetail;
use App\Models\Products;
use App\Models\RakGudang;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DetailPengeluaranBarangController extends Controller
{
    public function index($pengeluaran_id)
    {
        $gudang = Gudang::all();
        $area   = AreaGudang::all();
        $rak    = RakGudang::all();

        $pengeluaran = PengeluaranBarang::findOrFail($pengeluaran_id);
        $details     = PengeluaranBarangDetail::where('pengeluaran_id', $pengeluaran_id)->get();
        $produk      = Products::all();

        return view('detail_pengeluaran.index', compact(
            'pengeluaran',
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
            'pengeluaran_id' => 'required',
            'produk_id'      => 'required',
            'qty'            => 'required|numeric|min:1',
            'harga'          => 'required|numeric|min:0',
            'gudang_id'      => 'required',
            'area_id'        => 'required',
            'rak_id'         => 'required',
        ]);

        $subtotal = $request->qty * $request->harga;

        $stok = Stok::where('produk_id', $request->produk_id)
            ->where('gudang_id', $request->gudang_id)
            ->where('area_id', $request->area_id)
            ->where('rak_id', $request->rak_id)
            ->first();

        if (!$stok || $stok->quantity < $request->qty) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk pengeluaran.');
        }

        $detail = PengeluaranBarangDetail::create([
            'id'            => Str::uuid(),
            'pengeluaran_id'=> $request->pengeluaran_id,
            'produk_id'     => $request->produk_id,
            'qty'           => $request->qty,
            'harga'         => $request->harga,
            'subtotal'      => $subtotal,
            'gudang_id'     => $request->gudang_id,
            'area_id'       => $request->area_id,
            'rak_id'        => $request->rak_id,
        ]);

        $stok->quantity -= $request->qty;
        if ($stok->quantity < 0) $stok->quantity = 0;
        $stok->save();

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke pengeluaran.');
    }

    public function destroy($id)
    {
        $detail = PengeluaranBarangDetail::findOrFail($id);

        $stok = Stok::firstOrCreate(
            [
                'produk_id' => $detail->produk_id,
                'gudang_id' => $detail->gudang_id,
                'area_id'   => $detail->area_id,
                'rak_id'    => $detail->rak_id,
            ],
            [
                'quantity' => 0
            ]
        );

        $stok->quantity += $detail->qty;
        $stok->save();

        $detail->delete();

        return redirect()->back()->with('success', 'Detail pengeluaran berhasil dihapus.');
    }
}
