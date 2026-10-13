<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Manajemen Peran & Hak Akses</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
        }

        .toggle-status {
            width: 50px;
            height: 24px;
            appearance: none;
            background: #ddd;
            border-radius: 12px;
            position: relative;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .toggle-status:checked {
            background: linear-gradient(90deg, #28a745, #2ecc71);
        }

        .toggle-status::before {
            content: "❌";
            position: absolute;
            top: 3px;
            left: 4px;
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s ease;
            text-align: center;
            font-size: 12px;
            line-height: 18px;
        }

        .toggle-status:checked::before {
            content: "✔️";
            transform: translateX(26px);
            color: #28a745;
        }

        .perm-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            transition: all 0.2s ease;
            cursor: pointer;
            background: #fff;
            height: 100%;
        }

        .perm-card:hover {
            border-color: #007bff;
            background: #f8fbff;
        }

        .perm-card.active-perm {
            border-color: #007bff;
            background: #eef6ff;
        }

        .perm-group-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4a5568;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 4px;
            margin-bottom: 10px;
            margin-top: 12px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('include.navbarSistem')
        @include('include.sidebar')

        @php
            $groupedPermissions = collect($availablePermissions)->groupBy('group');
        @endphp

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0 font-weight-bold"><i class="fas fa-user-shield text-primary mr-2"></i>Manajemen Peran & Hak Akses</h1>
                            <p class="text-muted text-sm mb-0 mt-1">Kelola daftar role (Admin, Tim IT, Staff, Distributor) serta konfigurasi hak akses modul sistem.</p>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Manajemen Peran</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold">Daftar Peran (Role) & Hak Akses</h3>
                            <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal"
                                data-target="#createRoleModal">
                                <i class="fas fa-plus mr-1"></i> Tambah Peran Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="roleTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 16%">Nama Peran</th>
                                            <th style="width: 22%">Deskripsi</th>
                                            <th style="width: 32%">Hak Akses Modul</th>
                                            <th style="width: 10%" class="text-center">Status</th>
                                            <th style="width: 15%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $index => $role)
                                            @php
                                                $effectivePerms = $role->getEffectivePermissions();
                                                $isFullAdmin = $role->hasFullAdminAccess();
                                                $isCoreAdmin = strtolower(trim($role->role_name)) === 'admin';
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="font-weight-bold text-dark">{{ $role->role_name }}</span>
                                                    @if ($isCoreAdmin)
                                                        <span class="badge badge-danger ml-1">Utama</span>
                                                    @elseif ($isFullAdmin)
                                                        <span class="badge badge-primary ml-1">Setara Admin</span>
                                                    @endif
                                                </td>
                                                <td>{{ $role->role_description ?? '-' }}</td>
                                                <td>
                                                    @if ($isFullAdmin)
                                                        <span class="badge badge-success px-2 py-1">
                                                            <i class="fas fa-check-double mr-1"></i> Full Akses Semua Modul ({{ count($adminPermissionKeys) }} Modul)
                                                        </span>
                                                        <small class="text-muted d-block mt-1">
                                                            Memiliki akses penuh ke seluruh fitur sistem seperti Admin.
                                                        </small>
                                                    @elseif ($effectivePerms === ['portal_distributor'])
                                                        <span class="badge badge-info px-2 py-1">
                                                            <i class="fas fa-store mr-1"></i> Portal Belanja Distributor (Client)
                                                        </span>
                                                    @elseif (count($effectivePerms) > 0)
                                                        <div class="d-flex flex-wrap" style="gap: 4px;">
                                                            @foreach ($effectivePerms as $permKey)
                                                                @if (isset($availablePermissions[$permKey]))
                                                                    <span class="badge badge-secondary font-weight-normal px-2 py-1">
                                                                        <i class="{{ $availablePermissions[$permKey]['icon'] }} mr-1"></i>
                                                                        {{ $availablePermissions[$permKey]['label'] }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="badge badge-warning">Belum ada hak akses dipilih</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-role-id="{{ $role->id }}"
                                                        {{ $role->role_status ? 'checked' : '' }}
                                                        {{ $isCoreAdmin ? 'disabled title=Peran_Admin_Utama_Selalu_Aktif' : '' }}>
                                                </td>
                                                <td class="text-center align-middle" style="white-space: nowrap;">
                                                    <button class="btn btn-warning btn-sm manage-permissions-btn mr-1"
                                                        data-id="{{ $role->id }}"
                                                        data-name="{{ $role->role_name }}"
                                                        data-permissions='@json($effectivePerms)'
                                                        data-toggle="modal"
                                                        data-target="#permissionsModal"
                                                        title="Kelola Hak Akses">
                                                        <i class="fas fa-key"></i> Hak Akses
                                                    </button>
                                                    <button class="btn btn-info btn-sm edit-role-btn mr-1"
                                                        data-id="{{ $role->id }}"
                                                        data-name="{{ $role->role_name }}"
                                                        data-description="{{ $role->role_description }}"
                                                        data-status="{{ $role->role_status ? 1 : 0 }}"
                                                        data-permissions='@json($effectivePerms)'
                                                        data-toggle="modal"
                                                        data-target="#editRoleModal"
                                                        title="Edit Peran">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if (!$isCoreAdmin)
                                                        <button class="btn btn-danger btn-sm delete-role-btn"
                                                            data-toggle="modal" data-target="#deleteRoleModal"
                                                            data-role-id="{{ $role->id }}"
                                                            title="Hapus Peran">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    <!-- Modal Kelola Hak Akses (Dedicated) -->
    <div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog" aria-labelledby="permissionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold" id="permissionsModalLabel">
                        <i class="fas fa-key mr-2"></i>Kelola Hak Akses Peran: <span id="perm_role_title"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="permissionsForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center bg-light p-2 rounded mb-3 border">
                            <span class="text-sm font-weight-bold text-secondary">
                                <i class="fas fa-magic mr-1"></i> Template Cepat:
                            </span>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary preset-btn" data-target-form="#permissionsForm" data-preset="full_admin">
                                    <i class="fas fa-check-double mr-1"></i> Full Akses (Setara Admin / IT)
                                </button>
                                <button type="button" class="btn btn-outline-success preset-btn" data-target-form="#permissionsForm" data-preset="operational">
                                    <i class="fas fa-boxes mr-1"></i> Operasional Gudang & Staf
                                </button>
                                <button type="button" class="btn btn-outline-info preset-btn" data-target-form="#permissionsForm" data-preset="distributor">
                                    <i class="fas fa-store mr-1"></i> Portal Distributor
                                </button>
                                <button type="button" class="btn btn-outline-secondary preset-btn" data-target-form="#permissionsForm" data-preset="none">
                                    <i class="fas fa-eraser mr-1"></i> Reset
                                </button>
                            </div>
                        </div>

                        @foreach ($groupedPermissions as $groupName => $perms)
                            <div class="perm-group-title">{{ $groupName }}</div>
                            <div class="row">
                                @foreach ($perms as $key => $info)
                                    <div class="col-md-6 mb-2">
                                        <label class="perm-card d-flex align-items-start mb-0">
                                            <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                                class="mt-1 mr-2 perm-checkbox">
                                            <div>
                                                <div class="font-weight-bold text-sm text-dark">
                                                    <i class="{{ $info['icon'] }} text-primary mr-1"></i> {{ $info['label'] }}
                                                </div>
                                                <small class="text-muted d-block" style="line-height: 1.2;">{{ $info['desc'] }}</small>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Hak Akses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Role -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="createRoleModalLabel">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Peran & Hak Akses Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createRoleForm" action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="role_name">Nama Peran <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="role_name" required
                                        placeholder="Contoh: Tim IT, Supervisor Gudang, Admin Keuangan" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role_status">Status Peran <span class="text-danger">*</span></label>
                                    <select class="form-control" name="role_status" required>
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role_description">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="role_description" rows="2" required
                                placeholder="Masukkan deskripsi tugas dan tanggung jawab peran"></textarea>
                        </div>

                        <hr>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                            <label class="mb-0 font-weight-bold"><i class="fas fa-key text-primary mr-1"></i> Pengaturan Hak Akses Modul</label>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary preset-btn" data-target-form="#createRoleForm" data-preset="full_admin">
                                    <i class="fas fa-check-double mr-1"></i> Full Akses (Setara Admin / IT)
                                </button>
                                <button type="button" class="btn btn-outline-success preset-btn" data-target-form="#createRoleForm" data-preset="operational">
                                    <i class="fas fa-boxes mr-1"></i> Operasional Gudang & Staf
                                </button>
                                <button type="button" class="btn btn-outline-info preset-btn" data-target-form="#createRoleForm" data-preset="distributor">
                                    <i class="fas fa-store mr-1"></i> Portal Distributor
                                </button>
                                <button type="button" class="btn btn-outline-secondary preset-btn" data-target-form="#createRoleForm" data-preset="none">
                                    <i class="fas fa-eraser mr-1"></i> Reset
                                </button>
                            </div>
                        </div>

                        @foreach ($groupedPermissions as $groupName => $perms)
                            <div class="perm-group-title">{{ $groupName }}</div>
                            <div class="row">
                                @foreach ($perms as $key => $info)
                                    <div class="col-md-6 mb-2">
                                        <label class="perm-card d-flex align-items-start mb-0">
                                            <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                                class="mt-1 mr-2 perm-checkbox"
                                                {{ $key !== 'portal_distributor' ? 'checked' : '' }}>
                                            <div>
                                                <div class="font-weight-bold text-sm text-dark">
                                                    <i class="{{ $info['icon'] }} text-primary mr-1"></i> {{ $info['label'] }}
                                                </div>
                                                <small class="text-muted d-block" style="line-height: 1.2;">{{ $info['desc'] }}</small>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Peran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Role -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold" id="editRoleModalLabel">
                        <i class="fas fa-edit mr-1"></i> Edit Peran & Hak Akses
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editRoleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="permissions_submitted" value="1">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="edit_role_name">Nama Peran <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_role_name" name="role_name" required
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_role_status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_role_status" name="role_status" required>
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_role_description">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_role_description" name="role_description" rows="2" required></textarea>
                        </div>

                        <hr>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                            <label class="mb-0 font-weight-bold"><i class="fas fa-key text-info mr-1"></i> Hak Akses Modul</label>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary preset-btn" data-target-form="#editRoleForm" data-preset="full_admin">
                                    <i class="fas fa-check-double mr-1"></i> Full Akses (Setara Admin / IT)
                                </button>
                                <button type="button" class="btn btn-outline-success preset-btn" data-target-form="#editRoleForm" data-preset="operational">
                                    <i class="fas fa-boxes mr-1"></i> Operasional Gudang & Staf
                                </button>
                                <button type="button" class="btn btn-outline-info preset-btn" data-target-form="#editRoleForm" data-preset="distributor">
                                    <i class="fas fa-store mr-1"></i> Portal Distributor
                                </button>
                                <button type="button" class="btn btn-outline-secondary preset-btn" data-target-form="#editRoleForm" data-preset="none">
                                    <i class="fas fa-eraser mr-1"></i> Reset
                                </button>
                            </div>
                        </div>

                        @foreach ($groupedPermissions as $groupName => $perms)
                            <div class="perm-group-title">{{ $groupName }}</div>
                            <div class="row">
                                @foreach ($perms as $key => $info)
                                    <div class="col-md-6 mb-2">
                                        <label class="perm-card d-flex align-items-start mb-0">
                                            <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                                class="mt-1 mr-2 perm-checkbox">
                                            <div>
                                                <div class="font-weight-bold text-sm text-dark">
                                                    <i class="{{ $info['icon'] }} text-primary mr-1"></i> {{ $info['label'] }}
                                                </div>
                                                <small class="text-muted d-block" style="line-height: 1.2;">{{ $info['desc'] }}</small>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteRoleModalLabel"><i class="fas fa-exclamation-triangle"></i>
                        Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus peran ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('services.ToastModal')
    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            const adminPermissions = @json($adminPermissionKeys);
            const operationalPermissions = ['dashboard', 'gudang', 'produk', 'mutasi_stok', 'stock_opname', 'transaksi', 'orders', 'distributor', 'laporan'];

            $("#roleTable").DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            function syncPermCardVisuals($container) {
                $container.find('.perm-checkbox').each(function() {
                    $(this).closest('.perm-card').toggleClass('active-perm', $(this).is(':checked'));
                });
            }

            $(document).on('change', '.perm-checkbox', function() {
                $(this).closest('.perm-card').toggleClass('active-perm', $(this).is(':checked'));
            });

            syncPermCardVisuals($(document));

            // Handle Preset Buttons
            $(document).on('click', '.preset-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const preset = $(this).attr('data-preset') || $(this).data('preset');
                const targetSelector = $(this).attr('data-target-form') || $(this).data('targetForm') || $(this).data('target-form');
                const $targetForm = (targetSelector && $(targetSelector).length) ? $(targetSelector) : $(this).closest('form');

                let selected = [];
                if (preset === 'full_admin') {
                    selected = adminPermissions;
                } else if (preset === 'operational') {
                    selected = operationalPermissions;
                } else if (preset === 'distributor') {
                    selected = ['portal_distributor'];
                } else {
                    selected = [];
                }

                $targetForm.find('.perm-checkbox').each(function() {
                    const val = $(this).val();
                    const isChecked = selected.includes(val);
                    $(this).prop('checked', isChecked);
                });

                syncPermCardVisuals($targetForm);
            });

            // Sync visuals when any permission modal is shown
            $('#permissionsModal, #createRoleModal, #editRoleModal').on('shown.bs.modal', function() {
                syncPermCardVisuals($(this));
            });

            // Handle Manage Permissions Button Click
            $(document).on('click', '.manage-permissions-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const rawPerms = $(this).data('permissions');
                let perms = [];
                try {
                    perms = Array.isArray(rawPerms) ? rawPerms : (typeof rawPerms === 'string' ? JSON.parse(rawPerms || '[]') : []);
                } catch (err) {
                    perms = [];
                }

                const url = "{{ url('admin/role') }}/" + id + "/permissions";
                $('#permissionsForm').attr('action', url);
                $('#perm_role_title').text(name);

                $('#permissionsForm .perm-checkbox').each(function() {
                    const val = $(this).val();
                    $(this).prop('checked', perms.includes(val));
                });

                syncPermCardVisuals($('#permissionsForm'));
            });

            // Handle Edit Button Click
            $(document).on('click', '.edit-role-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const description = $(this).data('description');
                const status = $(this).data('status');
                const rawPerms = $(this).data('permissions');
                let perms = [];
                try {
                    perms = Array.isArray(rawPerms) ? rawPerms : (typeof rawPerms === 'string' ? JSON.parse(rawPerms || '[]') : []);
                } catch (err) {
                    perms = [];
                }

                const url = "{{ url('admin/role') }}/" + id;

                $('#editRoleForm').attr('action', url);
                $('#edit_role_name').val(name);
                $('#edit_role_description').val(description);
                $('#edit_role_status').val(status);

                $('#editRoleForm .perm-checkbox').each(function() {
                    const val = $(this).val();
                    $(this).prop('checked', perms.includes(val));
                });

                syncPermCardVisuals($('#editRoleForm'));
            });

            // Handle Delete Button Click
            $(document).on('click', '.delete-role-btn', function() {
                const roleId = $(this).data('role-id');
                const deleteUrl = "{{ url('admin/role') }}/" + roleId;
                $('#deleteForm').attr('action', deleteUrl);
            });

            // Handle Toggle Status
            $(document).on('change', '.toggle-status', function() {
                let roleId = $(this).data("role-id");
                let status = $(this).prop("checked") ? 1 : 0;

                $.post("{{ url('admin/role') }}/" + roleId + "/toggle-status", {
                    _token: '{{ csrf_token() }}',
                    role_status: status
                }, function(res) {
                    if (res.success) {
                        if (window.Toast) {
                            window.Toast.fire({
                                icon: 'success',
                                title: res.message
                            });
                        } else {
                            $(".toast-body").text(res.message);
                            $("#toastNotification").toast({
                                autohide: true,
                                delay: 3000
                            }).toast("show");
                        }
                    } else {
                        alert("Gagal memperbarui status.");
                    }
                }).fail(function() {
                    alert("Terjadi kesalahan dalam mengubah status.");
                });
            });
        });
    </script>
</body>

</html>
