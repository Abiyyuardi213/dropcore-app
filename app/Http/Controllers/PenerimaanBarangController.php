<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBarang;
use App\Models\DetailPenerimaanBarang;
use App\Models\Distributor;
use App\Models\Products;
use App\Models\Gudang;
use App\Models\KondisiBarang;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PenerimaanBarangController extends Controller
{
    public function index()
    {
        $data = PenerimaanBarang::with(['distributor', 'supplier', 'user'])->latest()->get();
        // Fallback for supplier_id if distributor relation is empty (legacy support if needed, but we focus on distributor)
        return view('penerimaan_barang.index', compact('data'));
    }

    public function create()
    {
        $distributors = Distributor::orderBy('nama_distributor')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $products = Products::orderBy('name')->get();
        $gudangs = Gudang::with('areas.raks')->orderBy('nama_gudang')->get();
        $kondisis = KondisiBarang::all();
        $sumberKeuangan = \App\Models\SumberKeuangan::where('is_active', true)->orderBy('nama_sumber', 'asc')->get();
        $no_penerimaan = PenerimaanBarang::generateNomorPenerimaan();

        return view('penerimaan_barang.create', compact('distributors', 'suppliers', 'products', 'gudangs', 'kondisis', 'no_penerimaan', 'sumberKeuangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_pengirim'      => 'required|in:supplier,distributor',
            'distributor_id'     => 'nullable|required_if:tipe_pengirim,distributor|exists:distributors,id',
            'supplier_id'        => 'nullable|required_if:tipe_pengirim,supplier|exists:suppliers,id',
            'tanggal_penerimaan' => 'required|date',
            'referensi'          => 'nullable|string|max:100',
            'keterangan'         => 'nullable|string',
            'submit_action'      => 'required|in:draft,process',
            'sumber_id'          => 'required_if:submit_action,process|nullable|exists:sumber_keuangan,id', // Required if processing

            'items'              => 'required|array|min:1',
            'items.*.produk_id'  => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.kondisi_id' => 'required|exists:kondisi_barang,id',
            'items.*.gudang_id'  => 'required|exists:gudang,id',
            'items.*.area_id'    => 'required|exists:area_gudang,id',
            'items.*.rak_id'     => 'required|exists:rak_gudang,id',
            'items.*.harga'      => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $status = ($request->submit_action === 'process') ? 'completed' : 'pending';
            $totalTransaksi = 0;

            // 1. Create Header
            $penerimaan = PenerimaanBarang::create([
                'tipe_pengirim'      => $request->tipe_pengirim,
                'distributor_id'     => ($request->tipe_pengirim == 'distributor') ? $request->distributor_id : null,
                'supplier_id'        => ($request->tipe_pengirim == 'supplier') ? $request->supplier_id : null,
                'user_id'            => optional(Auth::user())->id,
                'tanggal_penerimaan' => $request->tanggal_penerimaan,
                'referensi'          => $request->referensi,
                'keterangan'         => $request->keterangan,
                'status'             => $status,
            ]);

            // 2. Process Details
            foreach ($request->items as $item) {
                $qty = $item['qty'];
                $harga = $item['harga'] ?? 0;
                $subtotal = $qty * $harga;
                $totalTransaksi += $subtotal;

                // Create Detail
                DetailPenerimaanBarang::create([
                    'penerimaan_id' => $penerimaan->id,
                    'produk_id'     => $item['produk_id'],
                    'qty'           => $qty,
                    'kondisi_id'    => $item['kondisi_id'],
                    'gudang_id'     => $item['gudang_id'],
                    'area_id'       => $item['area_id'],
                    'rak_id'        => $item['rak_id'],
                    'harga'         => $harga,
                    'subtotal'      => $subtotal,
                ]);

                // 3. Update Stock ONLY if status is completed
                if ($status === 'completed') {
                    $stok = Stok::where('produk_id', $item['produk_id'])
                        ->where('gudang_id', $item['gudang_id'])
                        ->where('area_id', $item['area_id'])
                        ->where('rak_id', $item['rak_id'])
                        ->where('kondisi_id', $item['kondisi_id'])
                        ->first();

                    if ($stok) {
                        $stok->quantity += $qty;
                        $stok->save();
                    } else {
                        Stok::create([
                            'produk_id'  => $item['produk_id'],
                            'gudang_id'  => $item['gudang_id'],
                            'area_id'    => $item['area_id'],
                            'rak_id'     => $item['rak_id'],
                            'kondisi_id' => $item['kondisi_id'],
                            'quantity'   => $qty,
                        ]);
                    }
                }
            }

            // 4. Financial Transaction (Expense)
            if ($status === 'completed' && $totalTransaksi > 0 && $request->sumber_id) {
                $kategori = \App\Models\KategoriKeuangan::firstOrCreate(
                    ['nama' => 'Pembelian Stok'],
                    ['jenis' => 'pengeluaran', 'deskripsi' => 'Otomatis dari Penerimaan Barang']
                );

                // Auto Generate Finance Transaction No
                $date = date('Ymd');
                $count = \App\Models\Keuangan::whereDate('created_at', today())->count() + 1;
                $noTrx = 'TRX-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

                \App\Models\Keuangan::create([
                    'no_transaksi'         => $noTrx,
                    'jenis_transaksi'      => 'pengeluaran',
                    'kategori_keuangan_id' => $kategori->id,
                    'sumber_id'            => $request->sumber_id,
                    'jumlah'               => $totalTransaksi,
                    'tanggal_transaksi'    => $request->tanggal_penerimaan,
                    'keterangan'           => 'Pembelian Stok Ref: ' . $penerimaan->no_penerimaan,
                    'status'               => 'approved',
                    'user_id'              => optional(Auth::user())->id,
                ]);

                // Deduct Balance
                $akun = \App\Models\SumberKeuangan::findOrFail($request->sumber_id);
                $akun->decrement('saldo', $totalTransaksi);
            }

            DB::commit();

            $msg = ($status === 'completed')
                ? 'Penerimaan barang berhasil diproses, stok bertambah, dan transaksi keuangan tercatat.'
                : 'Penerimaan barang berhasil disimpan sebagai DRAFT.';

            return redirect()->route('penerimaan-barang.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $penerimaan = PenerimaanBarang::with([
            'distributor',
            'user',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak',
            'details.kondisi'
        ])->findOrFail($id);

        return view('penerimaan_barang.show', compact('penerimaan'));
    }

    public function print($id)
    {
        $penerimaan = PenerimaanBarang::with([
            'distributor',
            'user',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak',
            'details.kondisi'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('penerimaan_barang.pdf', compact('penerimaan'));
        return $pdf->stream('Penerimaan-Barang-' . $penerimaan->no_penerimaan . '.pdf');
    }

    // Disable Edit/Update for now to maintain stock integrity simpler.
    // Real world often allows voiding/reversing instead of direct edit.

    public function destroy($id)
    {
        // For now, strict deletion without stock reversal logic for simplicity unless requested?
        // User asked for "Real World". Real world: Reverse stock on delete/void.

        try {
            DB::beginTransaction();
            $penerimaan = PenerimaanBarang::with('details')->findOrFail($id);

            // Reverse Stock
            foreach ($penerimaan->details as $detail) {
                $stok = Stok::where('produk_id', $detail->produk_id)
                    ->where('gudang_id', $detail->gudang_id)
                    ->where('area_id', $detail->area_id)
                    ->where('rak_id', $detail->rak_id)
                    ->where('kondisi_id', $detail->kondisi_id)
                    ->first();

                if ($stok) {
                    if ($stok->quantity < $detail->qty) {
                        throw new \Exception("Gagal menghapus! Stok barang {$detail->produk->name} saat ini kurang dari jumlah yang diterima dulu. Barang mungkin sudah terpakai.");
                    }
                    $stok->quantity -= $detail->qty;
                    $stok->save();
                } else {
                    // Weird case: Stock record missing but we are deleting receipt. 
                    // Just ignore or throw? Ignore is safer to allow deletion of bad data.
                }
            }

            $penerimaan->delete();
            DB::commit();
            return redirect()->route('penerimaan-barang.index')->with('success', 'Penerimaan barang dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
