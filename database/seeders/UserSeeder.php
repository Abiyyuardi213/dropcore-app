<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'id'              => (string) Str::uuid(),
                'name'            => 'Admin Satu',
                'username'        => 'adminsatu',
                'email'           => 'adminsatu@gmail.com',
                'no_telepon'      => '081234567890',
                'password'        => Hash::make('password'),
                'profile_picture' => null,
                'role_id'         => DB::table('role')->where('role_name', 'admin')->value('id'),
            ],
        ]);
    }
}
