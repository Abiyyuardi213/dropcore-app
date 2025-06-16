<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
                'role_id'         => '4880aba1-4450-465e-b938-5694588ab2e5',
            ],
        ]);
    }
}
