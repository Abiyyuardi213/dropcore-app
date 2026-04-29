<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\TransaksiController;

Route::get('/produk', [ProdukController::class, 'index']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);
Route::get('/kategori', [ProdukController::class, 'categories']);

// Data Pendukung Transaksi
Route::get('/metode-pembayaran', [ProdukController::class, 'paymentMethods']);
Route::get('/jasa-pengiriman', [ProdukController::class, 'shippingServices']);

// Checkout
Route::post('/checkout', [TransaksiController::class, 'checkout']);
