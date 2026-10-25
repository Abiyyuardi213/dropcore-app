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
    }

    private function user(string $role = 'kasir'): User
    {
        return User::create(['name' => 'Petugas '.ucfirst($role), 'username' => $role, 'email' => $role.'@example.com', 'role_id' => DB::table('role')->where('role_name', $role)->value('id'), 'status_kepegawaian' => 'aktif', 'password' => Hash::make('password-kasir')]);
    }

    public function test_login_form_and_guest_redirect_use_cashier_portal(): void
    {
        $this->get('/kasir/login')->assertOk()->assertSee('Login Kasir')->assertSee(route('kasir.login.attempt'), false);
        $this->get('/kasir')->assertRedirect('/kasir/login');
    }

    public function test_cashier_can_login_by_username_and_cannot_follow_admin_intended_url(): void
    {
        $user = $this->user();
        $this->withSession(['url.intended' => '/administrator/dashboard'])->post('/kasir/login', ['login' => 'kasir', 'password' => 'password-kasir'])->assertRedirect('/kasir');
        $this->assertAuthenticatedAs($user);
        $this->get('/kasir')->assertOk()->assertSee('Dashboard Kasir')->assertSee('Daftar Belanja');
        $this->get('/kasir/login')->assertRedirect('/kasir');
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
        $this->post('/kasir/login', ['login' => $user->email, 'password' => 'password-kasir'])->assertRedirect('/kasir');
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
        $this->actingAs($admin)->get('/kasir')->assertForbidden();
    }

    public function test_inactive_account_or_role_cannot_login_or_use_cashier_area(): void
    {
        $user = $this->user(); $user->update(['status_kepegawaian' => 'nonaktif']);
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'password-kasir'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->actingAs($user->fresh())->get('/kasir')->assertForbidden();
        $this->post('/kasir/logout')->assertRedirect('/kasir/login');
        $user->update(['status_kepegawaian' => 'aktif']);
        DB::table('role')->where('id', $user->role_id)->update(['role_status' => false]);
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'password-kasir'])->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->actingAs($user->fresh())->get('/kasir')->assertForbidden();
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
        $this->post('/kasir/login', ['login' => 'kasir', 'password' => 'Kasir123!'])->assertRedirect('/kasir');
        $this->get('/kasir')->assertOk()->assertSee('Dashboard Kasir')->assertSee('Daftar Belanja');
        $user->update(['password' => Hash::make('changed-password')]);
        $this->seed(\Database\Seeders\KasirUserSeeder::class);
        $this->assertEquals(1, User::where('username', 'kasir')->count());
        $this->assertTrue(Hash::check('changed-password', $user->fresh()->password));
    }
}
