<?php

namespace Tests\Feature;

use App\Models\{Role, User};
use App\Services\ProductDashboardService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class ProductDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array']);
        DB::purge('sqlite');
        Schema::create('products', function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('name');
            foreach (['sku','category_id','uom_id'] as $field) $t->string($field)->nullable();
            foreach (['purchase_price','min_stock','max_stock'] as $field) $t->decimal($field,15,2)->nullable();
        });
        Schema::create('product_category', function (Blueprint $t) { $t->string('id')->primary(); $t->string('category_name'); });
        Schema::create('uoms', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('stok', function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('produk_id'); $t->integer('quantity');
            foreach (['gudang_id','area_id','rak_id'] as $field) $t->string($field)->nullable();
        });
        foreach (['penerimaan','pengeluaran'] as $type) {
            Schema::create($type.'_barang', function (Blueprint $t) use ($type) {
                $t->string('id')->primary(); $t->string('no_'.$type); $t->date('tanggal_'.$type); $t->string('status');
            });
            Schema::create($type.'_barang_detail', function (Blueprint $t) use ($type) {
                $t->string('id')->primary(); $t->string($type.'_id'); $t->integer('qty');
            });
        }
    }

    private function user(): void
    {
        $this->withoutMiddleware(); View::share('errors',new \Illuminate\Support\ViewErrorBag());
        $user = new User(['name'=>'Admin','username'=>'admin']); $user->id='u';
        $user->setRelation('role',new Role(['role_name'=>'admin'])); $this->actingAs($user);
    }

    public function test_stock_totals_alerts_valuation_and_units_use_actual_stock(): void
    {
        DB::table('uoms')->insert([['id'=>'pcs','name'=>'pcs'],['id'=>'roll','name'=>'roll']]);
        DB::table('product_category')->insert(['id'=>'c','category_name'=>'Kabel']);
        foreach ([['normal',20,5,50,10,'pcs'],['low',3,5,50,null,'pcs'],['empty',0,5,50,10,'pcs'],['over',60,5,50,2,'pcs'],['unset',2,null,null,0,'roll'],['negative',-1,5,50,10,'pcs']] as [$id,$qty,$min,$max,$cost,$unit]) {
            DB::table('products')->insert(['id'=>$id,'name'=>$id,'sku'=>$id==='unset'?null:$id,'category_id'=>$id==='unset'?null:'c','uom_id'=>$unit,'purchase_price'=>$cost,'min_stock'=>$min,'max_stock'=>$max]);
            if ($qty) DB::table('stok')->insert(['id'=>$id,'produk_id'=>$id,'quantity'=>$qty,'gudang_id'=>'w','area_id'=>'a','rak_id'=>$id==='unset'?null:'r']);
        }
        $data = app(ProductDashboardService::class)->data(30);
        foreach (['products'=>6,'stocked'=>4,'empty'=>2,'low'=>1,'over'=>1,'normal'=>2,'purchase_value'=>320,'missing_cost'=>1,'valued_products'=>3,'negative'=>1,'missing_min'=>1,'missing_sku'=>1,'unlocated'=>1] as $key=>$value) $this->assertEquals($value,$data['summary'][$key],$key);
        $this->assertEquals(['pcs'=>83,'roll'=>2],$data['stockByUnit']->pluck('quantity','unit')->all());
        $this->assertCount(3,$data['stockAlerts']);
        $this->assertEquals('over',$data['overstock']->first()->id);
        $this->assertEquals(1,$data['categories']->firstWhere('name','Tanpa kategori')['products']);
        $this->user(); $this->get('/administrator/dashboard-produk')->assertOk()->assertSee('Estimasi Nilai Stok')->assertSee('Prioritas Pengisian Stok');
    }

    public function test_movements_include_completed_pos_and_exclude_cancelled_draft_and_old_documents(): void
    {
        $today = CarbonImmutable::now('Asia/Jakarta')->startOfDay();
        foreach ([['penerimaan','in','completed',0,15],['pengeluaran','POS-1','completed',0,4],['pengeluaran','OUT-1','completed',1,3],['pengeluaran','cancel','cancelled',0,100],['penerimaan','draft','draft',0,100],['pengeluaran','old','completed',7,100]] as [$type,$id,$status,$days,$qty]) {
            DB::table($type.'_barang')->insert(['id'=>$id,'no_'.$type=>$id,'tanggal_'.$type=>$today->subDays($days)->toDateString(),'status'=>$status]);
            DB::table($type.'_barang_detail')->insert(['id'=>$id,'qty'=>$qty,$type.'_id'=>$id]);
        }
        $data = app(ProductDashboardService::class)->data(7);
        $this->assertEquals(15,$data['movements']['incoming_quantity']);
        $this->assertEquals(7,$data['movements']['outgoing_quantity']);
        $this->assertEquals(2,$data['movements']['outgoing_count']);
        $this->assertCount(7,$data['chart']['labels']);
        $this->assertEquals(4,$data['chart']['outgoing'][6]);
        $this->assertEquals(3,$data['chart']['outgoing'][5]);
        $this->assertCount(3,$data['recentDocuments']);
    }

    public function test_empty_dashboard_and_legacy_redirect_preserve_selected_period(): void
    {
        $this->user();
        $this->get('/administrator/dashboard-produk?days=7')->assertOk()->assertSee('Belum tersedia')->assertSee('Belum ada data produk.')->assertViewHas('movements',fn ($value) => $value['days']===7);
        $this->get('/administrator/dashboardProduk?days=90')->assertStatus(301)->assertRedirect('/administrator/dashboard-produk?days=90');
    }
}
