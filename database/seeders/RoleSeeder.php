<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin' => Role::getAdminPermissionKeys(), 'distributor' => ['portal_distributor']] as $name => $permissions) {
            if (DB::table('role')->whereRaw('LOWER(TRIM(role_name)) = ?', [$name])->exists()) continue;
            DB::table('role')->insert([
                'id' => (string) Str::uuid(), 'role_name' => $name,
                'role_description' => $name === 'admin' ? 'Pengelola' : 'Distributor',
                'role_status' => 1, 'permissions' => json_encode($permissions),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        $this->call(KasirRoleSeeder::class);
    }
}
