<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $table = 'role';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static bool $schemaChecked = false;

    protected $fillable = [
        'role_name',
        'role_description',
        'role_status',
        'permissions',
    ];

    protected $casts = [
        'role_status' => 'boolean',
        'permissions' => 'array',
    ];

    public const AVAILABLE_PERMISSIONS = [
        'dashboard' => [
            'label' => 'Dashboard Utama',
            'icon'  => 'fas fa-tachometer-alt',
            'group' => 'Utama',
            'desc'  => 'Akses halaman ringkasan utama dan grafik perkembangan penjualan',
            'url'   => '/admin/dashboard',
        ],
        'master_role' => [
            'label' => 'Master Peran & Hak Akses',
            'icon'  => 'fas fa-user-shield',
            'group' => 'Master Data',
            'desc'  => 'Mengelola daftar role/peran pengguna beserta pengaturan hak aksesnya',
            'url'   => '/admin/role',
        ],
        'master_hr' => [
            'label' => 'Master SDM (Pengguna, Divisi, Jabatan)',
            'icon'  => 'fas fa-users-cog',
            'group' => 'Master Data',
            'desc'  => 'Mengelola data pengguna/karyawan, struktur divisi, dan jabatan',
            'url'   => '/admin/user',
        ],
        'master_wilayah' => [
            'label' => 'Master Wilayah & Regional',
            'icon'  => 'fas fa-globe',
            'group' => 'Master Data',
            'desc'  => 'Mengelola data Wilayah, Provinsi, Kota, Kecamatan, dan Kelurahan',
            'url'   => '/admin/wilayah',
        ],
        'master_mitra' => [
            'label' => 'Master Mitra & Operasional',
            'icon'  => 'fas fa-folder-open',
            'group' => 'Master Data',
            'desc'  => 'Dashboard Master, Supplier, Kondisi Barang, Jasa Pengiriman, & Metode Pembayaran',
            'url'   => '/admin/dashboard-master',
        ],
        'gudang' => [
            'label' => 'Manajemen Gudang',
            'icon'  => 'fas fa-warehouse',
            'group' => 'Pergudangan & Produk',
            'desc'  => 'Dashboard Gudang, Data Gudang, Area Gudang, dan Rak Gudang',
            'url'   => '/admin/dashboardGudang',
        ],
        'produk' => [
            'label' => 'Manajemen Produk & Stok',
            'icon'  => 'fas fa-boxes',
            'group' => 'Pergudangan & Produk',
            'desc'  => 'Dashboard Produk, Katalog Produk, Kategori Produk, dan Stok Produk',
            'url'   => '/admin/dashboardProduk',
        ],
        'mutasi_stok' => [
            'label' => 'Mutasi Stok',
            'icon'  => 'fas fa-exchange-alt',
            'group' => 'Pergudangan & Produk',
            'desc'  => 'Pencatatan dan pengelolaan perpindahan stok antar gudang/area/rak',
            'url'   => '/admin/mutasi-stok',
        ],
        'stock_opname' => [
            'label' => 'Stock Opname',
            'icon'  => 'fas fa-clipboard-check',
            'group' => 'Pergudangan & Produk',
            'desc'  => 'Pemeriksaan dan penyesuaian stok fisik gudang',
            'url'   => '/admin/stock-opname',
        ],
        'transaksi' => [
            'label' => 'Transaksi Barang (In / Out)',
            'icon'  => 'fas fa-dolly',
            'group' => 'Transaksi & Distribusi',
            'desc'  => 'Penerimaan Barang dari Supplier dan Pengeluaran Barang',
            'url'   => '/admin/penerimaan-barang',
        ],
        'orders' => [
            'label' => 'Pesanan Distributor',
            'icon'  => 'fas fa-shopping-cart',
            'group' => 'Transaksi & Distribusi',
            'desc'  => 'Verifikasi pembayaran, pengiriman, dan faktur pesanan distributor',
            'url'   => '/admin/orders',
        ],
        'distributor' => [
            'label' => 'Data Distributor',
            'icon'  => 'fas fa-truck-moving',
            'group' => 'Transaksi & Distribusi',
            'desc'  => 'Mengelola daftar mitra distributor',
            'url'   => '/admin/distributor',
        ],
        'keuangan' => [
            'label' => 'Manajemen Keuangan',
            'icon'  => 'fas fa-wallet',
            'group' => 'Keuangan & Laporan',
            'desc'  => 'Dashboard Keuangan, Kas Pusat, Sumber Keuangan, dan Arus Kas Keuangan',
            'url'   => '/admin/dashboard-keuangan',
        ],
        'laporan' => [
            'label' => 'Laporan Sistem',
            'icon'  => 'fas fa-file-alt',
            'group' => 'Keuangan & Laporan',
            'desc'  => 'Melihat dan mencetak laporan operasional',
            'url'   => '/admin/laporan',
        ],
        'riwayat' => [
            'label' => 'Riwayat & Log Aktivitas',
            'icon'  => 'fas fa-history',
            'group' => 'Keuangan & Laporan',
            'desc'  => 'Audit log aktivitas sistem dan riwayat aktivitas produk',
            'url'   => '/admin/riwayat-logs',
        ],
        'portal_distributor' => [
            'label' => 'Portal Belanja Distributor (Client)',
            'icon'  => 'fas fa-store',
            'group' => 'Portal Eksternal',
            'desc'  => 'Akses halaman belanja distributor, keranjang, dan pemesanan online',
            'url'   => '/homepage',
        ],
    ];

    public static function ensureSchemaAndDefaults(): void
    {
        if (self::$schemaChecked) {
            return;
        }
        self::$schemaChecked = true;

        try {
            if (!Schema::hasColumn('role', 'permissions')) {
                Schema::table('role', function (Blueprint $table) {
                    $table->json('permissions')->nullable()->after('role_status');
                });

                $allAdminPermissions = self::getAdminPermissionKeys();
                $roles = DB::table('role')->get();
                $hasItRole = false;

                foreach ($roles as $r) {
                    $nameLower = strtolower(trim($r->role_name));
                    if (in_array($nameLower, ['it', 'tim it', 'team it', 'it support'])) {
                        $hasItRole = true;
                    }
                    if ($nameLower === 'distributor') {
                        DB::table('role')->where('id', $r->id)->update([
                            'permissions' => json_encode(['portal_distributor']),
                        ]);
                    } else {
                        DB::table('role')->where('id', $r->id)->update([
                            'permissions' => json_encode($allAdminPermissions),
                        ]);
                    }
                }

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

            // Auto-clean any numeric/corrupted keys from previous collection groupBy bug
            if (Schema::hasColumn('role', 'permissions')) {
                $allRoles = DB::table('role')->whereNotNull('permissions')->get();
                $availableKeys = array_keys(self::AVAILABLE_PERMISSIONS);
                $operationalKeys = ['dashboard', 'gudang', 'produk', 'mutasi_stok', 'stock_opname', 'transaksi', 'orders', 'distributor', 'laporan'];

                foreach ($allRoles as $er) {
                    $raw = $er->permissions;
                    $decoded = is_string($raw) ? json_decode($raw, true) : (array) $raw;
                    if (!is_array($decoded)) {
                        continue;
                    }

                    $hasCorruptedKey = false;
                    foreach ($decoded as $item) {
                        if (is_numeric($item) || !in_array($item, $availableKeys, true)) {
                            $hasCorruptedKey = true;
                            break;
                        }
                    }

                    if ($hasCorruptedKey) {
                        $cleaned = array_values(array_filter($decoded, function ($item) use ($availableKeys) {
                            return is_string($item) && in_array($item, $availableKeys, true);
                        }));

                        $nameLower = strtolower(trim($er->role_name));
                        if (empty($cleaned)) {
                            if ($nameLower === 'staff' || $nameLower === 'staf') {
                                $cleaned = $operationalKeys;
                            } elseif ($nameLower === 'distributor') {
                                $cleaned = ['portal_distributor'];
                            } else {
                                $cleaned = self::getAdminPermissionKeys();
                            }
                        }

                        DB::table('role')->where('id', $er->id)->update([
                            'permissions' => json_encode($cleaned),
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore schema check errors if DB connection is not ready
        }
    }

    protected static function booted()
    {
        static::creating(function ($role) {
            if (!$role->id) {
                $role->id = (string) Str::uuid();
            }
        });

        static::created(function ($role) {
            RiwayatAktivitasLog::add(
                'role', 'create', "Membuat role {$role->role_name}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($role) {
            RiwayatAktivitasLog::add(
                'role', 'update', "Memperbarui role {$role->role_name}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($role) {
            RiwayatAktivitasLog::add(
                'role', 'delete', "Menghapus role {$role->role_name}",
                optional(Auth::user())->id
            );
        });
    }

    public static function getAdminPermissionKeys(): array
    {
        return array_values(array_diff(array_keys(self::AVAILABLE_PERMISSIONS), ['portal_distributor']));
    }

    public function getEffectivePermissions(): array
    {
        $roleNameLower = strtolower(trim($this->role_name ?? ''));

        if (is_array($this->permissions)) {
            $validKeys = array_keys(self::AVAILABLE_PERMISSIONS);
            $perms = array_values(array_filter($this->permissions, function ($val) use ($validKeys) {
                return is_string($val) && in_array($val, $validKeys, true);
            }));

            if (in_array('*', $this->permissions, true) || in_array('all', $this->permissions, true)) {
                return self::getAdminPermissionKeys();
            }
            if ($roleNameLower === 'admin' && !in_array('master_role', $perms, true)) {
                $perms[] = 'master_role';
            }
            return array_values(array_unique($perms));
        }

        if ($roleNameLower === 'distributor') {
            return ['portal_distributor'];
        }

        return self::getAdminPermissionKeys();
    }

    public function hasPermission(string $permissionKey): bool
    {
        return in_array($permissionKey, $this->getEffectivePermissions(), true);
    }

    public function hasFullAdminAccess(): bool
    {
        $adminKeys = self::getAdminPermissionKeys();
        $effective = $this->getEffectivePermissions();
        return count(array_intersect($adminKeys, $effective)) === count($adminKeys);
    }

    public function isAdminPanelRole(): bool
    {
        $adminKeys = self::getAdminPermissionKeys();
        $effective = $this->getEffectivePermissions();
        return count(array_intersect($adminKeys, $effective)) > 0;
    }

    public function getFirstAllowedAdminUrl(): string
    {
        $effective = $this->getEffectivePermissions();
        if (in_array('dashboard', $effective, true)) {
            return '/admin/dashboard';
        }
        foreach (self::AVAILABLE_PERMISSIONS as $key => $info) {
            if ($key !== 'portal_distributor' && in_array($key, $effective, true) && !empty($info['url'])) {
                return $info['url'];
            }
        }
        return '/admin/pengguna/profil';
    }

    public function canAccessRequest(Request $request): bool
    {
        // Selalu izinkan halaman profil pengguna sendiri dan endpoint AJAX stok internal
        if (
            $request->is('admin/pengguna/profil*') ||
            $request->is('admin/stok/by-produk*') ||
            $request->is('admin/stok/produk*') ||
            $request->is('admin/lokasi-asal-produk*')
        ) {
            return $this->isAdminPanelRole();
        }

        if ($request->is('admin/dashboard')) {
            return $this->hasPermission('dashboard');
        }

        if ($request->is('admin/role*')) {
            return $this->hasPermission('master_role');
        }

        if ($request->is('admin/user*') || $request->is('admin/divisi*') || $request->is('admin/jabatan*')) {
            return $this->hasPermission('master_hr');
        }

        if (
            $request->is('admin/wilayah*') ||
            $request->is('admin/provinsi*') ||
            $request->is('admin/kota*') ||
            $request->is('admin/kecamatan*') ||
            $request->is('admin/kelurahan*')
        ) {
            return $this->hasPermission('master_wilayah');
        }

        if ($request->is('admin/dashboard-master*')) {
            return $this->hasPermission('master_mitra') ||
                   $this->hasPermission('master_role') ||
                   $this->hasPermission('master_hr') ||
                   $this->hasPermission('master_wilayah');
        }

        if (
            $request->is('admin/supplier*') ||
            $request->is('admin/kondisi-barang*') ||
            $request->is('admin/jasa-pengiriman*') ||
            $request->is('admin/metode-pembayaran*')
        ) {
            return $this->hasPermission('master_mitra');
        }

        if (
            $request->is('admin/dashboardGudang*') ||
            $request->is('admin/gudang*') ||
            $request->is('admin/areaGudang*') ||
            $request->is('admin/rak-gudang*')
        ) {
            return $this->hasPermission('gudang');
        }

        if (
            $request->is('admin/dashboardProduk*') ||
            $request->is('admin/product*') ||
            $request->is('admin/category*') ||
            $request->is('admin/stok*')
        ) {
            return $this->hasPermission('produk');
        }

        if (
            $request->is('admin/dashboard-keuangan*') ||
            $request->is('admin/kas-pusat*') ||
            $request->is('admin/sumber-keuangan*') ||
            $request->is('admin/keuangan*')
        ) {
            return $this->hasPermission('keuangan');
        }

        if ($request->is('admin/orders*')) {
            return $this->hasPermission('orders');
        }

        if ($request->is('admin/distributor*')) {
            return $this->hasPermission('distributor');
        }

        if ($request->is('admin/mutasi-stok*')) {
            return $this->hasPermission('mutasi_stok');
        }

        if ($request->is('admin/stock-opname*')) {
            return $this->hasPermission('stock_opname');
        }

        if (
            $request->is('admin/penerimaan-barang*') ||
            $request->is('admin/detail-penerimaan*') ||
            $request->is('admin/pengeluaran-barang*') ||
            $request->is('admin/detail-pengeluaran*')
        ) {
            return $this->hasPermission('transaksi');
        }

        if ($request->is('admin/riwayat-aktivitas-produk*') || $request->is('admin/riwayat-logs*')) {
            return $this->hasPermission('riwayat');
        }

        if ($request->is('admin/laporan*')) {
            return $this->hasPermission('laporan');
        }

        return $this->isAdminPanelRole();
    }

    public static function createRole($data)
    {
        self::ensureSchemaAndDefaults();

        $permissions = $data['permissions'] ?? self::getAdminPermissionKeys();
        if (!is_array($permissions)) {
            $permissions = self::getAdminPermissionKeys();
        }

        return self::create([
            'role_name'        => $data['role_name'],
            'role_description' => $data['role_description'] ?? null,
            'role_status'      => $data['role_status'] ?? true,
            'permissions'      => array_values($permissions),
        ]);
    }

    public function updateRole($data)
    {
        self::ensureSchemaAndDefaults();

        $updatePayload = [
            'role_name'        => $data['role_name'],
            'role_description' => $data['role_description'] ?? $this->role_description,
            'role_status'      => $data['role_status'] ?? $this->role_status,
        ];

        if (array_key_exists('permissions', $data)) {
            $perms = is_array($data['permissions']) ? array_values($data['permissions']) : [];
            if (strtolower(trim($data['role_name'])) === 'admin' && !in_array('master_role', $perms, true)) {
                $perms[] = 'master_role';
            }
            $updatePayload['permissions'] = $perms;
        }

        $this->update($updatePayload);
    }

    public function deleteRole()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->role_status = !$this->role_status;
        $this->save();

        RiwayatAktivitasLog::add(
            'role', 'toggle_status', "Mengubah status role {$this->role_name}",
            optional(Auth::user())->id
        );
    }
}

