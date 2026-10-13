<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        Role::ensureSchemaAndDefaults();

        $roles = Role::orderBy('created_at', 'asc')->get();
        $availablePermissions = Role::AVAILABLE_PERMISSIONS;
        $adminPermissionKeys = Role::getAdminPermissionKeys();

        return view('role.index', compact('roles', 'availablePermissions', 'adminPermissionKeys'));
    }

    public function store(Request $request)
    {
        Role::ensureSchemaAndDefaults();

        $request->validate([
            'role_name'        => 'required|string|max:255',
            'role_description' => 'nullable|string',
            'role_status'      => 'required|boolean',
            'permissions'      => 'nullable|array',
            'permissions.*'    => 'string',
        ]);

        $data = $request->all();
        if (!$request->has('permissions')) {
            $data['permissions'] = strtolower(trim($request->role_name)) === 'distributor'
                ? ['portal_distributor']
                : Role::getAdminPermissionKeys();
        }

        Role::createRole($data);

        return redirect()->route('role.index')->with('success', 'Peran dan hak akses berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        Role::ensureSchemaAndDefaults();

        $request->validate([
            'role_name'        => 'required|string|max:255',
            'role_description' => 'nullable|string',
            'role_status'      => 'required|boolean',
            'permissions'      => 'nullable|array',
            'permissions.*'    => 'string',
        ]);

        $role = Role::findOrFail($id);
        $data = $request->all();
        if ($request->has('permissions_submitted') && !$request->has('permissions')) {
            $data['permissions'] = [];
        }

        $role->updateRole($data);

        return redirect()->route('role.index')->with('success', 'Peran dan hak akses berhasil diperbarui.');
    }

    public function updatePermissions(Request $request, $id)
    {
        Role::ensureSchemaAndDefaults();

        $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $role = Role::findOrFail($id);
        $perms = $request->input('permissions', []);

        if (strtolower(trim($role->role_name)) === 'admin' && !in_array('master_role', $perms, true)) {
            $perms[] = 'master_role';
        }

        $role->update([
            'permissions' => array_values(array_unique($perms)),
        ]);

        return redirect()->route('role.index')->with('success', "Hak akses untuk peran '{$role->role_name}' berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if (strtolower(trim($role->role_name)) === 'admin') {
            return redirect()->route('role.index')->with('error', 'Peran utama Admin tidak dapat dihapus.');
        }

        $role->deleteRole();

        return redirect()->route('role.index')->with('success', 'Peran berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->toggleStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status peran berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status.'
            ], 500);
        }
    }
}

