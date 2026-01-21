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
use App\Http\Controllers\DashboardKeuanganController;
use App\Http\Controllers\DashboardMasterController;

use App\Http\Controllers\DetailPenerimaanBarangController;
use App\Http\Controllers\DetailPengeluaranBarangController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\KasPusatController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\KondisiBarangController;
use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\PengeluaranBarangController;
use App\Http\Controllers\RiwayatAktivitasLogController;
use App\Http\Controllers\RiwayatAktivitasProdukController;
use App\Http\Controllers\SumberKeuanganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\CartController;
use Whoops\Run;

Route::get('/', function () {
    return redirect()->route('homepage');
});

Route::get('homepage', [HomepageController::class, 'index'])->name('homepage');
Route::get('produk', [HomepageController::class, 'products'])->name('customer.products');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/login-customer', function () {
    return view('auth.login-customer');
})->name('login.customer.form')->middleware('guest');

Route::post('/login-customer', [AuthController::class, 'loginCustomer'])
    ->name('login.customer');

Route::get('/register-customer', [AuthController::class, 'showRegisterForm'])
    ->name('register.customer.form')->middleware('guest');

Route::post('/register-customer', [AuthController::class, 'registerCustomer'])
    ->name('register.customer');

Route::middleware(['role:admin,staff'])->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

    Route::get('/pengguna/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/pengguna/profil', [UserController::class, 'updateProfil'])->name('user.profil.update');

    Route::post('role/{id}/toggle-status', [RoleController::class, 'toggleStatus'])->name('role.toggleStatus');
    Route::resource('role', RoleController::class);

    Route::resource('user', UserController::class);

    Route::resource('category', CategoryController::class);

    Route::resource('product', ProductController::class);

    Route::get('dashboard-keuangan', [DashboardKeuanganController::class, 'index'])->name('dashboard-keuangan');

    Route::get('/kas-pusat', [KasPusatController::class, 'index'])->name('kas-pusat.index');
    Route::post('/kas-pusat/transfer', [KasPusatController::class, 'storeTransfer'])->name('kas-pusat.transfer');
    Route::get('/kas-pusat/edit', [KasPusatController::class, 'edit'])->name('kas-pusat.edit');
    // Route::post('/kas-pusat/update', [KasPusatController::class, 'update'])->name('kas-pusat.update');
    Route::put('/kas-pusat/update/{id}', [KasPusatController::class, 'update'])->name('kas-pusat.update');

    Route::prefix('sumber-keuangan')->group(function () {
        Route::get('/', [SumberKeuanganController::class, 'index'])->name('sumber-keuangan.index');
        Route::get('/create', [SumberKeuanganController::class, 'create'])->name('sumber-keuangan.create');
        Route::post('/store', [SumberKeuanganController::class, 'store'])->name('sumber-keuangan.store');
        Route::get('/edit/{id}', [SumberKeuanganController::class, 'edit'])->name('sumber-keuangan.edit');
        Route::put('/update/{id}', [SumberKeuanganController::class, 'update'])->name('sumber-keuangan.update');
        Route::delete('/delete/{id}', [SumberKeuanganController::class, 'destroy'])->name('sumber-keuangan.destroy');
    });

    Route::prefix('keuangan')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('keuangan.index');
        Route::get('/create', [KeuanganController::class, 'create'])->name('keuangan.create');
        Route::post('/store', [KeuanganController::class, 'store'])->name('keuangan.store');
        Route::get('/edit/{id}', [KeuanganController::class, 'edit'])->name('keuangan.edit');
        Route::put('/update/{id}', [KeuanganController::class, 'update'])->name('keuangan.update');
        Route::match(['get', 'post', 'delete'], '/delete/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
        Route::get('/detail/{id}', [KeuanganController::class, 'show'])->name('keuangan.show');
        Route::get('/print/{id}', [KeuanganController::class, 'print'])->name('keuangan.print');
    });

    Route::post('gudang/{id}/toggle-status', [GudangController::class, 'toggleStatus'])->name('gudang.toggleStatus');
    Route::resource('gudang', GudangController::class);

    //Route::post('distributor/{id}/toggle-status', [DistributorController::class, 'toggleStatus'])->name('distributor.toggleStatus');
    Route::resource('distributor', DistributorController::class);

    Route::post('areaGudang/{id}/toggle-status', [AreaGudangController::class, 'toggleStatus'])->name('areaGudang.toggleStatus');
    Route::resource('areaGudang', AreaGudangController::class);

    Route::get('dashboardGudang', [DashboardGudangController::class, 'index'])->name('dashboardGudang');

    Route::post('rak-gudang/{id}/toggle-status', [RakController::class, 'toggleStatus'])->name('rak-gudang.toggleStatus');
    Route::resource('rak-gudang', RakController::class);

    Route::get('dashboardProduk', [DashboardProdukController::class, 'index'])->name('dashboardProduk');

    Route::post('/stok/update-kondisi/{id}', [StokController::class, 'updateKondisi']);
    Route::resource('stok', StokController::class);

    Route::get('/stok/by-produk/{produk_id}', [StokController::class, 'getStokByProduk']);

    Route::get('/stok/produk/{produk_id}', [MutasiStokController::class, 'getStokByProduk']);
    Route::get('/lokasi-asal-produk/{produk_id}', [MutasiStokController::class, 'lokasiAsalProduk']);
    Route::get('mutasi-stok/{id}/print', [MutasiStokController::class, 'print'])->name('mutasi-stok.print');
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

    Route::get('penerimaan-barang/{id}/pdf', [PenerimaanBarangController::class, 'generatePDF'])
        ->name('penerimaan-barang.pdf');

    Route::get('penerimaan-barang/{id}/print', [PenerimaanBarangController::class, 'print'])->name('penerimaan-barang.print');

    // We are unifying creation in one step, so we might not need separate detail controller access for now, 
    // but better keep the resource and maybe removing the old legacy 'detail' specific routes if they clash.
    // The previous implementation used a 2-step process (Header -> Redirect to Detail). 
    // New Implementation uses 1-step form. 
    // So 'penerimaan-barang.detail' might be obsolete or just alias to 'show'.

    Route::resource('penerimaan-barang', PenerimaanBarangController::class);

    Route::post(
        'detail-penerimaan/store',
        [DetailPenerimaanBarangController::class, 'store']
    )->name('detail-penerimaan.store');

    Route::delete(
        'detail-penerimaan/{id}',
        [DetailPenerimaanBarangController::class, 'destroy']
    )->name('detail-penerimaan.destroy');

    Route::get('pengeluaran-barang/{id}/pdf', [PengeluaranBarangController::class, 'generatePDF'])
        ->name('pengeluaran-barang.pdf');

    Route::get('pengeluaran-barang/{id}/print', [PengeluaranBarangController::class, 'print'])->name('pengeluaran-barang.print');

    Route::resource('pengeluaran-barang', PengeluaranBarangController::class);

    // Legacy details routes - can be deprecated or kept as fail-safe if old links exist
    Route::get(
        'pengeluaran-barang/{id}/detail',
        [DetailPengeluaranBarangController::class, 'index']
    )->name('pengeluaran-barang.detail');

    Route::post(
        'detail-pengeluaran/store',
        [DetailPengeluaranBarangController::class, 'store']
    )->name('detail-pengeluaran.store');

    Route::delete(
        'detail-pengeluaran/{id}',
        [DetailPengeluaranBarangController::class, 'destroy']
    )->name('detail-pengeluaran.destroy');

    Route::resource('riwayat-aktivitas-produk', RiwayatAktivitasProdukController::class)->only(['index', 'show']);

    Route::get('riwayat-logs', [RiwayatAktivitasLogController::class, 'index'])->name('riwayat-log.index');
    Route::get('riwayat-logs/{id}', [RiwayatAktivitasLogController::class, 'show'])->name('riwayat-log.show');
    Route::delete('riwayat-logs', [RiwayatAktivitasLogController::class, 'destroyAll'])->name('riwayat-log.destroyAll');

    Route::resource('laporan', LaporanController::class);
    Route::resource('stock-opname', StockOpnameController::class);
    Route::resource('divisi', DivisiController::class);
    Route::resource('jabatan', JabatanController::class);
});

Route::middleware(['role:customer'])->group(function () {
    Route::get('profil-customer', [UserController::class, 'profilCustomer'])
        ->name('customer.profil');

    Route::post('profil-customer', [UserController::class, 'updateProfilCustomer'])
        ->name('customer.profil.update');

    // Cart Routes
    Route::get('keranjang', [CartController::class, 'index'])->name('customer.cart');
    Route::post('keranjang/tambah', [CartController::class, 'add'])->name('customer.cart.add');
    Route::patch('keranjang/update/{id}', [CartController::class, 'update'])->name('customer.cart.update');
    Route::delete('keranjang/hapus/{id}', [CartController::class, 'remove'])->name('customer.cart.remove');
});
