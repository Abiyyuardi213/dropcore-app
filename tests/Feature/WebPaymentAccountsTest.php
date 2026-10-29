<?php

namespace Tests\Feature;

use App\Models\{Order, User};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class WebPaymentAccountsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array']);
        DB::purge('sqlite');
        $this->withoutMiddleware();
        $this->withoutVite();
        View::share('errors', new \Illuminate\Support\ViewErrorBag());
        Schema::create('users', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); $t->string('username'); $t->string('password'); $t->timestamps(); });
        Schema::create('products', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); $t->string('uom_id')->nullable(); $t->decimal('price',15,2); $t->decimal('weight',8,2)->default(1); $t->timestamps(); });
        Schema::create('uoms', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('carts', function (Blueprint $t) { $t->increments('id'); $t->string('user_id'); $t->string('product_id'); $t->integer('quantity'); $t->timestamps(); });
        Schema::create('stok', function (Blueprint $t) { $t->string('id')->primary(); $t->string('produk_id'); $t->integer('quantity'); $t->timestamps(); });
        Schema::create('sumber_keuangan', function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('nama_sumber'); $t->string('jenis'); $t->string('nomor_rekening')->nullable(); $t->string('atas_nama')->nullable(); $t->decimal('saldo',18,2); $t->boolean('is_active'); $t->timestamps();
        });
        Schema::create('metode_pembayaran', function (Blueprint $t) { $t->string('id')->primary(); $t->string('nama_bank'); $t->string('nomor_rekening'); $t->string('atas_nama'); $t->boolean('status'); $t->timestamps(); });
        Schema::create('jasa_pengiriman', function (Blueprint $t) { $t->string('id')->primary(); $t->string('nama'); $t->string('kode'); $t->decimal('biaya_dasar',15,2); $t->boolean('status'); $t->timestamps(); });
        Schema::create('orders', function (Blueprint $t) {
            $t->string('id')->primary(); foreach (['user_id','order_number','status','shipping_address','shipping_provider','payment_method'] as $field) $t->string($field);
            $t->text('notes')->nullable(); foreach (['tax_base','tax_amount','total_price'] as $field) $t->decimal($field,18,2); $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) { $t->string('id')->primary(); $t->string('order_id'); $t->string('product_id'); $t->integer('quantity'); $t->decimal('price',15,2); $t->decimal('subtotal',18,2); $t->timestamps(); });
        Schema::create('mutasi_stok', function (Blueprint $t) {
            $t->string('id')->primary(); foreach (['produk_id','jenis_mutasi','gudang_asal_id','area_asal_id','rak_asal_id','gudang_tujuan_id','area_tujuan_id','rak_tujuan_id','kondisi_id','referensi','user_id','tanggal_mutasi','keterangan'] as $field) $t->string($field)->nullable();
            $t->integer('quantity'); $t->timestamps();
        });
        DB::table('users')->insert(['id'=>'u', 'name'=>'Pembeli', 'username'=>'buyer', 'password'=>'unused']);
        $this->actingAs(User::findOrFail('u'));
        DB::table('products')->insert(['id'=>'p','name'=>'Produk Web','price'=>100000,'weight'=>1]);
        DB::table('carts')->insert(['user_id'=>'u','product_id'=>'p','quantity'=>1]);
        DB::table('stok')->insert(['id'=>'s','produk_id'=>'p','quantity'=>10]);
        DB::table('jasa_pengiriman')->insert(['id'=>'ship','nama'=>'Kirim Test','kode'=>'REG','biaya_dasar'=>10000,'status'=>true]);
        foreach ([['bank','Bank Aktif','bank',true], ['cash','Kas Tunai','tunai',true], ['wallet','Dompet Digital','e-wallet',true], ['inactive','Bank Nonaktif','bank',false]] as [$id,$name,$type,$active]) {
            DB::table('sumber_keuangan')->insert(['id'=>$id,'nama_sumber'=>$name,'jenis'=>$type,'nomor_rekening'=>'123456','atas_nama'=>'Perusahaan','saldo'=>1000,'is_active'=>$active]);
        }
        DB::table('metode_pembayaran')->insert(['id'=>'legacy','nama_bank'=>'Bank Lama','nomor_rekening'=>'999','atas_nama'=>'Lama','status'=>true]);
    }

    private function payload(string $account): array
    {
        return ['shipping_address'=>'Alamat pengiriman', 'shipping_service_id'=>'ship', 'payment_method_id'=>$account, 'items'=>[['product_id'=>'p','quantity'=>1]]];
    }

    public function test_web_and_api_list_only_active_banks_and_never_expose_balances(): void
    {
        $this->get('/konfirmasi-pesanan')->assertOk()->assertSee('Bank Aktif')->assertDontSee('Kas Tunai')->assertDontSee('Dompet Digital')->assertDontSee('Bank Nonaktif')->assertDontSee('Bank Lama')
            ->assertViewHas('paymentMethods', fn ($methods) => $methods->pluck('id')->all() === ['bank']);
        $this->getJson('/api/metode-pembayaran')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', 'bank')->assertJsonPath('data.0.nama_bank', 'Bank Aktif')->assertJsonMissingPath('data.0.saldo');
    }

    public function test_web_and_api_reject_cash_wallet_inactive_and_legacy_ids_without_changing_stock(): void
    {
        foreach (['cash','wallet','inactive','legacy'] as $id) {
            $this->post('/checkout', $this->payload($id))->assertSessionHasErrors('payment_method_id');
            $this->postJson('/api/checkout', $this->payload($id))->assertUnprocessable()->assertJsonValidationErrors('payment_method_id');
        }
        $this->assertEquals(0, Order::count()); $this->assertEquals(10, DB::table('stok')->value('quantity')); $this->assertEquals(1, DB::table('carts')->count());
    }

    public function test_web_bank_checkout_saves_account_snapshot_without_recording_payment_prematurely(): void
    {
        $this->post('/checkout', $this->payload('bank'))->assertSessionHasNoErrors()->assertRedirect();
        $order = Order::firstOrFail();
        $this->assertEquals('Bank Aktif - 123456 a.n. Perusahaan', $order->payment_method);
        $this->assertEquals('waiting_payment', $order->status);
        $this->assertEquals(1000, DB::table('sumber_keuangan')->where('id','bank')->value('saldo'));
        $this->assertEquals(9, DB::table('stok')->value('quantity'));
        DB::table('sumber_keuangan')->where('id','bank')->update(['nama_sumber'=>'Diubah']);
        $this->assertEquals('Bank Aktif - 123456 a.n. Perusahaan', $order->fresh()->payment_method);
    }

    public function test_api_bank_checkout_accepts_the_same_account_ids(): void
    {
        $this->postJson('/api/checkout', $this->payload('bank'))->assertCreated()->assertJsonPath('status','success');
        $this->assertEquals('Bank Aktif - 123456 a.n. Perusahaan', Order::firstOrFail()->payment_method);
    }

    public function test_legacy_admin_module_redirects_and_cannot_modify_old_data(): void
    {
        $this->get('/administrator/metode-pembayaran')->assertRedirect(route('sumber-keuangan.index'));
        $this->post('/administrator/metode-pembayaran', [])->assertStatus(410);
        $this->patch('/administrator/metode-pembayaran/legacy', ['nama_bank'=>'Diubah'])->assertStatus(410);
        $this->delete('/administrator/metode-pembayaran/legacy')->assertStatus(410);
        $this->assertEquals('Bank Lama', DB::table('metode_pembayaran')->value('nama_bank'));
    }

    public function test_no_active_banks_shows_empty_state_even_when_cash_exists(): void
    {
        DB::table('sumber_keuangan')->where('id','bank')->update(['is_active'=>false]);
        $this->get('/konfirmasi-pesanan')->assertOk()->assertSee('Belum ada rekening bank aktif')->assertViewHas('paymentMethods', fn ($methods) => $methods->isEmpty());
        $this->getJson('/api/metode-pembayaran')->assertOk()->assertJsonCount(0, 'data');
    }
}
