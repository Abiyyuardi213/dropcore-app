<?php

namespace App\Http\Controllers;

use App\Models\MutasiStok;
use App\Models\Products;
use App\Models\Gudang;
use App\Models\AreaGudang;
use App\Models\RakGudang;
use App\Models\Stok;
use App\Models\KondisiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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

    // public function create()
    // {
    //     $products = Products::all();
    //     $gudangs = Gudang::all();
    //     $areas = AreaGudang::all();
    //     $raks = RakGudang::all();

    //     return view('mutasi-stok.create', compact('products', 'gudangs', 'areas', 'raks'));
    // }

    public function create()
    {
        $products = Products::orderBy('name', 'asc')->get();
        $gudangs = Gudang::with('areas.raks')->orderBy('nama_gudang', 'asc')->get();
        $kondisis = KondisiBarang::all();

        // Fetch existing stocks for selection in Outbound/Transfer
        $stoks = Stok::with(['produk', 'gudang', 'area', 'rak', 'kondisi'])
            ->where('quantity', '>', 0)
            ->get();

        return view('mutasi-stok.create', compact('products', 'gudangs', 'kondisis', 'stoks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_mutasi'      => 'required|in:masuk,keluar,pindah',
            'referensi'         => 'nullable|string|max:50',
            'produk_id'         => 'required|exists:products,id',
            'kondisi_id'        => 'required|exists:kondisi_barang,id',

            // Asal (Wajib jika Keluar/Pindah)
            'gudang_asal_id'    => 'required_if:jenis_mutasi,keluar,pindah|nullable|exists:gudang,id',
            'area_asal_id'      => 'nullable|exists:area_gudang,id',
            'rak_asal_id'       => 'nullable|exists:rak_gudang,id',

            // Tujuan (Wajib jika Masuk/Pindah)
            'gudang_tujuan_id'  => 'required_if:jenis_mutasi,masuk,pindah|nullable|exists:gudang,id',
            'area_tujuan_id'    => 'nullable|exists:area_gudang,id',
            'rak_tujuan_id'     => 'nullable|exists:rak_gudang,id',

            'quantity'          => 'required|integer|min:1',
            'tanggal_mutasi'    => 'required|date',
            'keterangan'        => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $qty = $validated['quantity'];
            $produkId = $validated['produk_id'];
            $kondisiId = $validated['kondisi_id'];
            $jenis = $validated['jenis_mutasi'];

            // 1. Handle Source (Pengurangan Stok) untuk Keluar & Pindah
            if (in_array($jenis, ['keluar', 'pindah'])) {
                $stokAsal = Stok::where('produk_id', $produkId)
                    ->where('kondisi_id', $kondisiId)
                    ->where('gudang_id', $validated['gudang_asal_id'])
                    ->where('area_id', $validated['area_asal_id'])
                    ->where('rak_id', $validated['rak_asal_id'])
                    ->first();

                if (!$stokAsal || $stokAsal->quantity < $qty) {
                    throw new \Exception("Stok tidak mencukupi di lokasi asal (Tersedia: " . ($stokAsal->quantity ?? 0) . ")");
                }

                $stokAsal->quantity -= $qty;
                $stokAsal->save();
            }

            // 2. Handle Destination (Penambahan Stok) untuk Masuk & Pindah
            if (in_array($jenis, ['masuk', 'pindah'])) {
                $stokTujuan = Stok::where('produk_id', $produkId)
                    ->where('kondisi_id', $kondisiId)
                    ->where('gudang_id', $validated['gudang_tujuan_id'])
                    ->where('area_id', $validated['area_tujuan_id'])
                    ->where('rak_id', $validated['rak_tujuan_id'])
                    ->first();

                if ($stokTujuan) {
                    $stokTujuan->quantity += $qty;
                    $stokTujuan->save();
                } else {
                    Stok::create([
                        'produk_id' => $produkId,
                        'gudang_id' => $validated['gudang_tujuan_id'],
                        'area_id' => $validated['area_tujuan_id'],
                        'rak_id' => $validated['rak_tujuan_id'],
                        'quantity' => $qty,
                        'kondisi_id' => $kondisiId
                    ]);
                }
            }

            // 3. Catat Mutasi
            MutasiStok::createMutasi($validated);

            DB::commit();
            return redirect()->route('mutasi-stok.index')->with('success', 'Mutasi stok berhasil tercatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // public function edit($id)
    // {
    //     $mutasi = MutasiStok::with([
    //         'produk', 'gudangAsal', 'areaAsal', 'rakAsal', 'gudangTujuan', 'areaTujuan', 'rakTujuan'
    //     ])->findOrFail($id);

    //     $products = Products::all();
    //     $gudangs = Gudang::all();
    //     $areas = AreaGudang::all();
    //     $raks = RakGudang::all();

    //     return view('mutasi-stok.edit', compact('mutasi', 'products', 'gudangs', 'areas', 'raks'));
    // }

    public function edit($id)
    {
        $mutasi = MutasiStok::with(['produk', 'gudangAsal', 'areaAsal', 'rakAsal', 'gudangTujuan', 'areaTujuan', 'rakTujuan'])
            ->findOrFail($id);

        $products = Products::orderBy('name', 'asc')->get();
        $gudangs = Gudang::with('areas.raks')->orderBy('nama_gudang', 'asc')->get();
        $kondisis = KondisiBarang::all();

        return view('mutasi-stok.edit', compact('mutasi', 'products', 'gudangs', 'kondisis'));
    }

    public function update(Request $request, $id)
    {
        return back()->with('error', 'Update mutasi tidak diizinkan.');
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
            'rakTujuan',
            'user'
        ])->findOrFail($id);

        return view('mutasi-stok.show', compact('mutasi'));
    }

    public function print($id)
    {
        $mutasi = MutasiStok::with([
            'produk',
            'gudangAsal',
            'areaAsal',
            'rakAsal',
            'gudangTujuan',
            'areaTujuan',
            'rakTujuan',
            'user'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('mutasi-stok.invoice_pdf', compact('mutasi'));
        return $pdf->stream('Invoice-' . $mutasi->id . '.pdf');
    }
}
