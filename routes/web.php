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
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\MutasiStokController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardMasterController;
use App\Http\Controllers\KondisiBarangController;
use App\Http\Controllers\SupplierController;
use Whoops\Run;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
    
    Route::get('homepage', [HomepageController::class, 'index'])->name('homepage');

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

    Route::get('/stok/produk/{produk_id}', [MutasiStokController::class, 'getStokByProduk']);
    Route::get('/lokasi-asal-produk/{produk_id}', [MutasiStokController::class, 'lokasiAsalProduk']);
    Route::resource('mutasi-stok', MutasiStokController::class);

    Route::post('wilayah/{id}/toggle-status', [WilayahController::class, 'toggleStatus'])->name('wilayah.toggleStatus');
    Route::resource('wilayah', WilayahController::class);

    Route::post('provinsi/{id}/toggle-status', [ProvinsiController::class, 'toggleStatus'])->name('provinsi.toggleStatus');
    Route::resource('provinsi', ProvinsiController::class);

    Route::resource('kota', KotaController::class);

    Route::resource('kecamatan', KecamatanController::class);

    Route::resource('kelurahan', KelurahanController::class);

    Route::post('supplier/{id}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('role.toggleStatus');
    Route::resource('supplier', SupplierController::class);

    Route::get('dashboard-master', [DashboardMasterController::class, 'index'])->name('dashboard-master');

    Route::resource('kondisi-barang', KondisiBarangController::class);
});


