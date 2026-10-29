<?php

namespace Tests\Feature;

use App\Models\{Role, User};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class AdminOrderFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array']);
        DB::purge('sqlite'); $this->withoutMiddleware();
        Schema::create('users',function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('orders',function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('user_id')->nullable(); $t->string('order_number');
            $t->string('status'); $t->string('proof_of_payment')->nullable(); $t->decimal('total_price',15,2); $t->timestamps();
        });
        DB::table('users')->insert([['id'=>'a','name'=>'Distributor A'],['id'=>'b','name'=>'Distributor B']]);
        foreach ([['one','a','waiting_confirmation','proof.jpg','2026-10-01 00:00:00'],['two','a','completed',null,'2026-10-01 23:59:59'],['three','b','completed','','2026-10-02 10:00:00']] as [$id,$user,$status,$proof,$date]) {
            DB::table('orders')->insert(['id'=>$id,'user_id'=>$user,'order_number'=>$id,'status'=>$status,'proof_of_payment'=>$proof,'total_price'=>1000,'created_at'=>$date,'updated_at'=>$date]);
        }
        View::share('errors',new \Illuminate\Support\ViewErrorBag());
        $user = new User(['name'=>'Admin','username'=>'admin']); $user->id='admin';
        $user->setRelation('role',new Role(['role_name'=>'admin'])); $this->actingAs($user);
    }

    public function test_filters_combine_and_reset_without_losing_distributor_choices(): void
    {
        $this->get('/administrator/orders?date_from=2026-10-01&date_to=2026-10-01&distributor=a&status=completed&proof=missing')
            ->assertOk()->assertViewHas('orders',fn ($orders) => $orders->pluck('id')->all()===['two'])
            ->assertViewHas('distributors',fn ($users) => $users->count()===2)->assertSee('Terapkan Filter');
        $this->get('/administrator/orders?proof=uploaded')->assertOk()->assertViewHas('orders',fn ($orders) => $orders->pluck('id')->all()===['one']);
        $this->get('/administrator/orders?proof=missing')->assertOk()->assertViewHas('orders',fn ($orders) => $orders->count()===2);
        $this->get('/administrator/orders')->assertOk()->assertViewHas('orders',fn ($orders) => $orders->count()===3);
        $this->get('/administrator/orders?distributor=b&status=waiting_payment')->assertOk()->assertViewHas('orders',fn ($orders) => $orders->isEmpty());
    }

    public function test_invalid_status_and_reversed_dates_are_rejected(): void
    {
        $this->from('/administrator/orders')->get('/administrator/orders?status=invalid')->assertRedirect('/administrator/orders')->assertSessionHasErrors('status');
        $this->from('/administrator/orders')->get('/administrator/orders?date_from=2026-10-02&date_to=2026-10-01')->assertSessionHasErrors('date_to');
    }
}
