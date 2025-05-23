<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardGudangController;
use App\Http\Controllers\DashboardProdukController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\AreaGudangController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\StokController;
use Whoops\Run;

Route::get('/', function () {
    return view('welcome');
});
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('role/{id}/toggle-status', [RoleController::class, 'toggleStatus'])->name('role.toggleStatus');
Route::resource('role', RoleController::class);
Route::resource('user', UserController::class);
Route::resource('category', CategoryController::class);
Route::resource('product', ProductController::class);
Route::post('gudang/{id}/toggle-status', [GudangController::class, 'toggleStatus'])->name('gudang.toggleStatus');
Route::resource('gudang', GudangController::class);
Route::post('areaGudang/{id}/toggle-status', [AreaGudangController::class, 'toggleStatus'])->name('areaGudang.toggleStatus');
Route::resource('areaGudang', AreaGudangController::class);
Route::get('dashboardGudang', [DashboardGudangController::class, 'index'])->name('dashboardGudang');
Route::post('rak-gudang/{id}/toggle-status', [RakController::class, 'toggleStatus'])->name('rak-gudang.toggleStatus');
Route::resource('rak-gudang', RakController::class);
Route::get('dashboardProduk', [DashboardProdukController::class, 'index'])->name('dashboardProduk');
Route::resource('stok', StokController::class);
