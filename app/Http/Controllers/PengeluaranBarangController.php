<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranBarang;
use App\Models\PengeluaranBarangDetail;
use App\Models\Distributor;
use App\Models\Products;
use App\Models\Gudang;
use App\Models\KondisiBarang;
use App\Models\Stok;
use Illuminate\Http\Request;
use App\Services\InventoryPaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranBarangController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'tanggal_awal' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_akhir' => array_merge(
                ['nullable', 'date_format:Y-m-d'],
                $request->filled('tanggal_awal') ? ['after_or_equal:tanggal_awal'] : []
            ),
            'tipe_penerima' => ['nullable', 'in:distributor,konsumen'],
            'status' => ['nullable', 'in:pending,completed,canceled'],
        ]);

        $query = PengeluaranBarang::with(['distributor', 'user']);
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_pengeluaran', '>=', $filters['tanggal_awal']);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_pengeluaran', '<=', $filters['tanggal_akhir']);
        }
        foreach (['tipe_penerima', 'status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $filters[$field]);
            }
        }
        $data = $query->latest()->get();
        return view('pengeluaran_barang.index', compact('data'));
    }

    public function create()
    {
        $distributors = Distributor::orderBy('nama_distributor')->get();
        $products = Products::orderBy('name')->get();
        // Eager load structure for dynamic dropdowns
        $gudangs = Gudang::with('areas.raks')->orderBy('nama_gudang')->get();
        $kondisis = KondisiBarang::all();
        $sumberKeuangan = \App\Models\SumberKeuangan::where('is_active', true)->orderBy('nama_sumber', 'asc')->get();
        $no_pengeluaran = PengeluaranBarang::generateNomorPengeluaran();
        $auto_referensi = 'SJ-' . strtoupper(\Illuminate\Support\Str::random(6));

        return view('pengeluaran_barang.create', compact('distributors', 'products', 'gudangs', 'kondisis', 'no_pengeluaran', 'sumberKeuangan', 'auto_referensi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_penerima'       => 'required|in:distributor,konsumen',
            'tanggal_pengeluaran' => 'required|date',
            'referensi'           => 'nullable|string|max:100',
            'keterangan'          => 'nullable|string',
            'submit_action'       => 'required|in:draft,process',
            'payment_status' => 'required|in:unpaid,paid',
            'sumber_id' => ['nullable', \Illuminate\Validation\Rule::requiredIf($request->submit_action === 'process' && $request->payment_status === 'paid'), 'exists:sumber_keuangan,id'],

            // Conditional Validation
            'distributor_id'      => ['nullable', 'required_if:tipe_penerima,distributor', \Illuminate\Validation\Rule::exists(Distributor::class, 'id')],
            'nama_konsumen'       => 'nullable|required_if:tipe_penerima,konsumen|string',
            'telepon_konsumen'    => 'nullable|required_if:tipe_penerima,konsumen|string',
            'alamat_konsumen'     => 'nullable|required_if:tipe_penerima,konsumen|string',

            // Details
            'items'              => 'required|array|min:1',
            'items.*.produk_id'  => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.kondisi_id' => 'nullable|exists:kondisi_barang,id',
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
            $pengeluaran = PengeluaranBarang::create([
                'no_pengeluaran'     => PengeluaranBarang::generateNomorPengeluaran(),
                'user_id'            => optional(Auth::user())->id,
                'tipe_penerima'      => $request->tipe_penerima,
                'distributor_id'     => $request->tipe_penerima === 'distributor' ? $request->distributor_id : null,
                'nama_konsumen'      => $request->tipe_penerima === 'konsumen' ? $request->nama_konsumen : null,
                'telepon_konsumen'   => $request->tipe_penerima === 'konsumen' ? $request->telepon_konsumen : null,
                'alamat_konsumen'    => $request->tipe_penerima === 'konsumen' ? $request->alamat_konsumen : null,
                'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
                'referensi'          => $request->referensi ?: ('SJ-' . strtoupper(\Illuminate\Support\Str::random(6))),
                'keterangan'         => $request->keterangan,
                'status'             => $status,
            ]);

            // 2. Process Details
            foreach ($request->items as $item) {
                $inventory->validateLocation($item);
                $qty = $item['qty'];
                $kondisiId = !empty($item['kondisi_id']) ? $item['kondisi_id'] : null;

                // Validate Stock Exists & Enough (Only strictly needed if processing, but good to check generally exists)
                $stok = Stok::where('produk_id', $item['produk_id'])
                    ->where('gudang_id', $item['gudang_id'])
                    ->where('area_id', $item['area_id'])
                    ->where('rak_id', $item['rak_id'])
                    ->where('kondisi_id', $kondisiId)
                    ->first();

                if (!$stok) {
                    // Check if just draft, maybe allow? But better strictly validate location.
                    throw new \Exception("Stok tidak ditemukan untuk produk ID: " . $item['produk_id']);
                }

                // If processing, check quantity sufficiency
                if ($status === 'completed') {
                    // Lock for update to be safe
                    $stok = Stok::where('id', $stok->id)->lockForUpdate()->first();
                    if ($stok->quantity < $qty) {
                        throw new \Exception("Stok tidak mencukupi untuk item (Stok: {$stok->quantity}, Minta: {$qty})");
                    }
                }

                $harga = round((float) $item['harga'], 2);
                $subtotal = round($qty * $harga, 2);
                $totalTransaksi += $subtotal;

                PengeluaranBarangDetail::create([
                    'pengeluaran_id' => $pengeluaran->id,
                    'produk_id'      => $item['produk_id'],
                    'stok_id'        => $stok->id,
                    'qty'            => $qty,
                    'harga'          => $harga,
                    'subtotal'       => $subtotal,
                    'kondisi_id'     => $kondisiId,
                    'gudang_id'      => $item['gudang_id'],
                    'area_id'        => $item['area_id'],
                    'rak_id'         => $item['rak_id'],
                ]);

                // 3. Deduct Stock if Completed
                if ($status === 'completed') {
                    $stok->quantity -= $qty;
                    $stok->save();
                }
            }

            if ($status === 'completed' && $request->payment_status === 'paid') {
                $inventory->pay($pengeluaran, $request->sumber_id, $request->tanggal_pengeluaran);
            }

            DB::commit();

            $msg = ($status === 'completed')
                ? 'Pengeluaran barang berhasil diproses dan stok telah dikurangi.'
                : 'Pengeluaran barang berhasil disimpan sebagai DRAFT (Stok belum dikurangi).';

            return redirect()->route('pengeluaran-barang.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengeluaran: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $pengeluaran = PengeluaranBarang::with([
            'distributor',
            'user',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak',
            'details.kondisi'
        ])->findOrFail($id);

        $sumberKeuangan = \App\Models\SumberKeuangan::where('is_active', true)->orderBy('nama_sumber')->get();
        return view('pengeluaran_barang.show', compact('pengeluaran', 'sumberKeuangan'));
    }

    public function print($id)
    {
        $pengeluaran = PengeluaranBarang::with([
            'distributor',
            'user',
            'details.produk',
            'details.gudang',
            'details.area',
            'details.rak',
            'details.kondisi'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pengeluaran_barang.pdf', compact('pengeluaran'))->setPaper('a4', 'portrait');
        return $pdf->stream('Surat-Jalan-' . $pengeluaran->no_pengeluaran . '.pdf');
    }

    public function destroy($id)
    {
        try {
            app(InventoryPaymentService::class)->cancel(PengeluaranBarang::findOrFail($id));
            return redirect()->route('pengeluaran-barang.index')->with('success', 'Transaksi dibatalkan. Perubahan stok dan pembayaran telah dibalik; draft tidak mengubah stok atau saldo.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'sumber_id' => 'required|exists:sumber_keuangan,id',
            'tanggal_pembayaran' => 'required|date|after_or_equal:' . PengeluaranBarang::findOrFail($id)->tanggal_pengeluaran,
        ]);
        try {
            app(InventoryPaymentService::class)->pay(PengeluaranBarang::findOrFail($id), $request->sumber_id, $request->tanggal_pembayaran);
            return back()->with('success', 'Pelunasan berhasil dicatat dan saldo akun diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function generatePDF($id)
    {
        // Legacy support if needed, or redirect to print
        return $this->print($id);
    }
}
