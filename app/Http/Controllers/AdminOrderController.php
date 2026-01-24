<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        $rules = [
            'status' => 'required|in:pending,waiting_payment,waiting_confirmation,processing,shipped,completed,cancelled,cancel_requested'
        ];

        if ($request->status == 'shipped') {
            $rules['shipping_provider'] = 'required|string';
            $rules['tracking_number'] = 'required|string';
        }

        $request->validate($rules);

        $data = ['status' => $request->status];

        if ($request->filled('shipping_provider')) {
            $data['shipping_provider'] = $request->shipping_provider;
        }

        if ($request->filled('tracking_number')) {
            $data['tracking_number'] = $request->tracking_number;
        }

        if ($request->status == 'shipped' && !$order->shipped_at) {
            $data['shipped_at'] = now();
        }

        // --- MODULE INTEGRATION LOGIC ---

        // 1. Finance Integration (When Moving to Processing/Completed from predefined states)
        // Trigger: Status becomes 'processing' (Payment Accepted)
        // 1. Finance Integration (When Moving to Processing/Completed from predefined states)
        // Trigger: Status becomes 'processing' OR 'shipped' OR 'completed' (verified)
        if (in_array($request->status, ['processing', 'shipped', 'completed'])) {
            // We rely on the internal check inside createFinanceRecord to prevent duplicates
            $this->createFinanceRecord($order);
        }

        // 2. Goods Issue Integration (When Moving to Shipped/Completed)
        // Trigger: Status becomes 'shipped' or 'completed'
        // We ensure we haven't created it yet
        if (($request->status == 'shipped' || $request->status == 'completed')
            && !in_array($oldStatus, ['shipped', 'completed'])
        ) {
            $this->createPengeluaranBarang($order);
        }

        // 3. Stock Restoration (Cancellation)
        if ($request->status == 'cancelled' && $order->status != 'cancelled') {
            $this->restoreStock($order);
        }

        $order->update($data);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    private function createFinanceRecord($order)
    {
        // Check if exists
        $exists = \App\Models\Keuangan::where('keterangan', 'LIKE', "%Ref: {$order->order_number}%")->exists();
        if ($exists) return;

        // Get Default Cash Source (First Active)
        $sumber = \App\Models\SumberKeuangan::where('is_active', true)->first();
        if (!$sumber) return; // Cannot process if no source

        // Get or Create Category
        $kategori = \App\Models\KategoriKeuangan::firstOrCreate(
            ['nama' => 'Penjualan Distributor'],
            ['jenis' => 'pemasukkan', 'deskripsi' => 'Otomatis dari Pesanan Distributor']
        );

        $date = date('Ymd');
        $count = \App\Models\Keuangan::whereDate('created_at', today())->count() + 1;
        $noTrx = 'TRX-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        \App\Models\Keuangan::create([
            'no_transaksi'         => $noTrx,
            'jenis_transaksi'      => 'pemasukkan',
            'kategori_keuangan_id' => $kategori->id,
            'sumber_id'            => $sumber->id,
            'jumlah'               => $order->total_price,
            'tanggal_transaksi'    => now(),
            'keterangan'           => 'Penjualan Distributor Ref: ' . $order->order_number,
            'status'               => 'approved',
            'user_id'              => Auth::id(),
        ]);

        // Update Balance
        $sumber->increment('saldo', $order->total_price);
    }

    private function createPengeluaranBarang($order)
    {
        // Check if exists
        $exists = \App\Models\PengeluaranBarang::where('referensi', $order->order_number)->exists();
        if ($exists) return;

        // Create Header
        $pengeluaran = \App\Models\PengeluaranBarang::create([
            'no_pengeluaran'     => \App\Models\PengeluaranBarang::generateNomorPengeluaran(),
            'user_id'            => Auth::id(),
            'tipe_penerima'      => 'distributor',
            'distributor_id'     => $order->user->distributor_id ?? null, // Assuming relation exists or null
            'nama_konsumen'      => $order->shipping_provider, // Use shipping info or fallback
            'alamat_konsumen'    => $order->shipping_address,
            'tanggal_pengeluaran' => now(),
            'referensi'          => $order->order_number,
            'keterangan'         => 'Otomatis dari Order Distributor',
            'status'             => 'completed', // Completed because stock is already gone
        ]);

        // Create Details based on Mutation Log (Real Batches)
        // We find the mutations that happened when Order was placed (or checkout)
        // Checkout created mutations with referensi = order_number and jenis = keluar
        $mutations = \App\Models\MutasiStok::where('referensi', $order->order_number)
            ->where('jenis_mutasi', 'keluar')
            ->get();

        if ($mutations->isNotEmpty()) {
            foreach ($mutations as $mutasi) {
                // Find matching Stok ID to link (Even if qty is 0)
                $stok = \App\Models\Stok::where('produk_id', $mutasi->produk_id)
                    ->where('gudang_id', $mutasi->gudang_asal_id)
                    ->where('area_id', $mutasi->area_asal_id)
                    ->where('rak_id', $mutasi->rak_asal_id)
                    ->where('kondisi_id', $mutasi->kondisi_id)
                    ->first();

                // If stok record completely deleted (rare), we might skip or handle error. 
                // But usually it persists with logic.
                if ($stok) {
                    \App\Models\PengeluaranBarangDetail::create([
                        'pengeluaran_id' => $pengeluaran->id,
                        'produk_id'      => $mutasi->produk_id,
                        'stok_id'        => $stok->id,
                        'qty'            => $mutasi->quantity,
                        'harga'          => 0, // Or fetch product price if needed, but usually COGS or Selling Price? Let's use 0 or Selling if available. 
                        // OrderItem has price. We can try to match product_id to OrderItem price.
                        'subtotal'       => 0,
                        'kondisi_id'     => $mutasi->kondisi_id,
                        'gudang_id'      => $mutasi->gudang_asal_id,
                        'area_id'        => $mutasi->area_asal_id,
                        'rak_id'         => $mutasi->rak_asal_id,
                    ]);
                }
            }
        }
    }

    private function restoreStock($order)
    {
        // 1. Try to find mutations linked to this order
        $mutations = \App\Models\MutasiStok::where('referensi', $order->order_number)
            ->where('jenis_mutasi', 'keluar')
            ->get();

        if ($mutations->isNotEmpty()) {
            foreach ($mutations as $mutasi) {
                // Restore to original location
                $stok = \App\Models\Stok::where('produk_id', $mutasi->produk_id)
                    ->where('gudang_id', $mutasi->gudang_asal_id)
                    ->where('area_id', $mutasi->area_asal_id)
                    ->where('rak_id', $mutasi->rak_asal_id)
                    ->where('kondisi_id', $mutasi->kondisi_id)
                    ->first();

                if ($stok) {
                    $stok->increment('quantity', $mutasi->quantity);
                } else {
                    // Re-create stock record if missing
                    \App\Models\Stok::create([
                        'produk_id' => $mutasi->produk_id,
                        'gudang_id' => $mutasi->gudang_asal_id,
                        'area_id' => $mutasi->area_asal_id,
                        'rak_id' => $mutasi->rak_asal_id,
                        'quantity' => $mutasi->quantity,
                        'kondisi_id' => $mutasi->kondisi_id,
                    ]);
                }

                // Log 'Masuk' mutation (Return)
                \App\Models\MutasiStok::createMutasi([
                    'produk_id' => $mutasi->produk_id,
                    'jenis_mutasi' => 'masuk',
                    'gudang_tujuan_id' => $mutasi->gudang_asal_id,
                    'area_tujuan_id' => $mutasi->area_asal_id,
                    'rak_tujuan_id' => $mutasi->rak_asal_id,
                    'quantity' => $mutasi->quantity,
                    'kondisi_id' => $mutasi->kondisi_id,
                    'referensi' => $order->order_number,
                    'keterangan' => 'Pembatalan Pesanan (Restock)',
                    'user_id' => Auth::id(),
                ]);
            }
        } else {
            // Fallback for orders without mutation history
            foreach ($order->items as $item) {
                // Try to find ANY stock record to increment
                $stok = \App\Models\Stok::where('produk_id', $item->product_id)->orderBy('quantity', 'desc')->first();
                if ($stok) {
                    $stok->increment('quantity', $item->quantity);

                    \App\Models\MutasiStok::createMutasi([
                        'produk_id' => $item->product_id,
                        'jenis_mutasi' => 'masuk',
                        'gudang_tujuan_id' => $stok->gudang_id,
                        'area_tujuan_id' => $stok->area_id,
                        'rak_tujuan_id' => $stok->rak_id,
                        'quantity' => $item->quantity,
                        'kondisi_id' => $stok->kondisi_id,
                        'referensi' => $order->order_number,
                        'keterangan' => 'Pembatalan Pesanan (Legacy fallback)',
                        'user_id' => Auth::id(),
                    ]);
                }
            }
        }
    }
    public function invoice($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        // Generate pseudo invoice number if not exists
        if (!$order->tax_invoice_number) {
            $year = date('Y');
            $order->update([
                'tax_invoice_number' => '010.000-' . $year . '.' . str_pad($order->id, 8, '0', STR_PAD_LEFT) // Dummy pattern
            ]);
        }

        return view('admin.orders.invoice', compact('order'));
    }
}
