<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('role', 'permissions')) {
            Schema::table('role', function (Blueprint $table) {
                $table->json('permissions')->nullable()->after('role_status');
            });
        }

        $allAdminPermissions = Role::getAdminPermissionKeys();

        // Set default permissions for existing roles
        $roles = DB::table('role')->get();
        $hasItRole = false;

        foreach ($roles as $role) {
            $nameLower = strtolower(trim($role->role_name));
            if (in_array($nameLower, ['it', 'tim it', 'team it', 'it support'])) {
                $hasItRole = true;
            }

            if ($role->permissions === null) {
                if ($nameLower === 'distributor') {
                    DB::table('role')->where('id', $role->id)->update([
                        'permissions' => json_encode(['portal_distributor']),
                    ]);
                } else {
                    DB::table('role')->where('id', $role->id)->update([
                        'permissions' => json_encode($allAdminPermissions),
                    ]);
                }
            }
        }

        // Tambahkan role Tim IT jika belum ada
        if (!$hasItRole) {
            DB::table('role')->insert([
                'id'               => (string) Str::uuid(),
                'role_name'        => 'Tim IT',
                'role_description' => 'Tim IT - Mengelola seluruh akses dan operasional sistem',
                'role_status'      => 1,
                'permissions'      => json_encode($allAdminPermissions),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('role', 'permissions')) {
            Schema::table('role', function (Blueprint $table) {
                $table->dropColumn('permissions');
            });
        }
    }
};
