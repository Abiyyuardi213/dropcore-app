<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\JasaPengiriman;
use App\Models\MetodePembayaran;

class OrderController extends Controller
{
    public function confirmationPage()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with(['product.uom'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('distributor.cart')->withErrors('Keranjang kosong.');
        }

        $shippingServices = JasaPengiriman::where('status', true)->get();
        $paymentMethods = MetodePembayaran::where('status', true)->get();

        // Calculate Total Weight
        $totalWeight = $cartItems->sum(function ($item) {
            return ($item->product->weight ?? 0) * $item->quantity;
        });

        // Calculate Subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('distributor.order.confirmation', compact('cartItems', 'shippingServices', 'paymentMethods', 'subtotal', 'totalWeight'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('distributor.cart')->withErrors('Keranjang belanja kosong.');
        }

        $request->validate([
            'shipping_address' => 'required|string|max:1000',
            'shipping_service_id' => 'required|exists:jasa_pengiriman,id',
            'payment_method_id' => 'required|exists:metode_pembayaran,id',
        ], [
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'shipping_service_id.required' => 'Pilih jasa pengiriman.',
            'payment_method_id.required' => 'Pilih metode pembayaran.',
        ]);

        try {
            DB::beginTransaction();

            // 1. Calculate Product Subtotal
            $productSubtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // 2. Calculate Tax (11% of Product Subtotal)
            $taxBase = $productSubtotal;
            $taxAmount = $taxBase * 0.11;

            // 3. Calculate Shipping Cost
            $shippingService = JasaPengiriman::findOrFail($request->shipping_service_id);
            $totalWeight = $cartItems->sum(function ($item) {
                return ($item->product->weight ?? 0) * $item->quantity;
            });
            // Minimum weight 1kg logic can be added if needed, or just raw calculation
            $finalWeight = max(1, ceil($totalWeight)); // Round up to nearest kg, min 1kg
            $shippingCost = $finalWeight * $shippingService->biaya_dasar;

            // 4. Final Total
            $finalTotal = $productSubtotal + $taxAmount + $shippingCost;

            $paymentMethod = MetodePembayaran::findOrFail($request->payment_method_id);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'tax_base' => $taxBase,
                'tax_amount' => $taxAmount,
                'total_price' => $finalTotal,
                'status' => 'waiting_payment',
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'shipping_provider' => $shippingService->nama . ' - ' . $shippingService->kode, // Store name
                'payment_method' => $paymentMethod->nama_bank . ' - ' . $paymentMethod->nomor_rekening, // Store name
            ]);

            // First pass: Validation checks for stock availability
            foreach ($cartItems as $cartItem) {
                if ($cartItem->product->total_stock < $cartItem->quantity) {
                    throw new \Exception("Stok produk '{$cartItem->product->name}' tidak mencukupi. Tersedia: {$cartItem->product->total_stock}");
                }
            }

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);

                // Deduct Stock
                $qtyNeeded = $item->quantity;
                $stokBatches = \App\Models\Stok::where('produk_id', $item->product_id)
                    ->where('quantity', '>', 0)
                    ->orderBy('created_at', 'asc') // FIFO
                    ->lockForUpdate()
                    ->get();

                foreach ($stokBatches as $stok) {
                    if ($qtyNeeded <= 0) break;

                    $take = min($stok->quantity, $qtyNeeded);

                    $stok->decrement('quantity', $take);
                    $qtyNeeded -= $take;

                    // Log Mutasi Stok (Keluar)
                    \App\Models\MutasiStok::createMutasi([
                        'produk_id' => $item->product_id,
                        'jenis_mutasi' => 'keluar',
                        'gudang_asal_id' => $stok->gudang_id,
                        'area_asal_id' => $stok->area_id,
                        'rak_asal_id' => $stok->rak_id,
                        'quantity' => $take,
                        'kondisi_id' => $stok->kondisi_id,
                        'referensi' => $order->order_number,
                        'keterangan' => 'Penjualan Distributor (Checkout)',
                        'user_id' => $user->id,
                    ]);
                }

                // Optional: Check if we successfully fulfilled the demand (paranoia check)
                if ($qtyNeeded > 0) {
                    // Should throw exception, but 'total_stock' check passed earlier.
                    // It means concurrency handling worked or stock disappeared.
                    throw new \Exception("Gagal mengalokasikan stok untuk '{$item->product->name}'. Silakan coba lagi.");
                }
            }

            // Clear Cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('distributor.order.payment', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    public function paymentPage($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'waiting_payment') {
            // If already paid/uploaded, redirect to success or details
            return redirect()->route('distributor.order.success', $order->id);
        }

        return view('distributor.order.payment', compact('order'));
    }

    public function processPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $order->order_number . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payment_proofs'), $filename);

            $order->update([
                'proof_of_payment' => $filename,
                'status' => 'waiting_confirmation'
            ]);

            return redirect()->route('distributor.order.success', $order->id)
                ->with('success', 'Bukti pembayaran berhasil diupload. Mohon tunggu konfirmasi admin.');
        }

        return back()->withErrors('Gagal mengupload bukti pembayaran.');
    }

    public function success($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('distributor.order.success', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->latest()->get();
        return view('distributor.order.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('distributor.order.show', compact('order'));
    }

    public function requestCancel(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if (!in_array($order->status, ['pending', 'confirmed', 'processing'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan saat ini.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string',
            'cancellation_note' => 'nullable|string'
        ]);

        $order->update([
            'status' => 'cancel_requested',
            'cancellation_reason' => $request->cancellation_reason,
            'cancellation_note' => $request->cancellation_note
        ]);

        return back()->with('success', 'Permintaan pembatalan telah dikirim dan menunggu konfirmasi admin.');
    }
}
