<?php

namespace Tests\Feature;

use App\Models\{Role, User};
use Database\Seeders\{KasirRoleSeeder, RoleSeeder};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Hash, Schema};
use Tests\TestCase;

class KasirAuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array', 'cache.default' => 'array']);
        DB::purge('sqlite');
        Schema::create('role', function (Blueprint $table) {
            $table->string('id')->primary(); $table->string('role_name'); $table->string('role_description')->nullable();
            $table->boolean('role_status')->default(true); $table->json('permissions')->nullable(); $table->timestamps();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary(); $table->string('name'); $table->string('username'); $table->string('email');
            $table->string('password'); $table->string('role_id'); $table->string('status_kepegawaian'); $table->rememberToken(); $table->timestamps();
        });
        $this->seed(RoleSeeder::class);
        Schema::create('pengeluaran_barang', function (Blueprint $t) {
            $t->increments('id'); foreach (['tipe_penerima','nama_konsumen','telepon_konsumen','alamat_konsumen','status'] as $field) $t->string($field)->nullable();
        });
        $cashierMigration = require base_path('database/migrations/2026_10_26_120000_create_cashier_sales_tables.php');
        $cashierMigration->up();
    }

    private function user(string $role = 'kasir'): User
    {
        return User::create(['name' => 'Petugas '.ucfirst($role), 'username' => $role, 'email' => $role.'@example.com', 'role_id' => DB::table('role')->where('role_name', $role)->value('id'), 'status_kepegawaian' => 'aktif', 'password' => Hash::make('password-kasir')]);
    }

    public function test_login_form_and_guest_redirect_use_cashier_portal(): void
    {
        $this->get('/kasir/login')->assertOk()->assertSee('Login Kasir')->assertSee(route('kasir.login.attempt'), false);
        $this->get('/kasir/dashboard')->assertRedirect('/kasir/login');
    }

    public function test_cashier_can_login_by_username_and_cannot_follow_admin_intended_url(): void
    {
        $user = $this->user();
        $this->withSession(['url.intended' => '/administrator/dashboard'])->post('/kasir/login', ['login' => 'kasir', 'password' => 'password-kasir'])->assertRedirect('/kasir/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->get('/kasir/dashboard')->assertOk()->assertSee('Dashboard Kasir')->assertSee('Penjualan Hari Ini');
        $this->get('/kasir/login')->assertRedirect('/kasir/dashboard');
        $this->get('/administrator/dashboard')->assertForbidden();
        $this->get('/administrator/product')->assertForbidden();
        $this->get('/homepage')->assertForbidden();
        $this->post('/kasir/logout')->assertRedirect('/kasir/login');
        $this->assertGuest();
    }

    public function test_cashier_can_login_by_email_and_wrong_password_is_rejected(): void
    {
        $user = $this->user();
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'wrong'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->post('/kasir/login', ['login' => $user->email, 'password' => 'password-kasir'])->assertRedirect('/kasir/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_cannot_login_to_cashier_and_cashier_cannot_login_to_admin_or_distributor(): void
    {
        $admin = $this->user('admin'); $cashier = $this->user();
        $this->post('/kasir/login', ['login' => $admin->username, 'password' => 'password-kasir'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->post('/administrator/login', ['login' => $cashier->username, 'password' => 'password-kasir'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->post('/login-distributor', ['login' => $cashier->username, 'password' => 'password-kasir'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->actingAs($admin)->get('/kasir/dashboard')->assertForbidden();
    }

    public function test_inactive_account_or_role_cannot_login_or_use_cashier_area(): void
    {
        $user = $this->user(); $user->update(['status_kepegawaian' => 'nonaktif']);
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'password-kasir'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->actingAs($user->fresh())->get('/kasir/dashboard')->assertForbidden();
        $this->post('/kasir/logout')->assertRedirect('/kasir/login');
        $user->update(['status_kepegawaian' => 'aktif']);
        DB::table('role')->where('id', $user->role_id)->update(['role_status' => false]);
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'password-kasir'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->actingAs($user->fresh())->get('/kasir/dashboard')->assertForbidden();
    }

    public function test_cashier_has_no_admin_permissions_even_if_database_contains_wildcard(): void
    {
        $user = $this->user();
        DB::table('role')->where('id', $user->role_id)->update(['permissions' => json_encode(['*', 'dashboard', 'portal_distributor'])]);
        $role = Role::findOrFail($user->role_id);
        $this->assertSame(['kasir'], $role->getEffectivePermissions());
        $this->assertFalse($role->isAdminPanelRole());
        $this->assertFalse($role->hasPermission('portal_distributor'));
        $this->assertNotContains('kasir', Role::getAdminPermissionKeys());
        $this->actingAs($user->fresh())->get('/administrator/dashboard')->assertForbidden();
    }

    public function test_role_seeding_is_idempotent_and_preserves_other_roles(): void
    {
        $id = DB::table('role')->where('role_name', 'kasir')->value('id');
        DB::table('role')->where('role_name', 'admin')->update(['permissions' => json_encode(['dashboard'])]);
        $this->seed(KasirRoleSeeder::class); $this->seed(RoleSeeder::class);
        $this->assertEquals(1, DB::table('role')->where('role_name', 'kasir')->count());
        $this->assertEquals($id, DB::table('role')->where('role_name', 'kasir')->value('id'));
        $this->assertEquals('["dashboard"]', DB::table('role')->where('role_name', 'admin')->value('permissions'));
    }

    public function test_cashier_login_is_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/kasir/login', ['login' => 'unknown', 'password' => 'wrong'])->assertSessionHasErrors('login');
        }
        $this->post('/kasir/login', ['login' => 'unknown', 'password' => 'wrong'])->assertStatus(429);
    }

    public function test_cashier_user_seeder_creates_working_credentials_without_resetting_password(): void
    {
        $this->seed(\Database\Seeders\KasirUserSeeder::class);
        $user = User::where('username', 'kasir')->firstOrFail();
        $this->assertTrue(Hash::check('Kasir123!', $user->password));
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'Kasir123!'])->assertRedirect('/kasir/dashboard');
        $this->get('/kasir/dashboard')->assertOk()->assertSee('Dashboard Kasir')->assertSee('Penjualan Hari Ini');
        $user->update(['password' => Hash::make('changed-password')]);
        $this->seed(\Database\Seeders\KasirUserSeeder::class);
        $this->assertEquals(1, User::where('username', 'kasir')->count());
        $this->assertTrue(Hash::check('changed-password', $user->fresh()->password));
    }

    public function test_transaction_layout_is_preserved_on_its_own_cashier_route(): void
    {
        $user = $this->user();
        $this->get('/kasir/penjualan')->assertRedirect('/kasir/login');
        Schema::create('sumber_keuangan', function (Blueprint $t) { $t->string('id')->primary(); $t->string('nama_sumber'); $t->string('jenis'); $t->boolean('is_active'); });
        $this->actingAs($user)->get('/kasir/penjualan')->assertOk()->assertSee('Daftar Belanja')->assertSee('Tambah Barang');
        $this->get('/kasir/dashboard')->assertOk()->assertSee('Grafik Penjualan Harian')->assertDontSee('CONTOH-001');
    }

    public function test_admin_bank_form_uses_complete_local_ojk_directory_instead_of_broken_remote_api(): void
    {
        $response = $this->actingAs($this->user('admin'))->get('/administrator/sumber-keuangan/create');
        $response->assertOk()->assertSee('105 bank umum dan syariah')->assertSee('publikasi OJK Juni 2026')
            ->assertSee('PT BANK SYARIAH INDONESIA Tbk')->assertSee('PT BANK JAGO TBK')
            ->assertSee('PT BANK DIGITAL BCA')->assertSee('Bank lainnya / BPR')
            ->assertDontSee('diambil otomatis dari API Bank Indonesia')->assertDontSee('raw.githubusercontent.com');
        $response->assertViewHas('bankDirectory', function ($directory) {
            $codes = array_column($directory['banks'], 'code');
            return count($codes) === 105 && count(array_unique($codes)) === 105 && in_array('014', $codes, true);
        });
    }
    private function addWarehouseUserFields(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['no_telepon','alamat','jenis_kelamin','profile_picture','nip','nik','divisi_id','jabatan_id'] as $field) $table->string($field)->nullable();
            $table->date('tanggal_bergabung')->nullable();
        });
    }

    public function test_warehouse_user_pages_no_longer_depend_on_division_and_position_tables(): void
    {
        $this->addWarehouseUserFields();
        $user = $this->user('admin');
        $this->actingAs($user);
        foreach (['/administrator/user/create', '/administrator/user/'.$user->id.'/edit', '/administrator/user/'.$user->id, '/administrator/pengguna/profil'] as $url) {
            $this->get($url)->assertOk()->assertDontSee('name="nik"', false)->assertDontSee('name="divisi_id"', false)->assertDontSee('name="jabatan_id"', false)->assertDontSee('>NIK<', false)->assertDontSee('>Divisi<', false)->assertDontSee('>Jabatan<', false);
        }
        $this->get('/administrator/user')->assertOk()->assertSee($user->username, false);
    }

    public function test_admin_can_create_a_warehouse_user_without_hr_fields_and_injected_fields_are_ignored(): void
    {
        $this->addWarehouseUserFields();
        $admin = $this->user('admin');
        $this->actingAs($admin)->post('/administrator/user', [
            'name'=>'Petugas Gudang Baru', 'username'=>'gudang-baru', 'email'=>'gudang@example.com', 'password'=>'password-test',
            'role_id'=>$admin->role_id, 'nik'=>'injected', 'divisi_id'=>'missing', 'jabatan_id'=>'missing', 'nip'=>'injected',
        ])->assertSessionHasNoErrors()->assertRedirect(route('user.index'));
        $created = User::where('username','gudang-baru')->firstOrFail();
        $this->assertTrue(Hash::check('password-test', $created->password));
        foreach (['nik','divisi_id','jabatan_id','nip'] as $field) $this->assertNull($created->$field);
        $this->assertEquals($admin->role_id, $created->role_id);
        $this->assertEquals('aktif', $created->status_kepegawaian);
    }

    public function test_editing_warehouse_user_preserves_legacy_hr_data_and_existing_password(): void
    {
        $this->addWarehouseUserFields();
        $admin = $this->user('admin');
        $admin->update(['nik'=>'legacy-nik','divisi_id'=>'legacy-division','jabatan_id'=>'legacy-position']);
        $password = $admin->password;
        $this->actingAs($admin)->put('/administrator/user/'.$admin->id, [
            'name'=>'Admin Gudang', 'username'=>$admin->username, 'email'=>$admin->email, 'role_id'=>$admin->role_id,
            'nik'=>'changed', 'divisi_id'=>'changed', 'jabatan_id'=>'changed', 'password'=>'',
        ])->assertSessionHasNoErrors()->assertRedirect(route('user.index'));
        $admin->refresh();
        $this->assertEquals('Admin Gudang', $admin->name); $this->assertEquals($password, $admin->password);
        $this->assertEquals('legacy-nik', $admin->nik); $this->assertEquals('legacy-division', $admin->divisi_id); $this->assertEquals('legacy-position', $admin->jabatan_id);
    }

    public function test_division_and_position_management_is_disabled(): void
    {
        $this->actingAs($this->user('admin'));
        foreach (['divisi','jabatan'] as $module) {
            $this->get('/administrator/'.$module)->assertRedirect(route('user.index'));
            $this->get('/administrator/'.$module.'/create')->assertRedirect(route('user.index'));
            $this->post('/administrator/'.$module, ['name'=>'Disabled'])->assertStatus(410);
            $this->put('/administrator/'.$module.'/legacy', ['name'=>'Disabled'])->assertStatus(410);
            $this->delete('/administrator/'.$module.'/legacy')->assertStatus(410);
        }
    }

    public function test_admin_can_filter_users_by_role_and_reset_to_all_roles(): void
    {
        $this->addWarehouseUserFields();
        $admin = $this->user('admin');
        $cashier = $this->user('kasir');
        $this->actingAs($admin)->get('/administrator/user?role_id='.$cashier->role_id)->assertOk()->assertSee('Semua Peran')
            ->assertViewHas('selectedRole', $cashier->role_id)
            ->assertViewHas('users', fn ($users) => $users->pluck('id')->all() === [$cashier->id]);
        $this->get('/administrator/user')->assertOk()->assertViewHas('users', fn ($users) => $users->count() === 2);
        $this->get('/administrator/user?role_id=missing-role')->assertSessionHasErrors('role_id');
    }

    public function test_supplier_filters_support_status_origin_type_and_reset(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->string('id')->primary();
            foreach (['kode_supplier','nama_supplier','penanggung_jawab','no_telepon','logo','kota_id','asal_supplier','tipe_supplier'] as $field) $table->string($field)->nullable();
            $table->boolean('status'); $table->timestamps();
        });
        Schema::create('kota', function (Blueprint $table) { $table->string('id')->primary(); $table->string('name'); });
        foreach ([['a',true,'dalam_negeri','Principal'], ['b',false,'luar_negeri','Distributor'], ['c',false,null,'Principal']] as [$id,$active,$origin,$type]) {
            DB::table('suppliers')->insert(['id'=>$id, 'nama_supplier'=>'Supplier '.$id, 'status'=>$active, 'asal_supplier'=>$origin, 'tipe_supplier'=>$type]);
        }
        $this->actingAs($this->user('admin'));
        $this->get('/administrator/supplier?status=0')->assertOk()->assertViewHas('suppliers', fn ($rows) => $rows->pluck('id')->all() === ['b','c']);
        $this->get('/administrator/supplier?status=0&asal_supplier=dalam_negeri&tipe_supplier=Principal')->assertOk()
            ->assertViewHas('suppliers', fn ($rows) => $rows->pluck('id')->all() === ['c'])
            ->assertSee('supplier-actions')->assertDontSee('class="btn-group"', false);
        $this->get('/administrator/supplier?asal_supplier=luar_negeri')->assertOk()->assertViewHas('suppliers', fn ($rows) => $rows->pluck('id')->all() === ['b']);
        $this->get('/administrator/supplier')->assertOk()->assertViewHas('suppliers', fn ($rows) => $rows->count() === 3)->assertViewHas('supplierTypes', fn ($types) => $types->count() === 2);
        $this->get('/administrator/supplier?status=wrong')->assertSessionHasErrors('status');
    }

    public function test_cashier_root_redirects_to_dashboard(): void
    {
        $this->actingAs($this->user())->get('/kasir')->assertRedirect('/kasir/dashboard');
    }

    public function test_cashier_product_catalog_search_filters_and_access(): void
    {
        $this->get('/kasir/produk')->assertRedirect('/kasir/login');
        $this->actingAs($this->user('admin'))->get('/kasir/produk')->assertForbidden();
        Schema::create('product_category', function (Blueprint $t) { $t->string('id')->primary(); $t->string('category_name'); });
        Schema::create('uoms', function (Blueprint $t) { $t->string('id')->primary(); $t->string('name'); });
        Schema::create('products', function (Blueprint $t) {
            $t->string('id')->primary(); foreach (['sku', 'name', 'merk', 'category_id', 'uom_id', 'image'] as $field) $t->string($field)->nullable();
            $t->decimal('price', 15, 2); $t->decimal('purchase_price', 15, 2)->nullable();
        });
        Schema::create('stok', function (Blueprint $t) { $t->increments('id'); $t->string('produk_id'); $t->integer('quantity'); });
        DB::table('product_category')->insert([['id' => 'fiber', 'category_name' => 'Fiber Optik'], ['id' => 'network', 'category_name' => 'Jaringan']]);
        DB::table('uoms')->insert(['id' => 'unit', 'name' => 'Unit']);
        DB::table('products')->insert([
            ['id' => 'p1', 'sku' => 'FO-001', 'name' => 'Kabel Fiber', 'merk' => 'Garuda', 'category_id' => 'fiber', 'uom_id' => 'unit', 'price' => 150000],
            ['id' => 'p2', 'sku' => 'NET-002', 'name' => 'Router', 'merk' => 'Brand', 'category_id' => 'network', 'uom_id' => 'unit', 'price' => 200000],
        ]);
        DB::table('stok')->insert([['produk_id' => 'p1', 'quantity' => 3], ['produk_id' => 'p1', 'quantity' => 4]]);
        $this->actingAs($this->user())->get('/kasir/produk')->assertOk()->assertSee('Kabel Fiber')->assertSee('Router')->assertSee('FO-001');
        $this->get('/kasir/produk?search=FO-001&stock=available')->assertOk()->assertSee('Kabel Fiber')->assertDontSee('NET-002');
        $this->get('/kasir/produk?category=network&stock=empty')->assertOk()->assertSee('NET-002')->assertDontSee('FO-001');
        $this->get('/kasir/produk?search=tidak-ada')->assertOk()->assertSee('Tidak ada produk yang sesuai.');
        $this->get('/kasir/produk?stock=invalid')->assertSessionHasErrors('stock');
    }

    public function test_cashier_customer_directory_filters_and_deduplicates_completed_consumers(): void
    {
        $this->get('/kasir/pelanggan')->assertRedirect('/kasir/login');
        $this->actingAs($this->user('admin'))->get('/kasir/pelanggan')->assertForbidden();
        Schema::create('distributor', function (Blueprint $t) {
            $t->string('id')->primary(); foreach (['kode_distributor', 'nama_distributor', 'telepon', 'email', 'alamat', 'status'] as $field) $t->string($field)->nullable();
        });
        DB::table('distributor')->insert(['id' => 'd1', 'kode_distributor' => 'DST-001', 'nama_distributor' => 'Mitra Test', 'telepon' => '081100', 'email' => 'mitra@example.com', 'alamat' => 'Alamat Mitra', 'status' => 'active']);
        $consumer = ['tipe_penerima' => 'konsumen', 'nama_konsumen' => 'Budi', 'telepon_konsumen' => '082200', 'alamat_konsumen' => 'Alamat Budi', 'status' => 'completed'];
        DB::table('pengeluaran_barang')->insert([$consumer, $consumer, array_replace($consumer, ['nama_konsumen' => 'DraftOnly', 'status' => 'pending']), array_replace($consumer, ['nama_konsumen' => 'CanceledOnly', 'status' => 'canceled'])]);
        $this->actingAs($this->user())->get('/kasir/pelanggan')->assertOk()->assertSee('Mitra Test')->assertSee('Budi')->assertDontSee('DraftOnly')->assertDontSee('CanceledOnly')->assertViewHas('customers', fn ($customers) => $customers->total() === 2);
        $this->get('/kasir/pelanggan?type=konsumen')->assertOk()->assertSee('Budi')->assertDontSee('DST-001')->assertViewHas('customers', fn ($customers) => $customers->total() === 1);
        $this->get('/kasir/pelanggan?search=mitra%40example.com')->assertOk()->assertSee('DST-001')->assertDontSee('Alamat Budi');
        $this->get('/kasir/pelanggan?search=082200&type=distributor')->assertOk()->assertSee('Tidak ada pelanggan yang sesuai.');
        $this->get('/kasir/pelanggan?type=invalid')->assertSessionHasErrors('type');
    }

    public function test_cashier_report_and_password_pages_are_protected(): void
    {
        $this->get('/kasir/laporan')->assertRedirect('/kasir/login');
        $this->get('/kasir/password')->assertRedirect('/kasir/login');
        $this->actingAs($this->user('admin'))->get('/kasir/laporan')->assertForbidden();
        $this->get('/kasir/password')->assertForbidden();
        $this->actingAs($this->user())->get('/kasir/password')->assertOk()->assertSee('Password Saat Ini');
        $this->get('/kasir/laporan?tanggal_awal=2026-10-01&tanggal_akhir=2026-10-31')->assertOk()->assertSee('Belum ada transaksi kasir pada periode ini.')->assertViewHas('tanggalAwal', '2026-10-01')->assertViewHas('transactions', fn ($rows) => $rows->total() === 0);
        $this->get('/kasir/laporan?tanggal_awal=2026-10-31&tanggal_akhir=2026-10-01')->assertSessionHasErrors('tanggal_akhir');
    }

    public function test_password_change_rejects_wrong_current_password_and_mismatched_confirmation(): void
    {
        $user = $this->user();
        $this->actingAs($user)->put('/kasir/password', ['current_password' => 'wrong', 'password' => 'NewKasir123!', 'password_confirmation' => 'NewKasir123!'])->assertSessionHasErrors('current_password');
        $this->put('/kasir/password', ['current_password' => 'password-kasir', 'password' => 'NewKasir123!', 'password_confirmation' => 'different'])->assertSessionHasErrors('password');
        $this->put('/kasir/password', ['current_password' => 'password-kasir', 'password' => 'short', 'password_confirmation' => 'short'])->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('password-kasir', $user->fresh()->password));
    }

    public function test_cashier_can_change_only_own_password_and_login_with_new_password(): void
    {
        $user = $this->user(); $other = $this->user('admin'); $otherHash = $other->password;
        $this->actingAs($user)->put('/kasir/password', ['user_id' => $other->id, 'current_password' => 'password-kasir', 'password' => 'NewKasir123!', 'password_confirmation' => 'NewKasir123!'])->assertRedirect('/kasir/password')->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewKasir123!', $user->fresh()->password));
        $this->assertSame($otherHash, $other->fresh()->password);
        $this->assertNotEmpty($user->fresh()->remember_token);
        $this->post('/kasir/logout');
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'password-kasir'])->assertSessionHasErrors('login');
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'NewKasir123!'])->assertRedirect('/kasir/dashboard');
    }
}
