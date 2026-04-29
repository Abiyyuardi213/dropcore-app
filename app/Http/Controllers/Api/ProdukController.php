<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Products;
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
}
