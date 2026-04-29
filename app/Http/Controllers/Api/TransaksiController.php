<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use App\Models\JasaPengiriman;
use App\Models\MetodePembayaran;
use App\Models\Stok;
use App\Models\MutasiStok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Str;

class TransaksiController extends Controller
{
    /**
     * Checkout API for testing (no auth required for now).
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'items'               => 'required|array|min:1',
            'items.*.product_id'  => 'required|exists:products,id',
            'items.*.quantity'    => 'required|integer|min:1',
            'shipping_address'    => 'required|string',
            'shipping_service_id' => 'required|exists:jasa_pengiriman,id',
            'payment_method_id'   => 'required|exists:metode_pembayaran,id',
            'notes'               => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // For testing, use the first user if not provided or just hardcode one
            $user = User::first(); 
            if (!$user) {
                throw new \Exception("User tidak ditemukan untuk testing");
            }

            $productSubtotal = 0;
            $totalWeight = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Products::findOrFail($item['product_id']);
                
                if ($product->total_stock < $item['quantity']) {
                    throw new \Exception("Stok produk '{$product->name}' tidak mencukupi. Tersedia: {$product->total_stock}");
                }

                $subtotal = $product->price * $item['quantity'];
                $productSubtotal += $subtotal;
                $totalWeight += ($product->weight ?? 0) * $item['quantity'];

                $itemsData[] = [
                    'product'  => $product,
                    'quantity' => $item['quantity'],
                    'price'    => $product->price,
                    'subtotal' => $subtotal
                ];
            }

            $taxBase = $productSubtotal;
            $taxAmount = $taxBase * 0.11; // 11% PPN

            $shippingService = JasaPengiriman::findOrFail($request->shipping_service_id);
            $finalWeight = max(1, ceil($totalWeight));
            $shippingCost = $finalWeight * $shippingService->biaya_dasar;

            $finalTotal = $productSubtotal + $taxAmount + $shippingCost;

            $paymentMethod = MetodePembayaran::findOrFail($request->payment_method_id);

            $order = Order::create([
                'user_id'           => $user->id,
                'order_number'      => 'ORD-API-' . strtoupper(Str::random(10)),
                'tax_base'          => $taxBase,
                'tax_amount'        => $taxAmount,
                'total_price'       => $finalTotal,
                'status'            => 'waiting_payment',
                'shipping_address'  => $request->shipping_address,
                'notes'             => $request->notes,
                'shipping_provider' => $shippingService->nama . ' - ' . $shippingService->kode,
                'payment_method'    => $paymentMethod->nama_bank . ' - ' . $paymentMethod->nomor_rekening,
            ]);

            foreach ($itemsData as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['subtotal'],
                ]);

                // Deduct Stock Logic (FIFO)
                $qtyNeeded = $item['quantity'];
                $stokBatches = Stok::where('produk_id', $item['product']->id)
                    ->where('quantity', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->lockForUpdate()
                    ->get();

                foreach ($stokBatches as $stok) {
                    if ($qtyNeeded <= 0) break;

                    $take = min($stok->quantity, $qtyNeeded);
                    $stok->decrement('quantity', $take);
                    $qtyNeeded -= $take;

                    MutasiStok::createMutasi([
                        'produk_id'      => $item['product']->id,
                        'jenis_mutasi'   => 'keluar',
                        'gudang_asal_id' => $stok->gudang_id,
                        'area_asal_id'   => $stok->area_id,
                        'rak_asal_id'    => $stok->rak_id,
                        'quantity'       => $take,
                        'kondisi_id'     => $stok->kondisi_id,
                        'referensi'      => $order->order_number,
                        'keterangan'     => 'Penjualan Mobile API (Testing)',
                        'user_id'        => $user->id,
                    ]);
                }

                if ($qtyNeeded > 0) {
                    throw new \Exception("Gagal mengalokasikan stok untuk '{$item['product']->name}'.");
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pesanan berhasil dibuat',
                'data'    => [
                    'order_id'     => $order->id,
                    'order_number' => $order->order_number,
                    'total_price'  => $order->total_price
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
