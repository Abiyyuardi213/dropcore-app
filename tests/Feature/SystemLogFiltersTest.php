<?php

namespace Tests\Feature;

use App\Models\{Role, User};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class SystemLogFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array']);
        DB::purge('sqlite'); $this->withoutMiddleware();
        Schema::create('users',function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('activity_logs',function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('module'); $t->string('action');
            $t->string('description')->nullable(); $t->string('user_id')->nullable(); $t->timestamps();
        });
        DB::table('users')->insert(['id'=>'u','name'=>'Petugas']);
        foreach ([['one','product','create','Kabel baru','u','2026-10-01 00:00:00'],['two','product','update','Ubah kabel','u','2026-10-01 23:59:59'],['three','auth','login','Masuk sistem',null,'2026-10-02 00:00:00'],['four','product','delete','Hapus kabel','deleted','2026-10-02 00:00:00']] as [$id,$module,$action,$description,$user,$date]) {
            DB::table('activity_logs')->insert(['id'=>$id,'module'=>$module,'action'=>$action,'description'=>$description,'user_id'=>$user,'created_at'=>$date,'updated_at'=>$date]);
        }
        View::share('errors',new \Illuminate\Support\ViewErrorBag());
        $user = new User(['name'=>'Admin','username'=>'admin']); $user->id='admin';
        $user->setRelation('role',new Role(['role_name'=>'admin'])); $this->actingAs($user);
    }

    public function test_combined_filters_search_system_logs_and_reset(): void
    {
        $this->get('/administrator/riwayat-logs?date_from=2026-10-01&date_to=2026-10-01&module=product&action=update&user_id=u&q=kabel')
            ->assertOk()->assertViewHas('logs',fn ($rows) => $rows->pluck('id')->all()===['two'])
            ->assertViewHas('actions',fn ($actions) => $actions->count()===4)->assertSee('Terapkan Filter');
        $this->get('/administrator/riwayat-logs?date_to=2026-10-01')->assertOk()->assertViewHas('logs',fn ($rows) => $rows->total()===2);
        $this->get('/administrator/riwayat-logs?user_id=__system')->assertOk()->assertViewHas('logs',fn ($rows) => $rows->total()===2);
        $this->get('/administrator/riwayat-logs?q=tidak-ada')->assertOk()->assertViewHas('logs',fn ($rows) => $rows->total()===0);
        $this->get('/administrator/riwayat-logs')->assertOk()->assertViewHas('logs',fn ($rows) => $rows->total()===4);
    }

    public function test_pagination_keeps_filters_and_searches_beyond_first_page(): void
    {
        for ($i=0;$i<51;$i++) DB::table('activity_logs')->insert(['id'=>'extra-'.$i,'module'=>'warehouse','action'=>'update','description'=>'Audit gudang '.$i,'user_id'=>'u','created_at'=>'2026-10-03 10:00:00','updated_at'=>'2026-10-03 10:00:00']);
        $this->get('/administrator/riwayat-logs?module=warehouse&q=Audit')->assertOk()->assertViewHas('logs',fn ($rows) => $rows->total()===51 && str_contains($rows->nextPageUrl(),'module=warehouse') && str_contains($rows->nextPageUrl(),'q=Audit'));
        $this->get('/administrator/riwayat-logs?module=warehouse&q=Audit&page=2')->assertOk()->assertViewHas('logs',fn ($rows) => $rows->count()===1 && $rows->firstItem()===51);
        $this->get('/administrator/riwayat-logs?q=Audit%20gudang%2050')->assertOk()->assertViewHas('logs',fn ($rows) => $rows->total()===1);
        $this->from('/administrator/riwayat-logs')->get('/administrator/riwayat-logs?date_from=2026-10-02&date_to=2026-10-01')->assertSessionHasErrors('date_to');
    }
}
