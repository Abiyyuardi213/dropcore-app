<?php

namespace Tests\Feature;

use App\Models\{Products, Role, Stok, Uom, User};
use App\Services\WarehouseCapacityService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class WarehouseCapacityTest extends TestCase
{
    private function stock(int $quantity, string $unit = 'pcs', ?float $weight = null, ?string $dimensions = null): Stok
    {
        $product = new Products(['name'=>'Produk', 'weight'=>$weight, 'dimensi'=>$dimensions]);
        $product->setRelation('uom', new Uom(['name'=>$unit]));
        $stock = new Stok(['produk_id'=>'p','quantity'=>$quantity]);
        return $stock->setRelation('produk', $product);
    }

    public function test_capacity_thresholds_remaining_space_and_overflow_are_calculated(): void
    {
        $service = new WarehouseCapacityService();
        foreach ([[0,'free'],[59,'free'],[60,'moderate'],[80,'near'],[99,'near'],[100,'full'],[120,'full']] as [$quantity,$status]) {
            $capacity = $service->measure('100 pcs', collect([$this->stock($quantity)]));
            $this->assertTrue($capacity['known']); $this->assertEquals($status, $capacity['status']);
            $this->assertEquals($quantity, $capacity['used']);
            $this->assertEquals(max(0,100-$quantity), $capacity['remaining']);
            $this->assertLessThanOrEqual(100, $capacity['bar']);
        }
        $this->assertEquals(20, $capacity['excess']);
    }

    public function test_weight_volume_and_localized_capacity_numbers_use_matching_units(): void
    {
        $service = new WarehouseCapacityService();
        $weight = $service->measure('1.000 kg', collect([$this->stock(100,'box',2.5)]));
        $this->assertEquals(1000,$weight['limit']); $this->assertEquals(250,$weight['used']); $this->assertEquals(25,$weight['percent']);
        $this->assertEquals(25, $service->measure('1 ton', collect([$this->stock(100,'box',2.5)]))['percent']);
        $volume = $service->measure('1 m³', collect([$this->stock(100,'box',null,'10x20x30 cm')]));
        $this->assertTrue($volume['known']); $this->assertEqualsWithDelta(.6,$volume['used'],.000001); $this->assertEquals(60,$volume['percent']);
        $this->assertEquals(80, $service->measure('100 pallet', collect([$this->stock(80,'palet')]))['percent']);
        $this->assertEquals(50, $service->measure('1,5 m3', collect([$this->stock(1,'box',null,'1x1x0,75 m')]))['percent']);
    }

    public function test_missing_metadata_or_incompatible_units_are_unknown_instead_of_falsely_free(): void
    {
        $service = new WarehouseCapacityService();
        foreach ([null, '0 kg', '100 m2', '500 pallet / 100 m3'] as $raw) {
            $this->assertFalse($service->measure($raw,collect())['known']);
        }
        $incomplete = $service->measure('100 kg', collect([$this->stock(10,'box',2),$this->stock(10,'box')]));
        $this->assertFalse($incomplete['known']); $this->assertNull($incomplete['percent']); $this->assertNull($incomplete['used']);
        $this->assertFalse($service->measure('100 m3',collect([$this->stock(10,'box',null,'10x20x30')]))['known']);
        $this->assertFalse($service->measure('100 pallet',collect([$this->stock(10,'pcs')]))['known']);
        $this->assertEquals(0,$service->measure('100 kg', collect())['used']);
    }

    private function schema(): void
    {
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array']);
        DB::purge('sqlite');
        Schema::create('gudang', function (Blueprint $t) { $t->string('id')->primary(); $t->string('kode_gudang'); $t->string('nama_gudang'); $t->string('lokasi')->nullable(); $t->string('kapasitas')->nullable(); $t->boolean('gudang_status')->default(true); $t->timestamps(); });
        Schema::create('area_gudang', function (Blueprint $t) { $t->string('id')->primary(); $t->string('gudang_id'); $t->string('kode_area'); $t->string('nama_area'); $t->string('kapasitas_area')->nullable(); foreach (['jenis_area','pic','suhu','kelembaban','keterangan'] as $field) $t->string($field)->nullable(); $t->boolean('area_status')->default(true); $t->timestamps(); });
        Schema::create('rak_gudang', function (Blueprint $t) { $t->string('id')->primary(); $t->string('gudang_id'); $t->string('area_id'); $t->string('kode_rak'); $t->string('posisi')->nullable(); $t->string('kapasitas_max')->nullable(); $t->boolean('rak_status')->default(true); $t->timestamps(); });
        Schema::create('products', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); $t->string('uom_id')->nullable(); $t->decimal('weight',8,2)->nullable(); $t->decimal('berat',8,2)->nullable(); $t->string('dimensi')->nullable(); });
        Schema::create('uoms', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('stok', function (Blueprint $t) { $t->string('id')->primary(); $t->string('gudang_id'); $t->string('area_id')->nullable(); $t->string('rak_id')->nullable(); $t->string('produk_id'); $t->integer('quantity'); $t->timestamps(); });
        Schema::create('mutasi_stok', function (Blueprint $t) { $t->string('id')->primary(); $t->timestamps(); });
    }

    public function test_hierarchy_totals_include_unallocated_stock_without_double_counting(): void
    {
        $this->schema();
        DB::table('gudang')->insert(['id'=>'w','nama_gudang'=>'Gudang Utama','kode_gudang'=>'WH-01','kapasitas'=>'100 unit']);
        DB::table('area_gudang')->insert(['id'=>'a','gudang_id'=>'w','nama_area'=>'Area Kabel','kode_area'=>'A01','kapasitas_area'=>'100 unit']);
        DB::table('rak_gudang')->insert([
            ['id'=>'r1','gudang_id'=>'w','area_id'=>'a','kode_rak'=>'R01','kapasitas_max'=>'50 pcs'],
            ['id'=>'r2','gudang_id'=>'w','area_id'=>'a','kode_rak'=>'R02','kapasitas_max'=>'100 pcs'],
        ]);
        DB::table('uoms')->insert(['id'=>'pcs','name'=>'pcs']);
        DB::table('products')->insert(['id'=>'p','name'=>'Kabel','uom_id'=>'pcs']);
        DB::table('stok')->insert([
            ['id'=>'s1','gudang_id'=>'w','area_id'=>'a','rak_id'=>'r1','produk_id'=>'p','quantity'=>50],
            ['id'=>'s2','gudang_id'=>'w','area_id'=>'a','rak_id'=>'r2','produk_id'=>'p','quantity'=>40],
            ['id'=>'s3','gudang_id'=>'w','area_id'=>null,'rak_id'=>null,'produk_id'=>'p','quantity'=>20],
        ]);
        $map = app(WarehouseCapacityService::class)->warehouses();
        $this->assertEquals(110,$map[0]['capacity']['used']); $this->assertEquals('full',$map[0]['capacity']['status']);
        $this->assertEquals(20,$map[0]['unassigned']);
        $this->assertEquals(90,$map[0]['areas'][0]['capacity']['used']);
        $this->assertEquals('full',$map[0]['areas'][0]['racks'][0]['capacity']['status']);
        $this->assertEquals('free',$map[0]['areas'][0]['racks'][1]['capacity']['status']);
        $this->dashboardUser();
        $this->get('/administrator/dashboardGudang')->assertOk()->assertSee('Visualisasi Kapasitas Gudang')->assertSee('Gudang Utama')->assertSee('Area Kabel')->assertSee('R01')->assertSee('Melebihi kapasitas');
        $this->get('/administrator/gudang')->assertOk()->assertSee('Daftar Gudang &amp; Kapasitas', false)->assertSee('warehouse-card-grid')->assertSee('Area Kabel')->assertSee('Melebihi kapasitas')
            ->assertSee(route('gudang.show','w'))->assertSee(route('gudang.edit','w'))->assertSee(route('gudang.destroy','w'))->assertSee(route('gudang.toggleStatus','w'))
            ->assertDontSee('id="gudangTable"', false)->assertDontSee('jquery.dataTables.min.js', false);
        $this->get('/administrator/area-gudang')->assertOk()->assertSee('Daftar Area &amp; Kapasitas', false)->assertSee('Area Kabel')->assertSee('Gudang Utama')->assertSee('Hampir penuh')->assertSee('R01')
            ->assertSee(route('area-gudang.show','a'))->assertSee(route('area-gudang.edit','a'))->assertSee(route('area-gudang.destroy','a'))->assertSee(route('area-gudang.toggleStatus','a'))
            ->assertDontSee('id="areaGudangTable"', false)->assertDontSee('/administrator/areaGudang');
        $this->get('/administrator/rak-gudang')->assertOk()->assertSee('Daftar Rak &amp; Kapasitas', false)->assertSee('R01')->assertSee('R02')->assertSee('Gudang Utama')->assertSee('Area Kabel')
            ->assertSee('Kondisi Kapasitas')->assertSee('data-capacity-status="full"', false)->assertSee('data-capacity-status="free"', false)
            ->assertSee(route('rak-gudang.show','r1'))->assertSee(route('rak-gudang.edit','r1'))->assertSee(route('rak-gudang.destroy','r1'))->assertSee(route('rak-gudang.toggleStatus','r1'))
            ->assertDontSee('id="rakGudangTable"', false)->assertViewHas('raks', fn ($racks) => $racks->count() === 2 && (float) $racks[0]['capacity']['used'] === 50.0);
    }

    private function dashboardUser(): void
    {
        $this->withoutMiddleware();
        View::share('errors',new \Illuminate\Support\ViewErrorBag());
        $user = new User(['name'=>'Admin Gudang','username'=>'admin']); $user->id='u';
        $user->setRelation('role',new Role(['role_name'=>'admin'])); $this->actingAs($user);
    }

    public function test_empty_warehouse_dashboard_renders_without_invented_utilization(): void
    {
        $this->schema(); $this->dashboardUser();
        $this->get('/administrator/dashboardGudang')->assertOk()->assertSee('Belum ada data gudang')->assertViewHas('warehouseMap', fn ($map) => $map->isEmpty());
        $this->get('/administrator/gudang')->assertOk()->assertSee('Belum ada data gudang')->assertSee('Tambah Gudang');
        $this->get('/administrator/area-gudang')->assertOk()->assertSee('Belum ada data area gudang')->assertSee('Tambah Area Gudang');
        $this->get('/administrator/rak-gudang')->assertOk()->assertSee('Belum ada data rak gudang')->assertSee('Tambah Rak Gudang');
    }

    public function test_legacy_area_urls_redirect_without_losing_action_paths_or_http_methods(): void
    {
        $this->schema(); $this->dashboardUser();
        foreach (['','/create','/a','/a/edit'] as $path) {
            $this->get('/administrator/areaGudang'.$path)->assertStatus(301)->assertRedirect(url('administrator/area-gudang'.$path));
        }
        $this->get('/administrator/areaGudang?filter=test')->assertRedirect(url('administrator/area-gudang').'?filter=test');
        $this->post('/administrator/areaGudang', ['nama_area'=>'Legacy'])->assertStatus(308)->assertRedirect(route('area-gudang.index'));
        $this->put('/administrator/areaGudang/a', [])->assertStatus(308)->assertRedirect(route('area-gudang.update','a'));
        $this->post('/administrator/areaGudang/a/toggle-status')->assertStatus(308)->assertRedirect(route('area-gudang.toggleStatus','a'));
        $this->assertEquals(0, DB::table('area_gudang')->count());
    }

    public function test_area_creation_edit_status_and_delete_work_on_new_routes(): void
    {
        $this->schema(); $this->dashboardUser();
        Schema::create('activity_logs', function (Blueprint $t) { $t->string('id')->primary(); foreach (['module','action','description','user_id'] as $field) $t->string($field)->nullable(); $t->timestamps(); });
        DB::table('gudang')->insert(['id'=>'w','nama_gudang'=>'Gudang Uji','kode_gudang'=>'WH-01']);
        $data = ['gudang_id'=>'w','nama_area'=>'Area Uji','kapasitas_area'=>'100 unit','area_status'=>1];
        $this->get('/administrator/area-gudang/create')->assertOk()->assertSee(route('area-gudang.store'));
        $this->post('/administrator/area-gudang',$data)->assertSessionHasNoErrors()->assertRedirect(route('area-gudang.index'));
        $id = DB::table('area_gudang')->value('id');
        $this->get('/administrator/area-gudang/'.$id.'/edit')->assertOk()->assertSee(route('area-gudang.update',$id));
        $this->get('/administrator/area-gudang/'.$id)->assertOk()->assertSee('Area Uji');
        $data['nama_area'] = 'Area Diperbarui';
        $this->put('/administrator/area-gudang/'.$id,$data)->assertSessionHasNoErrors()->assertRedirect(route('area-gudang.index'));
        $this->assertEquals('Area Diperbarui',DB::table('area_gudang')->value('nama_area'));
        $this->post('/administrator/area-gudang/'.$id.'/toggle-status')->assertOk()->assertJsonPath('active',false);
        $this->delete('/administrator/area-gudang/'.$id)->assertRedirect(route('area-gudang.index'));
        $this->assertEquals(0,DB::table('area_gudang')->count());
    }

    public function test_new_area_route_uses_warehouse_permission(): void
    {
        $warehouseRole = new Role(['role_name'=>'Operator Gudang','permissions'=>['gudang']]);
        $productRole = new Role(['role_name'=>'Operator Produk','permissions'=>['produk']]);
        foreach (['/administrator/area-gudang','/administrator/area-gudang/create','/administrator/area-gudang/a/edit'] as $path) {
            $request = \Illuminate\Http\Request::create($path);
            $this->assertTrue($warehouseRole->canAccessRequest($request));
            $this->assertFalse($productRole->canAccessRequest($request));
        }
    }

    public function test_rack_status_switch_returns_actual_state_without_changing_stock(): void
    {
        $this->schema(); $this->dashboardUser();
        Schema::create('activity_logs', function (Blueprint $t) { $t->string('id')->primary(); foreach (['module','action','description','user_id'] as $field) $t->string($field)->nullable(); $t->timestamps(); });
        DB::table('gudang')->insert(['id'=>'w','nama_gudang'=>'Gudang Uji','kode_gudang'=>'WH-01']);
        DB::table('area_gudang')->insert(['id'=>'a','gudang_id'=>'w','nama_area'=>'Area Uji','kode_area'=>'A01']);
        DB::table('rak_gudang')->insert(['id'=>'r','gudang_id'=>'w','area_id'=>'a','kode_rak'=>'R01']);
        DB::table('stok')->insert(['id'=>'s','gudang_id'=>'w','area_id'=>'a','rak_id'=>'r','produk_id'=>'p','quantity'=>10]);
        $this->post('/administrator/rak-gudang/r/toggle-status')->assertOk()->assertJsonPath('active',false);
        $this->assertEquals(0,DB::table('rak_gudang')->value('rak_status'));
        $this->post('/administrator/rak-gudang/r/toggle-status')->assertOk()->assertJsonPath('active',true);
        $this->assertEquals(10,DB::table('stok')->value('quantity'));
    }
}
