<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Stok;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardProdukController extends Controller
{
    public function index()
    {
        $totalProduk = Products::count();
        $totalStok = Stok::sum('quantity');

        // Products by Category
        $produkPerKategori = Category::withCount('products')->get();

        // Low Stock Products (using the accessor or raw query if faster, accessor is safer but slower on large datasets)
        // Optimizing by eager loading stok and filtering in memory for small datasets, 
        // or using a subquery for larger ones. Here we iterate as in DashboardController.
        $lowStockProducts = Products::with('stok')
            ->get()
            ->filter(function ($product) {
                return $product->total_stock <= $product->min_stock;
            })
            ->take(5);

        // Top 5 Most Expensive Products
        $expensiveProducts = Products::orderBy('price', 'desc')->take(5)->get();

        return view('dashboardProduk', compact(
            'totalProduk',
            'totalStok',
            'produkPerKategori',
            'lowStockProducts',
            'expensiveProducts'
        ));
    }
}
