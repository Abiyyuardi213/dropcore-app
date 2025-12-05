<?php

namespace App\Http\Controllers;

use App\Models\AreaGudang;
use App\Models\Gudang;
use App\Models\KasPusat;
use App\Models\Keuangan;
use App\Models\PengeluaranBarang;
use App\Models\PengeluaranBarangDetail;
use App\Models\Products;
use App\Models\RakGudang;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'pengeluaran_id' => 'required',
    //         'produk_id'      => 'required',
    //         'qty'            => 'required|numeric|min:1',
    //         'harga'          => 'required|numeric|min:0',
    //         'gudang_id'      => 'required',
    //         'area_id'        => 'required',
    //         'rak_id'         => 'required',
    //         'kondisi_id'     => 'nullable',
    //     ]);

    //     $stok = Stok::where('produk_id', $request->produk_id)
    //         ->where('gudang_id', $request->gudang_id)
    //         ->where('area_id', $request->area_id)
    //         ->where('rak_id', $request->rak_id)
    //         ->first();

    //     if (!$stok || $stok->quantity < $request->qty) {
    //         return redirect()->back()->with('error', 'Stok tidak mencukupi untuk pengeluaran.');
    //     }

    //     $detail = PengeluaranBarangDetail::createDetail([
    //         'pengeluaran_id' => $request->pengeluaran_id,
    //         'produk_id'      => $request->produk_id,
    //         'stok_id'        => $stok->id,
    //         'qty'            => $request->qty,
    //         'harga'          => $request->harga,
    //         'kondisi_id'     => $request->kondisi_id,
    //         'gudang_id'      => $request->gudang_id,
    //         'area_id'        => $request->area_id,
    //         'rak_id'         => $request->rak_id,
    //     ]);

    //     $stok->quantity -= $request->qty;
    //     if ($stok->quantity < 0) $stok->quantity = 0;
    //     $stok->save();

    //     return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke pengeluaran.');
    // }

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
            'kondisi_id'     => 'nullable',
        ]);

        DB::transaction(function () use ($request) {

            $stok = Stok::where('produk_id', $request->produk_id)
                ->where('gudang_id', $request->gudang_id)
                ->where('area_id', $request->area_id)
                ->where('rak_id', $request->rak_id)
                ->first();

            if (!$stok || $stok->quantity < $request->qty) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk pengeluaran.');
            }

            $detail = PengeluaranBarangDetail::createDetail([
                'pengeluaran_id' => $request->pengeluaran_id,
                'produk_id'      => $request->produk_id,
                'stok_id'        => $stok->id,
                'qty'            => $request->qty,
                'harga'          => $request->harga,
                'kondisi_id'     => $request->kondisi_id,
                'gudang_id'      => $request->gudang_id,
                'area_id'        => $request->area_id,
                'rak_id'         => $request->rak_id,
            ]);

            $stok->quantity -= $request->qty;
            $stok->save();

            $jumlahMasuk = $request->qty * $request->harga;

            $keuangan = Keuangan::create([
                'jenis_transaksi' => 'pemasukkan',
                'jumlah' => $jumlahMasuk,
                'referensi_id' => $detail->id,
                'referensi_tabel' => 'pengeluaran_barang_detail',
                'sumber_id' => null,
                'keterangan' => 'Penjualan barang keluar',
                'tanggal_transaksi' => now(),
            ]);

            $kas = KasPusat::first();

            if (!$kas) {
                $kas = KasPusat::create([
                    'saldo_awal' => 0,
                    'saldo_saat_ini' => 0,
                ]);
            }

            $kas->tambahSaldo($jumlahMasuk);

        });

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan & keuangan tercatat.');
    }

    // public function destroy($id)
    // {
    //     $detail = PengeluaranBarangDetail::findOrFail($id);

    //     $stok = Stok::firstOrCreate(
    //         [
    //             'produk_id' => $detail->produk_id,
    //             'gudang_id' => $detail->gudang_id,
    //             'area_id'   => $detail->area_id,
    //             'rak_id'    => $detail->rak_id,
    //         ],
    //         ['quantity' => 0]
    //     );

    //     $stok->quantity += $detail->qty;
    //     $stok->save();

    //     $detail->deleteDetail();

    //     return redirect()->back()->with('success', 'Detail pengeluaran berhasil dihapus.');
    // }

    // public function destroy($id)
    // {
    //     $detail = PengeluaranBarangDetail::findOrFail($id);

    //     $stok = Stok::firstOrCreate(
    //         [
    //             'produk_id' => $detail->produk_id,
    //             'gudang_id' => $detail->gudang_id,
    //             'area_id'   => $detail->area_id,
    //             'rak_id'    => $detail->rak_id,
    //         ],
    //         ['quantity' => 0]
    //     );

    //     $stok->quantity += $detail->qty;
    //     $stok->save();

    //     $kas = KasPusat::first();

    //     if ($kas) {
    //         $jumlahRefund = $detail->qty * $detail->harga;
    //         $kas->kurangiSaldo($jumlahRefund);
    //     }

    //     $detail->deleteDetail();

    //     return redirect()->back()->with('success', 'Detail pengeluaran dihapus. Saldo kas dikurangi.');
    // }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $detail = PengeluaranBarangDetail::findOrFail($id);

            $stok = Stok::firstOrCreate(
                [
                    'produk_id' => $detail->produk_id,
                    'gudang_id' => $detail->gudang_id,
                    'area_id'   => $detail->area_id,
                    'rak_id'    => $detail->rak_id,
                ],
                ['quantity' => 0]
            );

            $stok->quantity += $detail->qty;
            $stok->save();

            $keuangan = Keuangan::where('referensi_id', $detail->id)
                ->where('referensi_tabel', 'pengeluaran_barang_detail')
                ->first();

            if ($keuangan) {
                $kas = KasPusat::first();

                if ($kas) {
                    if ($keuangan->jenis_transaksi === 'pemasukkan') {
                        $kas->kurangiSaldo($keuangan->jumlah);
                    } else {
                        $kas->tambahSaldo($keuangan->jumlah);
                    }
                }

                $kas->save();
                $keuangan->delete();
            }

            $detail->deleteDetail();
        });

        return redirect()->back()->with('success', 'Detail pengeluaran & transaksi keuangan terkait berhasil dihapus.');
    }
}
