<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;

Route::get('/produk', [ProdukController::class, 'index']);
