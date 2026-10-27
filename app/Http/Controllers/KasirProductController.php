<?php

namespace App\Http\Controllers;

use App\Models\{Category, Products};
use Illuminate\Http\Request;

class KasirProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'exists:product_category,id'],
            'stock' => ['nullable', 'in:available,empty'],
        ]);
        $query = Products::select(['id', 'sku', 'name', 'merk', 'price', 'category_id', 'uom_id', 'image'])
            ->with(['category:id,category_name', 'uom:id,name'])->withSum('stok', 'quantity');
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(fn ($q) => $q->where('name', 'like', '%'.$search.'%')->orWhere('sku', 'like', '%'.$search.'%')->orWhere('merk', 'like', '%'.$search.'%'));
        }
        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('stock')) {
            $query->whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM stok WHERE stok.produk_id = products.id) '.($request->stock === 'available' ? '> 0' : '<= 0'));
        }
        $products = $query->orderBy('name')->orderBy('id')->paginate(15)->withQueryString();
        $categories = Category::orderBy('category_name')->get(['id', 'category_name']);
        return view('kasir.produk', compact('products', 'categories'));
    }
}
