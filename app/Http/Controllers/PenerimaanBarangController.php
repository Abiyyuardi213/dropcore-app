<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBarang;
use App\Models\DetailPenerimaanBarang;
use App\Models\Products;
use App\Models\Gudang;
use App\Models\KondisiBarang;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\InventoryPaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PenerimaanBarangController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'tanggal_awal' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_akhir' => array_merge(
                ['nullable', 'date_format:Y-m-d'],
                $request->filled('tanggal_awal') ? ['after_or_equal:tanggal_awal'] : []
            ),
            'tipe_pengirim' => ['nullable', 'in:supplier,distributor'],
            'status' => ['nullable', 'in:pending,completed,canceled'],
        ]);

        $query = PenerimaanBarang::with(['distributor', 'supplier', 'user']);
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_penerimaan', '>=', $filters['tanggal_awal']);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_penerimaan', '<=', $filters['tanggal_akhir']);
        }
        foreach (['tipe_pengirim', 'status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $filters[$field]);
            }
        }
        $data = $query->latest()->get();
        return view('penerimaan_barang.index', compact('data'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $products = Products::orderBy('name')->get();
        $gudangs = Gudang::with('areas.raks')->orderBy('nama_gudang')->get();
        $kondisis = KondisiBarang::all();
        $sumberKeuangan = \App\Models\SumberKeuangan::where('is_active', true)->orderBy('nama_sumber', 'asc')->get();
        $no_penerimaan = PenerimaanBarang::generateNomorPenerimaan();
        $auto_referensi = 'RF-' . strtoupper(\Illuminate\Support\Str::random(6));

        return view('penerimaan_barang.create', compact('suppliers', 'products', 'gudangs', 'kondisis', 'no_penerimaan', 'sumberKeuangan', 'auto_referensi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_pengirim'      => 'required|in:supplier',
            'supplier_id'        => 'required|exists:suppliers,id',
            'tanggal_penerimaan' => 'required|date',
            'referensi'          => 'nullable|string|max:100',
            'keterangan'         => 'nullable|string',
            'submit_action'      => 'required|in:draft,process',
            'payment_status' => 'required|in:unpaid,paid',
            'sumber_id' => ['nullable', \Illuminate\Validation\Rule::requiredIf($request->submit_action === 'process' && $request->payment_status === 'paid'), 'exists:sumber_keuangan,id'],

            'items'              => 'required|array|min:1',
            'items.*.produk_id'  => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.kondisi_id' => 'required|exists:kondisi_barang,id',
            'items.*.gudang_id'  => 'required|exists:gudang,id',
            'items.*.area_id'    => 'required|exists:area_gudang,id',
            'items.*.rak_id'     => 'required|exists:rak_gudang,id',
            'items.*.harga'      => 'required|numeric|min:0|decimal:0,2',
        ]);

        try {
            DB::beginTransaction();
            $inventory = app(InventoryPaymentService::class);
            $inventory->lockProducts($request->items);

            $status = ($request->submit_action === 'process') ? 'completed' : 'pending';
            $totalTransaksi = 0;

            // 1. Create Header
            $penerimaan = PenerimaanBarang::create([
                'tipe_pengirim'      => 'supplier',
                'distributor_id'     => null,
                'supplier_id'        => $request->supplier_id,
                'user_id'            => optional(Auth::user())->id,
                'tanggal_penerimaan' => $request->tanggal_penerimaan,
                'referensi'          => $request->referensi ?: ('RF-' . strtoupper(\Illuminate\Support\Str::random(6))),
                'keterangan'         => $request->keterangan,
                'status'             => $status,
            ]);

            // 2. Process Details
            foreach ($request->items as $item) {
                $inventory->validateLocation($item);
                $qty = $item['qty'];
                $harga = round((float) $item['harga'], 2);
                $subtotal = round($qty * $harga, 2);
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
                        ->lockForUpdate()->first();

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

            // Calculate Tax
            $ppnPercentage = 10;
            $ppnAmount = round($totalTransaksi * ($ppnPercentage / 100), 2);
            $totalWithTax = $totalTransaksi + $ppnAmount;

            // Update Header with Financials
            $penerimaan->update([
                'subtotal' => $totalTransaksi,
                'ppn_percentage' => $ppnPercentage,
                'ppn_amount' => $ppnAmount,
                'total_amount' => $totalWithTax
            ]);

            if ($status === 'completed' && $request->payment_status === 'paid') {
                $inventory->pay($penerimaan, $request->sumber_id, $request->tanggal_penerimaan);
            }

            DB::commit();

            $msg = ($status === 'completed')
                ? 'Penerimaan barang diproses dan stok bertambah. Pembayaran mengikuti status yang dipilih.'
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
            'supplier',
            'user',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak',
            'details.kondisi'
        ])->findOrFail($id);

        $sumberKeuangan = \App\Models\SumberKeuangan::where('is_active', true)->orderBy('nama_sumber')->get();
        return view('penerimaan_barang.show', compact('penerimaan', 'sumberKeuangan'));
    }

    public function print($id)
    {
        $penerimaan = PenerimaanBarang::with([
            'distributor',
            'supplier',
            'user',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak',
            'details.kondisi'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('penerimaan_barang.pdf', compact('penerimaan'))->setPaper('a4', 'portrait');
        return $pdf->stream('Penerimaan-Barang-' . $penerimaan->no_penerimaan . '.pdf');
    }

    // Disable Edit/Update for now to maintain stock integrity simpler.
    // Real world often allows voiding/reversing instead of direct edit.

    public function destroy($id)
    {
        try {
            app(InventoryPaymentService::class)->cancel(PenerimaanBarang::findOrFail($id));
            return redirect()->route('penerimaan-barang.index')->with('success', 'Transaksi dibatalkan. Perubahan stok dan pembayaran telah dibalik; draft tidak mengubah stok atau saldo.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'sumber_id' => 'required|exists:sumber_keuangan,id',
            'tanggal_pembayaran' => 'required|date|after_or_equal:' . PenerimaanBarang::findOrFail($id)->tanggal_penerimaan,
        ]);
        try {
            app(InventoryPaymentService::class)->pay(PenerimaanBarang::findOrFail($id), $request->sumber_id, $request->tanggal_pembayaran);
            return back()->with('success', 'Pelunasan berhasil dicatat dan saldo akun diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
