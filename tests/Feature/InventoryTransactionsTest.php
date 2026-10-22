<?php

namespace Tests\Feature;

use App\Models\{PenerimaanBarang, PengeluaranBarang, Keuangan};
use App\Services\InventoryPaymentService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InventoryTransactionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array']);
        DB::purge('sqlite');
        $this->withoutMiddleware();
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
    }

    private function payload(bool $purchase, string $action = 'process', string $payment = 'unpaid'): array
    {
        return [
            'tipe_pengirim' => 'supplier', 'supplier_id' => 'x', 'tipe_penerima' => 'distributor', 'distributor_id' => 'x',
            'tanggal_penerimaan' => '2026-10-21', 'tanggal_pengeluaran' => '2026-10-21',
            'submit_action' => $action, 'payment_status' => $payment, 'sumber_id' => $payment === 'paid' ? 'bank' : null,
            'items' => [['produk_id' => 'p', 'gudang_id' => 'x', 'area_id' => 'a', 'rak_id' => 'r', 'kondisi_id' => 'x', 'qty' => 2, 'harga' => $purchase ? 100 : 200]],
        ];
    }

    public function test_purchase_paid_and_cancel_restore_stock_cash_and_keep_audit_once(): void
    {
        $this->post('/administrator/penerimaan-barang', $this->payload(true, 'process', 'paid'))->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertEquals(12, DB::table('stok')->value('quantity'));
        $this->assertEquals(780, DB::table('sumber_keuangan')->value('saldo'));
        $doc = PenerimaanBarang::firstOrFail();
        app(InventoryPaymentService::class)->cancel($doc);
        app(InventoryPaymentService::class)->cancel($doc);
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
        $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
        $this->assertEquals('canceled', $doc->fresh()->status);
        $this->assertEquals('reversed', $doc->fresh()->payment_status);
        $this->assertEquals(2, Keuangan::count());
    }

    public function test_sale_paid_and_cancel_restore_stock_and_cash(): void
    {
        $this->post('/administrator/pengeluaran-barang', $this->payload(false, 'process', 'paid'))->assertSessionHas('success');
        $this->assertEquals(8, DB::table('stok')->value('quantity'));
        $this->assertEquals(1400, DB::table('sumber_keuangan')->value('saldo'));
        app(InventoryPaymentService::class)->cancel(PengeluaranBarang::firstOrFail());
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
        $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
    }

    public function test_deleting_drafts_does_not_touch_stock_or_cash(): void
    {
        foreach ([true, false] as $purchase) {
            $route = $purchase ? 'penerimaan' : 'pengeluaran';
            $data = $this->payload($purchase, 'draft', 'paid'); $data['sumber_id'] = null;
            $this->post("/administrator/$route-barang", $data)->assertSessionHas('success');
            $doc = $purchase ? PenerimaanBarang::firstOrFail() : PengeluaranBarang::firstOrFail();
            app(InventoryPaymentService::class)->cancel($doc);
        }
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
        $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
        $this->assertEquals(0, Keuangan::count());
    }

    public function test_unpaid_purchase_and_sale_only_move_stock_until_settled(): void
    {
        foreach ([true, false] as $purchase) {
            $route = $purchase ? 'penerimaan' : 'pengeluaran';
            $this->post("/administrator/$route-barang", $this->payload($purchase))->assertSessionHas('success');
        }
        $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
        $this->assertEquals(0, Keuangan::count());
        $this->post('/administrator/penerimaan-barang/'.PenerimaanBarang::first()->id.'/pay', ['sumber_id' => 'bank', 'tanggal_pembayaran' => '2026-10-22'])->assertSessionHas('success');
        $this->assertEquals(780, DB::table('sumber_keuangan')->value('saldo'));
        $this->post('/administrator/pengeluaran-barang/'.PengeluaranBarang::first()->id.'/pay', ['sumber_id' => 'bank', 'tanggal_pembayaran' => '2026-10-22'])->assertSessionHas('success');
        $this->assertEquals(1180, DB::table('sumber_keuangan')->value('saldo'));
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
        $this->post('/administrator/pengeluaran-barang/'.PengeluaranBarang::first()->id.'/pay', ['sumber_id' => 'bank', 'tanggal_pembayaran' => '2026-10-22'])->assertSessionHas('error');
        $this->assertEquals(2, Keuangan::count());
    }

    public function test_new_receiving_location_and_explicit_purchase_price_are_used(): void
    {
        $data = $this->payload(true); $data['items'][0] = array_replace($data['items'][0], ['gudang_id' => 'new', 'area_id' => 'new-a', 'rak_id' => 'new-r', 'harga' => 12.50]);
        $this->post('/administrator/penerimaan-barang', $data)->assertSessionHas('success');
        $this->assertEquals(2, DB::table('stok')->where('gudang_id', 'new')->value('quantity'));
        $this->assertEquals(27.50, PenerimaanBarang::first()->total_amount);
        $this->assertEquals(200, DB::table('products')->value('price'));
    }

    public function test_insufficient_stock_or_cash_roll_back_the_entire_transaction(): void
    {
        $data = $this->payload(false, 'process', 'paid'); $data['items'][0]['qty'] = 11;
        $this->post('/administrator/pengeluaran-barang', $data)->assertSessionHas('error');
        $data = $this->payload(true, 'process', 'paid'); $data['items'][0]['harga'] = 2000;
        $this->post('/administrator/penerimaan-barang', $data)->assertSessionHas('error');
        $this->assertEquals(0, PenerimaanBarang::count()); $this->assertEquals(0, PengeluaranBarang::count());
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
        $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
    }

    public function test_missing_sale_price_is_rejected_instead_of_silently_replaced(): void
    {
        $data = $this->payload(false); unset($data['items'][0]['harga']);
        $this->post('/administrator/pengeluaran-barang', $data)->assertSessionHasErrors('items.0.harga');
        $this->assertEquals(0, PengeluaranBarang::count());
    }

    public function test_purchase_cancel_after_stock_used_is_atomic(): void
    {
        $this->post('/administrator/penerimaan-barang', $this->payload(true, 'process', 'paid'))->assertSessionHas('success');
        DB::table('stok')->update(['quantity' => 1]);
        try { app(InventoryPaymentService::class)->cancel(PenerimaanBarang::firstOrFail()); $this->fail('Expected insufficient stock failure'); }
        catch (ValidationException $e) { $this->assertEquals(780, DB::table('sumber_keuangan')->value('saldo')); }
        $this->assertEquals('completed', PenerimaanBarang::first()->status);
        $this->assertEquals(1, Keuangan::count());
    }

    public function test_unpaid_cancellation_and_repeated_sale_cancellation_do_not_move_cash(): void
    {
        foreach ([true, false] as $purchase) {
            $route = $purchase ? 'penerimaan' : 'pengeluaran';
            $this->post("/administrator/$route-barang", $this->payload($purchase))->assertSessionHas('success');
            $doc = $purchase ? PenerimaanBarang::firstOrFail() : PengeluaranBarang::firstOrFail();
            app(InventoryPaymentService::class)->cancel($doc);
            app(InventoryPaymentService::class)->cancel($doc);
        }
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
        $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
        $this->assertEquals(0, Keuangan::count());
    }

    public function test_same_stock_in_multiple_lines_cannot_be_oversold(): void
    {
        $data = $this->payload(false, 'process', 'paid');
        $data['items'][0]['qty'] = 6; $data['items'][] = $data['items'][0];
        $this->post('/administrator/pengeluaran-barang', $data)->assertSessionHas('error');
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
        $this->assertEquals(0, PengeluaranBarang::count());
    }

    public function test_decimal_sale_price_is_not_multiplied_by_removing_the_decimal_separator(): void
    {
        $data = $this->payload(false, 'process', 'paid'); $data['items'][0]['harga'] = 12.50;
        $this->post('/administrator/pengeluaran-barang', $data)->assertSessionHas('success');
        $this->assertEquals(25, Keuangan::first()->jumlah);
        $this->assertEquals(1025, DB::table('sumber_keuangan')->value('saldo'));
    }

    public function test_invalid_location_is_rejected_and_linked_finance_cannot_be_edited_or_deleted(): void
    {
        $data = $this->payload(true); $data['items'][0]['rak_id'] = 'new-r';
        $this->post('/administrator/penerimaan-barang', $data)->assertSessionHas('error');
        $this->assertEquals(0, PenerimaanBarang::count());
        $this->post('/administrator/penerimaan-barang', $this->payload(true, 'process', 'paid'))->assertSessionHas('success');
        $entry = Keuangan::firstOrFail();
        $this->delete('/administrator/keuangan/delete/'.$entry->id)->assertSessionHas('error');
        $this->put('/administrator/keuangan/update/'.$entry->id, [])->assertSessionHas('error');
        $this->assertEquals(1, Keuangan::count());
        $this->assertEquals(780, DB::table('sumber_keuangan')->value('saldo'));
    }

    public function test_draft_and_canceled_documents_cannot_be_settled(): void
    {
        $this->post('/administrator/penerimaan-barang', $this->payload(true, 'draft'))->assertSessionHas('success');
        $doc = PenerimaanBarang::firstOrFail();
        $this->post('/administrator/penerimaan-barang/'.$doc->id.'/pay', ['sumber_id' => 'bank', 'tanggal_pembayaran' => '2026-10-22'])->assertSessionHas('error');
        app(InventoryPaymentService::class)->cancel($doc);
        $this->post('/administrator/penerimaan-barang/'.$doc->id.'/pay', ['sumber_id' => 'bank', 'tanggal_pembayaran' => '2026-10-22'])->assertSessionHas('error');
        $this->assertEquals(0, Keuangan::count());
    }

    public function test_migration_links_old_payments_without_touching_stock_or_account_balance(): void
    {
        $migration = require base_path('database/migrations/2026_10_21_210000_separate_inventory_payments_and_purchase_prices.php');
        $migration->down();
        DB::table('penerimaan_barang')->insert(['id' => 'old', 'no_penerimaan' => 'GR-OLD', 'status' => 'completed']);
        DB::table('keuangan')->insert(['id' => 'old-payment', 'jumlah' => 100, 'jenis_transaksi' => 'pengeluaran', 'status' => 'approved', 'keterangan' => 'Pembelian Stok Ref: GR-OLD', 'sumber_id' => 'bank', 'created_at' => '2026-10-20 10:00:00']);
        $migration->up();
        $this->assertEquals('paid', DB::table('penerimaan_barang')->where('id', 'old')->value('payment_status'));
        $this->assertEquals('old', DB::table('keuangan')->value('referensi_id'));
        $this->assertEquals('penerimaan_barang', DB::table('keuangan')->value('referensi_tabel'));
        $this->assertEquals(10, DB::table('stok')->value('quantity'));
        $this->assertEquals(1000, DB::table('sumber_keuangan')->value('saldo'));
    }

    public function test_transaction_dashboard_separates_completed_draft_and_canceled_totals(): void
    {
        $this->post('/administrator/penerimaan-barang', $this->payload(true))->assertSessionHas('success');
        $this->post('/administrator/pengeluaran-barang', $this->payload(false))->assertSessionHas('success');
        $this->post('/administrator/penerimaan-barang', $this->payload(true, 'draft'))->assertSessionHas('success');
        $draft = PenerimaanBarang::where('status', 'pending')->firstOrFail();
        app(InventoryPaymentService::class)->cancel($draft);
        $this->post('/administrator/pengeluaran-barang', $this->payload(false, 'draft'))->assertSessionHas('success');
        $view = app(\App\Http\Controllers\DashboardTransaksiController::class)->index(\Illuminate\Http\Request::create('/', 'GET', ['tanggal' => '2026-10-21']));
        $data = $view->getData();
        $this->assertCount(7, $data['harian']);
        $this->assertEquals(1, $data['ringkasan']['penerimaan']);
        $this->assertEquals(1, $data['ringkasan']['pengeluaran']);
        $this->assertEquals(220, $data['ringkasan']['pembelian']);
        $this->assertEquals(400, $data['ringkasan']['penjualan']);
        $this->assertEquals(1, $data['ringkasan']['batal']);
        $this->assertEquals(1, $data['ringkasan']['draft']);
        $this->assertEquals(4, $data['terbaru']->total());
        $empty = app(\App\Http\Controllers\DashboardTransaksiController::class)->index(\Illuminate\Http\Request::create('/', 'GET', ['tanggal' => '2026-10-20']))->getData();
        $this->assertEquals(0, $empty['ringkasan']['pembelian']);
        $this->assertEquals(4, $empty['terbaru']->total());
    }
}

