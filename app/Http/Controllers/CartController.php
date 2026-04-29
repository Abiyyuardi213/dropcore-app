<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product.category', 'product.uom'])
            ->get();

        return view('distributor.cart', compact('cartItems'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        // Check stock availability
        $product = Products::withSum('stok', 'quantity')->find($productId);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
        }

        $availableStock = $product->stok_sum_quantity ?? 0;

        // Check existing cart quantity
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        $currentCartQuantity = $cartItem ? $cartItem->quantity : 0;

        if (($currentCartQuantity + $quantity) > $availableStock) {
            return response()->json(['error' => 'Stok tidak mencukupi. Sisa stok: ' . $availableStock], 400);
        }

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        $cartItems = Cart::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        $miniCartHtml = view('include.mini-cart-list', compact('cartItems'))->render();

        return response()->json([
            'success' => 'Produk berhasil ditambahkan ke keranjang.',
            'cart_count' => Cart::where('user_id', $user->id)->count(),
            'mini_cart_html' => $miniCartHtml
        ]);
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Products::withSum('stok', 'quantity')->find($cartItem->product_id);
        $availableStock = $product->stok_sum_quantity ?? 0;

        if ($request->quantity > $availableStock) {
            return back()->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $availableStock);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
