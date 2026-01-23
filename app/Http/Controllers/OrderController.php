<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('distributor.cart')->withErrors('Keranjang belanja kosong.');
        }

        try {
            DB::beginTransaction();

            $totalPrice = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $request->validate([
                'shipping_address' => 'required|string|max:1000',
            ], [
                'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            ]);

            // Tax calculation (11%)
            $taxBase = $totalPrice; // DPP is the sum of product prices
            $taxAmount = $taxBase * 0.11; // PPN 11%
            $finalTotal = $taxBase + $taxAmount;

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'tax_base' => $taxBase,
                'tax_amount' => $taxAmount,
                'total_price' => $finalTotal,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
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
                    ->get();

                foreach ($stokBatches as $stok) {
                    if ($qtyNeeded <= 0) break;

                    if ($stok->quantity >= $qtyNeeded) {
                        $stok->decrement('quantity', $qtyNeeded);
                        $qtyNeeded = 0;
                    } else {
                        $qtyNeeded -= $stok->quantity;
                        $stok->update(['quantity' => 0]);
                    }
                }
            }

            // Clear Cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('distributor.order.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
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
        $orders = Order::where('user_id', Auth::id())->latest()->get();
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
}
