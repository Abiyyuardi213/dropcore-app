<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\{User, KasirSale, PengeluaranBarang, Keuangan};
use App\Services\InventoryPaymentService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, Hash};
use Illuminate\Support\Str;
class KasirSalesTest extends TestCase
{
    private User $cashier;
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array']);
        DB::purge('sqlite');
        $this->withoutMiddleware();
        \Illuminate\Support\Facades\View::share('errors', new \Illuminate\Support\ViewErrorBag());
        foreach (['products', 'suppliers', 'distributor', 'gudang', 'kondisi_barang'] as $name) {
            Schema::create($name, function (Blueprint $t) use ($name) {
                $t->string('id')->primary();
                if ($name === 'products') { $t->string('name'); $t->decimal('price', 15, 2); }
                $t->timestamps();
            });
        }
        Schema::create('area_gudang', function (Blueprint $t) { $t->string('id')->primary(); $t->string('gudang_id'); });
        Schema::create('rak_gudang', function (Blueprint $t) { $t->string('id')->primary(); $t->string('gudang_id'); $t->string('area_id'); });
        Schema::create('stok', function (Blueprint $t) {
            $t->string('id')->primary();
            foreach (['produk_id', 'gudang_id', 'area_id', 'rak_id', 'kondisi_id'] as $field) $t->string($field)->nullable();
            $t->integer('quantity'); $t->timestamps();
        });
        foreach (['penerimaan_barang', 'pengeluaran_barang'] as $name) {
            Schema::create($name, function (Blueprint $t) use ($name) {
                $t->string('id')->primary(); $t->string('status')->default('completed');
                foreach (['user_id', 'distributor_id', 'supplier_id', 'tipe_pengirim', 'tipe_penerima', 'nama_konsumen', 'telepon_konsumen', 'alamat_konsumen', 'referensi', 'keterangan', 'no_penerimaan', 'no_pengeluaran', 'tanggal_penerimaan', 'tanggal_pengeluaran'] as $field) $t->string($field)->nullable();
                foreach (['subtotal', 'ppn_percentage', 'ppn_amount', 'total_amount'] as $field) $t->decimal($field, 15, 2)->default(0);
                $t->timestamps();
            });
        }
        foreach (['penerimaan_barang_detail', 'pengeluaran_barang_detail'] as $name) {
            Schema::create($name, function (Blueprint $t) {
                $t->string('id')->primary();
                foreach (['penerimaan_id', 'pengeluaran_id', 'produk_id', 'stok_id', 'gudang_id', 'area_id', 'rak_id', 'kondisi_id'] as $field) $t->string($field)->nullable();
                $t->integer('qty'); $t->decimal('harga', 15, 2); $t->decimal('subtotal', 15, 2); $t->timestamps();
            });
        }
        Schema::create('activity_logs', function (Blueprint $t) { $t->string('id')->primary(); foreach (['module', 'action', 'description', 'user_id'] as $field) $t->string($field)->nullable(); $t->timestamps(); });
        Schema::create('sumber_keuangan', function (Blueprint $t) { $t->string('id')->primary(); $t->string('nama_sumber'); $t->boolean('is_active')->default(true); $t->decimal('saldo', 18, 2); $t->timestamps(); });
        Schema::create('kategori_keuangan', function (Blueprint $t) { $t->string('id')->primary(); $t->string('nama'); $t->string('tipe'); $t->string('deskripsi')->nullable(); $t->timestamps(); });
        Schema::create('keuangan', function (Blueprint $t) {
            $t->string('id')->primary();
            foreach (['no_transaksi', 'jenis_transaksi', 'kategori_keuangan_id', 'sumber_id', 'referensi_id', 'referensi_tabel', 'tanggal_transaksi', 'keterangan', 'status', 'user_id'] as $field) $t->string($field)->nullable();
            $t->decimal('jumlah', 18, 2); $t->timestamps();
        });
        $migration = require base_path('database/migrations/2026_10_21_210000_separate_inventory_payments_and_purchase_prices.php');
        $migration->up();
        DB::table('products')->insert(['id' => 'p', 'name' => 'Produk Test', 'price' => 200, 'purchase_price' => 100]);
        foreach (['suppliers', 'distributor', 'gudang', 'kondisi_barang'] as $table) DB::table($table)->insert(['id' => 'x']);
        DB::table('gudang')->insert(['id' => 'new']);
        DB::table('area_gudang')->insert([['id' => 'a', 'gudang_id' => 'x'], ['id' => 'new-a', 'gudang_id' => 'new']]);
        DB::table('rak_gudang')->insert([['id' => 'r', 'gudang_id' => 'x', 'area_id' => 'a'], ['id' => 'new-r', 'gudang_id' => 'new', 'area_id' => 'new-a']]);
        DB::table('stok')->insert(['id' => 's', 'produk_id' => 'p', 'gudang_id' => 'x', 'area_id' => 'a', 'rak_id' => 'r', 'kondisi_id' => 'x', 'quantity' => 10]);
        DB::table('sumber_keuangan')->insert(['id' => 'bank', 'nama_sumber' => 'Bank Test', 'saldo' => 1000]);

        Schema::table('products', function (Blueprint $t) { foreach (['sku','merk','image','uom_id','category_id'] as $field) $t->string($field)->nullable(); });
        Schema::table('sumber_keuangan', fn (Blueprint $t) => $t->string('jenis')->default('tunai'));
        Schema::table('gudang', fn (Blueprint $t) => $t->string('nama_gudang')->nullable());
        Schema::table('area_gudang', fn (Blueprint $t) => $t->string('kode_area')->nullable());
        Schema::table('rak_gudang', fn (Blueprint $t) => $t->string('kode_rak')->nullable());
        Schema::table('kondisi_barang', fn (Blueprint $t) => $t->string('nama_kondisi')->nullable());
        Schema::create('uoms', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('users', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); $t->string('username'); $t->string('password'); $t->rememberToken(); $t->timestamps(); });
        $migration = require base_path('database/migrations/2026_10_26_120000_create_cashier_sales_tables.php'); $migration->up();
        $this->cashier = User::create(['name' => 'Kasir Test', 'username' => 'cashier', 'password' => Hash::make('test-password')]);
        $this->actingAs($this->cashier);
    }


    private function payload(): array
    {
        return ['submission_key' => (string) Str::uuid(), 'customer_name' => 'Customer POS', 'customer_phone' => '081234',
            'sumber_id' => 'bank', 'discount' => 10, 'paid_amount' => 400,
            'items' => [['stock_id' => 's', 'quantity' => 2]]];
    }
    public function test_cash_payment_of_1200000_returns_exactly_5000(): void
    {
        DB::table('products')->where('id', 'p')->update(['price' => 1195000]);
        $data = $this->payload();
        $data['items'][0]['quantity'] = 1;
        $data['discount'] = 0;
        $data['paid_amount'] = '1200000';
        $this->post('/kasir/penjualan', $data)->assertSessionHasNoErrors()->assertRedirect();
        $sale = KasirSale::firstOrFail();
        $this->assertEquals('1200000.00', number_format($sale->paid_amount, 2, '.', ''));
        $this->assertEquals('5000.00', number_format($sale->change_amount, 2, '.', ''));
        $this->get('/kasir/penjualan/'.$sale->id)->assertOk()->assertSee('1.200.000,00')->assertSee('5.000,00');
    }
    public function test_receipt_80mm_and_downloadable_invoice_are_available_only_to_the_owner(): void
    {
        $this->post('/kasir/penjualan', $this->payload())->assertSessionHasNoErrors();
        $sale = KasirSale::firstOrFail();
        $this->get('/kasir/penjualan/'.$sale->id)->assertOk()->assertSee('Simpan Invoice (PDF)')->assertSee('Cetak Struk (80 mm)');
        $this->get('/kasir/penjualan/'.$sale->id.'/struk')->assertOk()->assertSee('80mm', false)->assertSee($sale->invoice);
        $pdf = $this->get('/kasir/penjualan/'.$sale->id.'/invoice');
        $pdf->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $pdf->getContent());
        $this->assertStringContainsString('attachment', $pdf->headers->get('content-disposition'));
        $sale->update(['user_id' => 'another-user']);
        $this->get('/kasir/penjualan/'.$sale->id.'/struk')->assertNotFound();
        $this->get('/kasir/penjualan/'.$sale->id.'/invoice')->assertNotFound();
    }
    public function test_sale_creates_invoice_stock_issue_payment_and_change_once(): void
    {
        $data = $this->payload();
        $this->post('/kasir/penjualan', $data)->assertSessionHasNoErrors()->assertRedirect();
        $sale = KasirSale::firstOrFail();
        $this->assertEquals(400, $sale->subtotal); $this->assertEquals(40, $sale->discount_amount);
        $this->assertEquals(360, $sale->total); $this->assertEquals(40, $sale->change_amount);
        $this->assertEquals(160, $sale->profit); $this->assertEquals(8, DB::table('stok')->value('quantity'));
        $this->assertEquals(1360, DB::table('sumber_keuangan')->value('saldo'));
        $this->assertEquals(360, Keuangan::first()->jumlah);
        $this->post('/kasir/penjualan', $data)->assertRedirect(route('kasir.penjualan.show', $sale->id));
        $this->assertEquals(1, KasirSale::count()); $this->assertEquals(1, Keuangan::count());
        $this->get('/kasir/penjualan/'.$sale->id)->assertOk()->assertSee($sale->invoice);
        $this->get('/kasir/laporan')->assertOk()->assertViewHas('count', 1)->assertViewHas('total', 360);
        $this->get('/kasir/dashboard')->assertOk()->assertViewHas('stats', fn ($s) => $s['Unit Terjual'] === 2);
    }
    public function test_insufficient_stock_and_underpayment_roll_back_everything(): void
    {
        $data = $this->payload(); $data['items'][0]['quantity'] = 11;
        $this->post('/kasir/penjualan', $data)->assertSessionHasErrors('transaksi');
        $data = $this->payload(); $data['paid_amount'] = 1;
        $this->post('/kasir/penjualan', $data)->assertSessionHasErrors('transaksi');
        $this->assertEquals(0, KasirSale::count()); $this->assertEquals(0, PengeluaranBarang::count());
        $this->assertEquals(10, DB::table('stok')->value('quantity')); $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
    }
    public function test_non_cash_requires_exact_amount_and_unknown_cost_does_not_fake_profit(): void
    {
        DB::table('sumber_keuangan')->update(['jenis' => 'bank']);
        $data = $this->payload(); $this->post('/kasir/penjualan', $data)->assertSessionHasErrors('transaksi');
        DB::table('products')->update(['purchase_price' => null]); $data['paid_amount'] = 360; session()->forget('errors');
        $this->post('/kasir/penjualan', $data)->assertSessionHasNoErrors();
        $this->assertNull(KasirSale::firstOrFail()->profit); $this->assertEquals(0, KasirSale::first()->change_amount);
    }
    public function test_prices_are_server_authoritative_and_duplicate_stock_lines_rejected(): void
    {
        $data = $this->payload(); $data['items'][0]['price'] = 1; $data['total'] = 2;
        $this->post('/kasir/penjualan', $data)->assertSessionHasNoErrors();
        $this->assertEquals(360, KasirSale::firstOrFail()->total);
        $data = $this->payload(); $data['items'][] = $data['items'][0];
        $this->post('/kasir/penjualan', $data)->assertSessionHasErrors('items.0.stock_id');
        $this->assertEquals(1, KasirSale::count());
    }
    public function test_cancel_reverses_cash_and_stock_and_removes_sale_from_report(): void
    {
        $this->post('/kasir/penjualan', $this->payload())->assertSessionHasNoErrors();
        $sale = KasirSale::firstOrFail(); app(InventoryPaymentService::class)->cancel($sale->outgoing);
        $this->assertEquals(10, DB::table('stok')->value('quantity')); $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
        $this->get('/kasir/laporan')->assertOk()->assertViewHas('count', 0);
        $this->get('/kasir/dashboard')->assertOk()->assertViewHas('stats', fn ($s) => $s['Jumlah Invoice'] === 0);
    }
    public function test_stock_lookup_and_receipt_scope_are_safe(): void
    {
        $this->getJson('/kasir/penjualan/produk?search=Produk')->assertOk()->assertJsonPath('0.price', 200)->assertJsonMissingPath('0.purchase_price')->assertJsonPath('0.stocks.0.quantity', 10);
        $this->post('/kasir/penjualan', $this->payload())->assertSessionHasNoErrors(); $sale = KasirSale::firstOrFail();
        $other = User::create(['name'=>'Other', 'username'=>'other', 'password'=>Hash::make('password')]);
        $this->actingAs($other)->get('/kasir/penjualan/'.$sale->id)->assertNotFound();
        $this->get('/kasir/laporan')->assertOk()->assertViewHas('count', 0);
    }

    public function test_invalid_discount_inactive_account_and_missing_stock_cannot_save(): void
    {
        $data = $this->payload(); $data['discount'] = 101;
        $this->post('/kasir/penjualan', $data)->assertSessionHasErrors('discount');
        $data = $this->payload(); $data['items'][0]['stock_id'] = 'missing';
        $this->post('/kasir/penjualan', $data)->assertSessionHasErrors('items.0.stock_id');
        $data = $this->payload(); DB::table('sumber_keuangan')->update(['is_active' => false]);
        $this->post('/kasir/penjualan', $data)->assertSessionHasErrors('transaksi');
        $this->assertEquals(0, KasirSale::count()); $this->assertEquals(0, Keuangan::count());
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
    }
    public function test_price_snapshot_does_not_change_when_master_is_updated(): void
    {
        $data = $this->payload(); $data['discount'] = 12.5;
        $this->post('/kasir/penjualan', $data)->assertSessionHasNoErrors();
        $sale = KasirSale::firstOrFail(); $this->assertEquals(350, $sale->total);
        DB::table('products')->update(['price' => 999, 'purchase_price' => 888]);
        $this->assertEquals(200, $sale->items->first()->unit_price);
        $this->assertEquals(100, $sale->items->first()->purchase_price);
        $this->assertEquals(350, $sale->items->sum('total'));
    }
}
