<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KasirUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            $this->command?->warn('Akun contoh kasir hanya dibuat di lingkungan local/testing.');
            return;
        }
        $this->call(KasirRoleSeeder::class);
        User::firstOrCreate(['username' => 'kasir'], [
            'name' => 'Kasir', 'email' => 'kasir@example.com',
            'password' => Hash::make('Kasir123!'),
            'role_id' => DB::table('role')->whereRaw('LOWER(TRIM(role_name)) = ?', ['kasir'])->value('id'),
            'status_kepegawaian' => 'aktif',
        ]);
    }
}
