<?php

namespace Tests\Feature;

use App\Models\{Role, User};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, View};
use Tests\TestCase;

class ProductActivityFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','session.driver'=>'array']);
        DB::purge('sqlite'); $this->withoutMiddleware();
        Schema::create('products',function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); $t->string('sku'); });
        Schema::create('users',function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('riwayat_aktivitas_produk',function (Blueprint $t) {
            $t->string('id')->primary(); $t->string('produk_id')->nullable(); $t->string('user_id')->nullable();
            $t->string('tipe_aktivitas'); $t->string('deskripsi')->nullable(); $t->timestamps();
        });
        DB::table('products')->insert(['id'=>'p','name'=>'Kabel','sku'=>'SKU-1']);
        DB::table('users')->insert(['id'=>'u','name'=>'Petugas']);
        foreach ([['one','p','u','tambah_produk','2026-10-01 00:00:00'],['two','p','u','edit_produk','2026-10-01 23:59:59'],['three','deleted',null,'hapus_produk','2026-10-02 00:00:00'],['four',null,'deleted','stok_masuk','2026-10-02 00:00:00']] as [$id,$product,$user,$type,$date]) {
            DB::table('riwayat_aktivitas_produk')->insert(['id'=>$id,'produk_id'=>$product,'user_id'=>$user,'tipe_aktivitas'=>$type,'created_at'=>$date,'updated_at'=>$date]);
        }
        View::share('errors',new \Illuminate\Support\ViewErrorBag());
        $user = new User(['name'=>'Admin','username'=>'admin']); $user->id='admin';
        $user->setRelation('role',new Role(['role_name'=>'admin'])); $this->actingAs($user);
    }

    public function test_combined_filters_date_boundaries_and_reset(): void
    {
        $this->get('/administrator/riwayat-aktivitas-produk?date_from=2026-10-01&date_to=2026-10-01&produk_id=p&user_id=u&tipe_aktivitas=edit_produk')
            ->assertOk()->assertViewHas('riwayats',fn ($rows) => $rows->pluck('id')->all()===['two'])->assertSee('SKU-1')
            ->assertViewHas('activityTypes',fn ($types) => $types->count()===4);
        $this->get('/administrator/riwayat-aktivitas-produk?date_to=2026-10-01')->assertOk()->assertViewHas('riwayats',fn ($rows) => $rows->count()===2);
        $this->get('/administrator/riwayat-aktivitas-produk?produk_id=p&tipe_aktivitas=hapus_produk')->assertOk()->assertViewHas('riwayats',fn ($rows) => $rows->isEmpty());
        $this->get('/administrator/riwayat-aktivitas-produk')->assertOk()->assertViewHas('riwayats',fn ($rows) => $rows->count()===4);
    }

    public function test_missing_relations_can_be_filtered_and_reversed_dates_are_rejected(): void
    {
        $this->get('/administrator/riwayat-aktivitas-produk?produk_id=__deleted&user_id=__system')->assertOk()->assertViewHas('riwayats',fn ($rows) => $rows->count()===2);
        $this->from('/administrator/riwayat-aktivitas-produk')->get('/administrator/riwayat-aktivitas-produk?date_from=2026-10-02&date_to=2026-10-01')->assertSessionHasErrors('date_to');
    }

    public function test_detail_shows_full_description_product_and_only_related_activity(): void
    {
        $description = str_repeat('Deskripsi lengkap aktivitas. ',12).'<script>alert(1)</script>';
        DB::table('riwayat_aktivitas_produk')->where('id','one')->update(['deskripsi'=>$description]);
        $this->get('/administrator/riwayat-aktivitas-produk/one')->assertOk()
            ->assertSee('Tambah Produk')->assertSee('SKU-1')->assertSee($description)
            ->assertDontSee('<script>alert(1)</script>',false)->assertSee('Sedang dilihat')
            ->assertSee('Lihat Detail Produk')->assertSee('nilai sebelum dan sesudah')
            ->assertViewHas('relatedActivities',fn ($rows) => $rows->pluck('id')->all()===['two','one']);
    }

    public function test_detail_handles_deleted_product_missing_user_and_null_product(): void
    {
        $this->get('/administrator/riwayat-aktivitas-produk/three')->assertOk()->assertSee('Produk Tidak Tersedia')
            ->assertSee('System / Tidak tercatat')->assertDontSee('Lihat Detail Produk');
        $this->get('/administrator/riwayat-aktivitas-produk/four')->assertOk()->assertSee('Pengguna tidak tersedia')
            ->assertViewHas('relatedActivities',fn ($rows) => $rows->isEmpty());
        $this->get('/administrator/riwayat-aktivitas-produk/missing')->assertNotFound();
    }
}
