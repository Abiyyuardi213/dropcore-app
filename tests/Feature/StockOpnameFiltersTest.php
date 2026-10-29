<?php

namespace Tests\Feature;

use App\Models\{Role, User};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class StockOpnameFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array']);
        DB::purge('sqlite'); $this->withoutMiddleware();
        Schema::create('gudang',function (Blueprint $t) { $t->string('id')->primary(); $t->string('nama_gudang'); });
        Schema::create('users',function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('stock_opnames',function (Blueprint $t) {
            $t->string('id')->primary(); $t->dateTime('tanggal'); $t->string('gudang_id'); $t->string('user_id');
            $t->string('status'); $t->string('keterangan')->nullable(); $t->timestamps();
        });
        Schema::create('stock_opname_details',function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('opname_id'); $t->integer('qty_sistem'); $t->integer('qty_fisik')->nullable();
        });
        DB::table('gudang')->insert([['id'=>'a','nama_gudang'=>'Gudang A'],['id'=>'b','nama_gudang'=>'Gudang B']]);
        DB::table('users')->insert([['id'=>'u1','name'=>'Petugas A'],['id'=>'u2','name'=>'Petugas B']]);
        foreach ([['match','processed','a','u1','2026-10-01 23:59:59'],['diff','processed','b','u2','2026-10-02 00:00:00'],['partial','draft','a','u1','2026-10-01 00:00:00'],['empty','draft','a','u1','2026-10-03 00:00:00'],['mixed','draft','b','u2','2026-10-03 00:00:00']] as [$id,$status,$warehouse,$user,$date]) {
            DB::table('stock_opnames')->insert(['id'=>$id,'status'=>$status,'gudang_id'=>$warehouse,'user_id'=>$user,'tanggal'=>$date,'created_at'=>$date,'updated_at'=>$date]);
        }
        foreach ([['match',10,10],['diff',10,8],['partial',10,10],['partial',10,null],['mixed',10,8],['mixed',10,null]] as $index=>[$id,$system,$physical]) {
            DB::table('stock_opname_details')->insert(['id'=>(string)$index,'opname_id'=>$id,'qty_sistem'=>$system,'qty_fisik'=>$physical]);
        }
        View::share('errors',new \Illuminate\Support\ViewErrorBag());
        $user = new User(['name'=>'Admin','username'=>'admin']); $user->id='admin';
        $user->setRelation('role',new Role(['role_name'=>'admin'])); $this->actingAs($user);
    }

    public function test_combined_filters_date_boundaries_and_reset(): void
    {
        $this->get('/administrator/stock-opname?date_from=2026-10-01&date_to=2026-10-01&gudang_id=a&user_id=u1&status=processed&result=matched')
            ->assertOk()->assertViewHas('data',fn ($rows) => $rows->pluck('id')->all()===['match'])->assertSee('Terapkan Filter')
            ->assertViewHas('petugas',fn ($users) => $users->count()===2);
        $this->get('/administrator/stock-opname?date_to=2026-10-01')->assertOk()->assertViewHas('data',fn ($rows) => $rows->count()===2);
        $this->get('/administrator/stock-opname?gudang_id=a&user_id=u2')->assertOk()->assertViewHas('data',fn ($rows) => $rows->isEmpty());
        $this->get('/administrator/stock-opname')->assertOk()->assertViewHas('data',fn ($rows) => $rows->count()===5);
    }

    public function test_matching_requires_all_items_counted_and_differences_do_not_cancel_each_other(): void
    {
        $this->get('/administrator/stock-opname?result=matched')->assertOk()->assertViewHas('data',fn ($rows) => $rows->pluck('id')->all()===['match']);
        $this->get('/administrator/stock-opname?result=incomplete')->assertOk()->assertViewHas('data',fn ($rows) => $rows->pluck('id')->sort()->values()->all()===['empty','mixed','partial']);
        // Opposite discrepancies must still count as a difference, even when their sum is zero.
        DB::table('stock_opname_details')->insert(['id'=>'extra','opname_id'=>'diff','qty_sistem'=>10,'qty_fisik'=>12]);
        $this->get('/administrator/stock-opname?result=difference')->assertOk()->assertViewHas('data',fn ($rows) => $rows->pluck('id')->sort()->values()->all()===['diff','mixed']);
    }

    public function test_invalid_filter_values_and_reversed_dates_are_rejected(): void
    {
        $this->from('/administrator/stock-opname')->get('/administrator/stock-opname?status=invalid&result=invalid')->assertSessionHasErrors(['status','result']);
        $this->from('/administrator/stock-opname')->get('/administrator/stock-opname?date_from=2026-10-02&date_to=2026-10-01')->assertSessionHasErrors('date_to');
    }
}
