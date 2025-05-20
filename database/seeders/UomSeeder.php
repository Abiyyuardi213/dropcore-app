<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('uoms')->insert([
            [
                'id' => (string) Str::uuid(),
                'name' => 'Meter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Roll',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Box',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
