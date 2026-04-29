<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Category;
use App\Models\MetodePembayaran;
use App\Models\JasaPengiriman;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Get all products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $products = Products::with(['category', 'uom'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status'  => 'success',
                'message' => 'Daftar produk berhasil diambil',
                'data'    => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data produk',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product detail by ID.
     */
    public function show($id)
    {
        try {
            $product = Products::with(['category', 'uom'])->findOrFail($id);

            return response()->json([
                'status'  => 'success',
                'data'    => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get all categories.
     */
    public function categories()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'data'   => $categories
        ]);
    }

    /**
     * Get active payment methods.
     */
    public function paymentMethods()
    {
        $methods = MetodePembayaran::where('status', true)->get();
        return response()->json([
            'status' => 'success',
            'data'   => $methods
        ]);
    }

    /**
     * Get active shipping services.
     */
    public function shippingServices()
    {
        $services = JasaPengiriman::where('status', true)->get();
        return response()->json([
            'status' => 'success',
            'data'   => $services
        ]);
    }
}
