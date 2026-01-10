<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Peran</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
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
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('include.navbarSistem')
        @include('include.sidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Manajemen Peran</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Peran</h3>
                            <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal"
                                data-target="#createRoleModal">
                                <i class="fas fa-plus"></i> Tambah Peran
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="roleTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Peran</th>
                                            <th>Deskripsi</th>
                                            <th>Status Peran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $index => $role)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $role->role_name }}</td>
                                                <td>{{ $role->role_description }}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-role-id="{{ $role->id }}"
                                                        {{ $role->role_status ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-info btn-sm edit-role-btn"
                                                        data-id="{{ $role->id }}" data-name="{{ $role->role_name }}"
                                                        data-description="{{ $role->role_description }}"
                                                        data-status="{{ $role->role_status }}" data-toggle="modal"
                                                        data-target="#editRoleModal">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-role-btn"
                                                        data-toggle="modal" data-target="#deleteRoleModal"
                                                        data-role-id="{{ $role->id }}">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
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

    <!-- Modal Tambah Role -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoleModalLabel">Tambah Peran Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="role_name">Nama Peran</label>
                            <input type="text" class="form-control" name="role_name" required
                                placeholder="Masukkan nama peran" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="role_description">Deskripsi</label>
                            <textarea class="form-control" name="role_description" required placeholder="Masukkan deskripsi peran"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="role_status">Status</label>
                            <select class="form-control" name="role_status" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Role -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Peran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editRoleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_role_name">Nama Peran</label>
                            <input type="text" class="form-control" id="edit_role_name" name="role_name" required
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="edit_role_description">Deskripsi</label>
                            <textarea class="form-control" id="edit_role_description" name="role_description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_role_status">Status</label>
                            <select class="form-control" id="edit_role_status" name="role_status" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
            $("#roleTable").DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            // Handle Edit Button Click
            $('.edit-role-btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var description = $(this).data('description');
                var status = $(this).data('status');

                var url = "{{ url('role') }}/" + id;

                $('#editRoleForm').attr('action', url);
                $('#edit_role_name').val(name);
                $('#edit_role_description').val(description);
                $('#edit_role_status').val(status);
            });

            // Handle Delete Button Click
            $('.delete-role-btn').click(function() {
                let roleId = $(this).data('role-id');
                let deleteUrl = "{{ url('role') }}/" + roleId;
                $('#deleteForm').attr('action', deleteUrl);
            });

            // Handle Toggle Status
            $(".toggle-status").change(function() {
                let roleId = $(this).data("role-id");
                let status = $(this).prop("checked") ? 1 : 0;

                $.post("{{ url('role') }}/" + roleId + "/toggle-status", {
                    _token: '{{ csrf_token() }}',
                    role_status: status
                }, function(res) {
                    if (res.success) {
                        // Use Bootstrap 5 Toast if available, or fall back to whatever is used
                        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                            // Assuming standard BS toast structure creation in JS for simple messages, 
                            // but since we have a specific Toast structure in ToastModal, we might just reload or show alert if ToastModal isn't dynamic enough for AJAX updates without reload.
                            // However, the ToastModal.blade.php relies on session flask. 
                            // For AJAX, we often need a JS function to trigger it. 
                            // The existing code tried to set text and show. Let's keep that pattern if it works.

                            // Update the toast body content if generic generic toast exists
                            var toastBody = $(".toast-body");
                            if (toastBody.length) {
                                toastBody.text(res.message);
                                $("#toastNotification").toast('show');
                            }
                        } else {
                            // Fallback for BS4 as per original code pattern
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
