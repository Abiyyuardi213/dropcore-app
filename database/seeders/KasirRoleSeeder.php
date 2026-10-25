<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KasirRoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = DB::table('role')->whereRaw('LOWER(TRIM(role_name)) = ?', ['kasir'])->first();
        if ($role) {
            DB::table('role')->where('id', $role->id)->update(['permissions' => json_encode(['kasir']), 'updated_at' => now()]);
            return;
        }
        DB::table('role')->insert([
            'id' => (string) Str::uuid(), 'role_name' => 'kasir',
            'role_description' => 'Kasir - hanya memiliki akses ke area kasir',
            'role_status' => 1, 'permissions' => json_encode(['kasir']),
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }
}
