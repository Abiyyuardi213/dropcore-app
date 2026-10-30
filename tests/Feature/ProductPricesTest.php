<?php

namespace Tests\Feature;

use App\Models\{Products, Role, User};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class ProductPricesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array']);
        DB::purge('sqlite'); $this->withoutMiddleware();
        Schema::create('products',function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('name'); $t->string('sku')->unique();
            foreach (['merk','description','dimensi','category_id','uom_id','image'] as $field) $t->string($field)->nullable();
            foreach (['weight','berat','price','purchase_price','min_stock','max_stock'] as $field) $t->decimal($field,15,2)->nullable();
            $t->timestamps();
        });
        Schema::create('product_category',function (Blueprint $t) { $t->string('id')->primary(); $t->string('category_name'); });
        Schema::create('uoms',function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('stok',function (Blueprint $t) { $t->string('id')->primary(); $t->string('produk_id'); $t->integer('quantity'); });
        foreach (['activity_logs','riwayat_aktivitas_produk'] as $table) Schema::create($table,function (Blueprint $t) {
            $t->string('id')->primary();
            foreach (['module','action','description','produk_id','tipe_aktivitas','deskripsi','user_id'] as $field) $t->string($field)->nullable();
            $t->timestamps();
        });
        DB::table('product_category')->insert(['id'=>'c','category_name'=>'Kabel']);
        DB::table('uoms')->insert(['id'=>'pcs','name'=>'pcs']);
        View::share('errors',new \Illuminate\Support\ViewErrorBag());
        $user = new User(['name'=>'Admin','username'=>'admin']); $user->id='u';
        $user->setRelation('role',new Role(['role_name'=>'admin'])); $this->actingAs($user);
    }

    private function input(): array
    {
        return ['sku'=>'SKU-1','name'=>'Produk Uji','category_id'=>'c','uom_id'=>'pcs','harga_beli'=>'100000.25','harga_jual'=>'125000.50'];
    }

    public function test_admin_creates_updates_and_views_distinct_prices_without_breaking_legacy_storage(): void
    {
        $this->post('/administrator/produk',$this->input())->assertRedirect(route('product.index'))->assertSessionHasNoErrors();
        $product = Products::firstOrFail();
        $this->assertEquals('100000.25',$product->purchase_price); $this->assertEquals('125000.5',$product->price);
        $this->assertEquals($product->price,$product->harga_jual); $this->assertEquals($product->purchase_price,$product->harga_beli);
        $this->get('/administrator/produk')->assertOk()->assertSee('Harga Beli')->assertSee('Harga Jual')->assertSee('100.000,25')->assertSee('125.000,50');
        $this->get('/administrator/produk/'.$product->id)->assertOk()->assertSee('Harga Beli')->assertSee('Harga Jual');
        $this->get('/administrator/produk/'.$product->id.'/edit')->assertOk()->assertSee('name="harga_beli"',false)->assertSee('name="harga_jual"',false);
        $this->put('/administrator/produk/'.$product->id,array_replace($this->input(),['harga_beli'=>'110000.75','harga_jual'=>'130000.25']))->assertSessionHasNoErrors();
        $product->refresh(); $this->assertEquals(110000.75,$product->purchase_price); $this->assertEquals(130000.25,$product->price);
    }

    public function test_missing_cost_is_distinct_from_zero_and_existing_cost_can_be_cleared(): void
    {
        $this->post('/administrator/produk',array_replace($this->input(),['harga_beli'=>null]))->assertSessionHasNoErrors();
        $product = Products::firstOrFail(); $this->assertNull($product->purchase_price);
        $this->get('/administrator/produk')->assertOk()->assertSee('Belum diisi');
        $this->put('/administrator/produk/'.$product->id,array_replace($this->input(),['harga_beli'=>0]))->assertSessionHasNoErrors();
        $this->assertEquals(0,$product->fresh()->purchase_price);
        $this->put('/administrator/produk/'.$product->id,array_replace($this->input(),['harga_beli'=>null]))->assertSessionHasNoErrors();
        $this->assertNull($product->fresh()->purchase_price);
    }

    public function test_invalid_prices_are_rejected_and_legacy_requests_still_work(): void
    {
        $this->from('/administrator/produk/create')->post('/administrator/produk',array_replace($this->input(),['harga_beli'=>-1,'harga_jual'=>'12.345']))->assertSessionHasErrors(['harga_beli','harga_jual']);
        session()->forget('errors');
        $legacy = $this->input(); unset($legacy['harga_beli'],$legacy['harga_jual']);
        $this->post('/administrator/produk',$legacy+['purchase_price'=>100,'price'=>150])->assertSessionHasNoErrors();
        $product = Products::firstOrFail(); $this->assertEquals(100,$product->harga_beli); $this->assertEquals(150,$product->harga_jual);
        $this->get('/administrator/produk/create')->assertOk()->assertSee('name="harga_beli"',false)->assertSee('name="harga_jual"',false);
    }
}
