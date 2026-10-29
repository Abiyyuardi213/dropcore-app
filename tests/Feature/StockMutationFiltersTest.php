<?php

namespace Tests\Feature;

use App\Models\{Role, User};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class StockMutationFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array']);
        DB::purge('sqlite'); $this->withoutMiddleware();
        foreach (['products'=>'name','gudang'=>'nama_gudang','kondisi_barang'=>'nama_kondisi','users'=>'name','area_gudang'=>'nama_area','rak_gudang'=>'kode_rak'] as $table=>$name) {
            Schema::create($table,function (Blueprint $t) use ($table,$name) { $t->string('id')->primary(); $t->string($name); if ($table==='products') $t->string('sku')->nullable(); });
        }
        Schema::create('mutasi_stok',function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('jenis_mutasi'); $t->dateTime('tanggal_mutasi'); $t->integer('quantity');
            foreach (['produk_id','gudang_asal_id','gudang_tujuan_id','area_asal_id','area_tujuan_id','rak_asal_id','rak_tujuan_id','user_id','kondisi_id','referensi'] as $field) $t->string($field)->nullable();
            $t->timestamps();
        });
        DB::table('products')->insert(['id'=>'p','name'=>'Kabel','sku'=>'SKU-1']);
        DB::table('gudang')->insert([['id'=>'a','nama_gudang'=>'Gudang A'],['id'=>'b','nama_gudang'=>'Gudang B']]);
        DB::table('kondisi_barang')->insert(['id'=>'new','nama_kondisi'=>'Baru']);
        foreach ([['one','pindah','a','b','2026-10-01 23:59:59'],['two','pindah','b','a','2026-10-02 00:00:00'],['three','masuk',null,'a','2026-10-01 00:00:00']] as [$id,$type,$from,$to,$date]) {
            DB::table('mutasi_stok')->insert(['id'=>$id,'jenis_mutasi'=>$type,'produk_id'=>'p','kondisi_id'=>'new','gudang_asal_id'=>$from,'gudang_tujuan_id'=>$to,'tanggal_mutasi'=>$date,'quantity'=>5,'created_at'=>$date,'updated_at'=>$date]);
        }
        View::share('errors',new \Illuminate\Support\ViewErrorBag());
        $user = new User(['name'=>'Admin','username'=>'admin']); $user->id='u';
        $user->setRelation('role',new Role(['role_name'=>'admin'])); $this->actingAs($user);
    }

    public function test_combined_filters_dates_empty_results_and_reset(): void
    {
        $this->get('/administrator/mutasi-stok?date_from=2026-10-01&date_to=2026-10-01&jenis_mutasi=pindah&produk_id=p&gudang_asal_id=a&gudang_tujuan_id=b&kondisi_id=new')
            ->assertOk()->assertViewHas('mutasi',fn ($rows) => $rows->pluck('id')->all()===['one'])->assertSee('SKU-1')->assertSee('Terapkan Filter');
        $this->get('/administrator/mutasi-stok?date_to=2026-10-01')->assertOk()->assertViewHas('mutasi',fn ($rows) => $rows->count()===2);
        $this->get('/administrator/mutasi-stok?gudang_asal_id=b&gudang_tujuan_id=b')->assertOk()->assertViewHas('mutasi',fn ($rows) => $rows->isEmpty());
        $this->get('/administrator/mutasi-stok')->assertOk()->assertViewHas('mutasi',fn ($rows) => $rows->count()===3);
    }

    public function test_invalid_type_and_date_range_are_rejected(): void
    {
        $this->from('/administrator/mutasi-stok')->get('/administrator/mutasi-stok?jenis_mutasi=unknown')->assertSessionHasErrors('jenis_mutasi');
        $this->from('/administrator/mutasi-stok')->get('/administrator/mutasi-stok?date_from=2026-10-02&date_to=2026-10-01')->assertSessionHasErrors('date_to');
    }
}
