<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->insert([
            [
                'id' => (string) Str::uuid(),
                'role_name' => 'admin',
                'role_description' => 'pengelola',
                'role_status' => 1,
            ],
            [
                'id' => (string) Str::uuid(),
                'role_name' => 'distributor',
                'role_description' => 'distributor',
                'role_status' => 1,
            ],
        ]);
    }
}
