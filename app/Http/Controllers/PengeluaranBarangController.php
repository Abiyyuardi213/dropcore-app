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

            // Conditional Validation
            'distributor_id'      => 'nullable|required_if:tipe_penerima,distributor|exists:distributors,id',
            'nama_konsumen'       => 'nullable|required_if:tipe_penerima,konsumen|string',
            'telepon_konsumen'    => 'nullable|required_if:tipe_penerima,konsumen|string',
            'alamat_konsumen'     => 'nullable|required_if:tipe_penerima,konsumen|string',

            // Details
            'items'              => 'required|array|min:1',
            'items.*.produk_id'  => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.kondisi_id' => 'required|exists:kondisi_barang,id',
            'items.*.gudang_id'  => 'required|exists:gudang,id',
            'items.*.area_id'    => 'required|exists:area_gudang,id',
            'items.*.rak_id'     => 'required|exists:rak_gudang,id',
            'items.*.harga'      => 'nullable|numeric|min:0', // Optional selling price?
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Header
            $pengeluaran = PengeluaranBarang::create([
                'no_pengeluaran'     => PengeluaranBarang::generateNomorPengeluaran(), // Ensure fresh number
                'user_id'            => optional(Auth::user())->id,
                'tipe_penerima'      => $request->tipe_penerima,
                'distributor_id'     => $request->distributor_id,
                'nama_konsumen'      => $request->nama_konsumen,
                'telepon_konsumen'   => $request->telepon_konsumen,
                'alamat_konsumen'    => $request->alamat_konsumen,
                'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
                'referensi'          => $request->referensi,
                'keterangan'         => $request->keterangan,
                'status'             => 'completed',
            ]);

            // 2. Process Details & Deduct Stock
            foreach ($request->items as $item) {
                // Find Stock
                $stok = Stok::where('produk_id', $item['produk_id'])
                    ->where('gudang_id', $item['gudang_id'])
                    ->where('area_id', $item['area_id'])
                    ->where('rak_id', $item['rak_id'])
                    ->where('kondisi_id', $item['kondisi_id'])
                    ->lockForUpdate() // Lock to prevent concurrent race conditions
                    ->first();

                if (!$stok) {
                    throw new \Exception("Stok tidak ditemukan untuk produk ID: " . $item['produk_id'] . " di lokasi yang dipilih.");
                }

                if ($stok->quantity < $item['qty']) {
                    throw new \Exception("Stok tidak mencukupi untuk item (Stok: {$stok->quantity}, Minta: {$item['qty']})");
                }

                // Deduct Stock
                $stok->quantity -= $item['qty'];
                $stok->save();

                // Create Detail
                $qty = $item['qty'];
                $harga = $item['harga'] ?? 0;
                $subtotal = $qty * $harga;

                PengeluaranBarangDetail::create([
                    'pengeluaran_id' => $pengeluaran->id,
                    'produk_id'      => $item['produk_id'],
                    'stok_id'        => $stok->id, // Precise linking
                    'qty'            => $qty,
                    'harga'          => $harga,
                    'subtotal'       => $subtotal,
                    'kondisi_id'     => $item['kondisi_id'],
                    'gudang_id'      => $item['gudang_id'],
                    'area_id'        => $item['area_id'],
                    'rak_id'         => $item['rak_id'],
                ]);
            }

            DB::commit();

            return redirect()->route('pengeluaran-barang.index')
                ->with('success', 'Pengeluaran barang berhasil disimpan dan stok telah dikurangi.');
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

        return view('pengeluaran_barang.invoice', compact('pengeluaran'));
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
