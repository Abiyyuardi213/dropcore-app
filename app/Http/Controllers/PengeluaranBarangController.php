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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranBarangController extends Controller
{
    public function index()
    {
        $data = PengeluaranBarang::with(['distributor', 'user'])->latest()->get();
        return view('pengeluaran_barang.index', compact('data'));
    }

    public function create()
    {
        $distributors = Distributor::orderBy('nama_distributor')->get();
        $products = Products::orderBy('name')->get();
        // Eager load structure for dynamic dropdowns
        $gudangs = Gudang::with('areas.raks')->orderBy('nama_gudang')->get();
        $kondisis = KondisiBarang::all();
        $no_pengeluaran = PengeluaranBarang::generateNomorPengeluaran();

        return view('pengeluaran_barang.create', compact('distributors', 'products', 'gudangs', 'kondisis', 'no_pengeluaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_penerima'       => 'required|in:distributor,konsumen',
            'tanggal_pengeluaran' => 'required|date',
            'referensi'           => 'nullable|string|max:100',
            'keterangan'          => 'nullable|string',
            'submit_action'       => 'required|in:draft,process',

            // Conditional Validation
            'distributor_id'      => 'nullable|required_if:tipe_penerima,distributor|exists:distributors,id',
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
            'items.*.harga'      => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $status = ($request->submit_action === 'process') ? 'completed' : 'pending';

            // 1. Create Header
            $pengeluaran = PengeluaranBarang::create([
                'no_pengeluaran'     => PengeluaranBarang::generateNomorPengeluaran(),
                'user_id'            => optional(Auth::user())->id,
                'tipe_penerima'      => $request->tipe_penerima,
                'distributor_id'     => $request->distributor_id,
                'nama_konsumen'      => $request->nama_konsumen,
                'telepon_konsumen'   => $request->telepon_konsumen,
                'alamat_konsumen'    => $request->alamat_konsumen,
                'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
                'referensi'          => $request->referensi,
                'keterangan'         => $request->keterangan,
                'status'             => $status,
            ]);

            // 2. Process Details
            foreach ($request->items as $item) {
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

                $harga = $item['harga'] ?? 0;
                $subtotal = $qty * $harga;

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

        return view('pengeluaran_barang.show', compact('pengeluaran'));
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

        $pdf = Pdf::loadView('pengeluaran_barang.pdf', compact('pengeluaran'));
        return $pdf->stream('Surat-Jalan-' . $pengeluaran->no_pengeluaran . '.pdf');
    }

    public function destroy($id)
    {
        // Cancel/Rollback Transaction
        try {
            DB::beginTransaction();
            $pengeluaran = PengeluaranBarang::with('details')->findOrFail($id);

            // Return Stock
            foreach ($pengeluaran->details as $detail) {
                $stok = Stok::where('produk_id', $detail->produk_id)
                    ->where('gudang_id', $detail->gudang_id)
                    ->where('area_id', $detail->area_id)
                    ->where('rak_id', $detail->rak_id)
                    ->where('kondisi_id', $detail->kondisi_id)
                    ->first();

                if ($stok) {
                    $stok->quantity += $detail->qty;
                    $stok->save();
                } else {
                    // If stock record gone (rare), recreate? Or just log? 
                    // Recreating is safer to ensure inventory value exists.
                    Stok::create([
                        'produk_id'  => $detail->produk_id,
                        'gudang_id'  => $detail->gudang_id,
                        'area_id'    => $detail->area_id,
                        'rak_id'     => $detail->rak_id,
                        'kondisi_id' => $detail->kondisi_id,
                        'quantity'   => $detail->qty,
                    ]);
                }
            }

            $pengeluaran->delete();
            DB::commit();
            return redirect()->route('pengeluaran-barang.index')->with('success', 'Pengeluaran barang dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function generatePDF($id)
    {
        // Legacy support if needed, or redirect to print
        return $this->print($id);
    }
}
