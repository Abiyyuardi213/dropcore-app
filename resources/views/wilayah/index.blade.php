<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Wilayah</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <h1 class="m-0">Manajemen Wilayah/Negara</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Wilayah/Negara</h3>
                            <div class="ml-auto">
                                <form action="{{ route('wilayah.sync') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-success btn-sm mr-2 sync-btn-wilayah">
                                        <i class="fas fa-sync"></i> Sinkronasi API
                                    </button>
                                </form>
                                <a href="#" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#addWilayahModal">
                                    <i class="fas fa-plus"></i> Tambah Wilayah
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="wilayahTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Wilayah</th>
                                            <th>Deskripsi</th>
                                            <th>Status Wilayah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wilayahs as $index => $wilayah)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $wilayah->negara }}</td>
                                                <td>{{ $wilayah->deskripsi }}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-wilayah-id="{{ $wilayah->id }}"
                                                        {{ $wilayah->status_wilayah ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-warning btn-sm edit-wilayah-btn"
                                                        data-toggle="modal" data-target="#editWilayahModal"
                                                        data-id="{{ $wilayah->id }}"
                                                        data-negara="{{ $wilayah->negara }}"
                                                        data-deskripsi="{{ $wilayah->deskripsi }}"
                                                        data-status="{{ $wilayah->status_wilayah }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-wilayah-btn"
                                                        data-wilayah-id="{{ $wilayah->id }}">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="tablePagination"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>



    <!-- Modal Tambah Wilayah -->
    <div class="modal fade" id="addWilayahModal" tabindex="-1" role="dialog" aria-labelledby="addWilayahModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('wilayah.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addWilayahModalLabel">Tambah Wilayah Baru</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="negara">Nama Wilayah/Negara</label>
                            <input type="text" name="negara" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status_wilayah">Status Wilayah</label>
                            <select class="form-control @error('status_wilayah') is-invalid @enderror"
                                name="status_wilayah" required>
                                <option value="1" {{ old('status_wilayah') == '1' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="0" {{ old('status_wilayah') == '0' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                            @error('status_wilayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Wilayah -->
    <div class="modal fade" id="editWilayahModal" tabindex="-1" aria-labelledby="editWilayahLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editWilayahForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editWilayahLabel"><i class="fas fa-edit"></i> Ubah Wilayah</h5>
                        <button type="button" class="close text-white"
                            data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="wilayah_id" id="editWilayahId">
                        <div class="form-group">
                            <label>Nama Wilayah / Negara</label>
                            <input type="text" name="negara" id="editNegara" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Status Wilayah</label>
                            <select name="status_wilayah" id="editStatusWilayah" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Simpan
                            Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('services.ToastModal')
    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/ToastScript.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#wilayahTable").DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            // Sync Confirmation & Loading
            $('.sync-btn-wilayah').click(function(e) {
                e.preventDefault();
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Konfirmasi Sinkronisasi',
                    text: "Apakah anda yakin ingin sinkronisasi data wilayah? Proses ini akan memperbarui data dari API.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Sinkronisasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            html: 'Mohon tunggu, sinkronisasi data sedang berjalan.<br>Jangan tutup halaman ini.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        form.submit();
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.delete-wilayah-btn').click(function() {
                let wilayahId = $(this).data('wilayah-id');
                let deleteUrl = "{{ route('wilayah.index') }}/" + wilayahId;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data wilayah ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.createElement('form');
                        form.action = deleteUrl;
                        form.method = 'POST';
                        form.innerHTML = `
                            @csrf
                            @method('DELETE')
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            $(".toggle-status").change(function() {
                let wilayahId = $(this).data("wilayah-id");
                let status = $(this).prop("checked") ? 1 : 0;
                let checkbox = $(this);
                let originalState = !status;

                $.post("{{ route('wilayah.index') }}/" + wilayahId + "/toggle-status", {
                    _token: '{{ csrf_token() }}',
                    status_wilayah: status
                }, function(res) {
                    if (res.success) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                    } else {
                        Swal.fire('Gagal!', 'Gagal memperbarui status.', 'error');
                        checkbox.prop("checked", originalState);
                    }
                }).fail(function() {
                    Swal.fire('Error!', 'Terjadi kesalahan dalam mengubah status.', 'error');
                    checkbox.prop("checked", originalState);
                });
            });
        });

        $(document).ready(function() {
            @if (session('success') || session('error'))
                $('#toastNotification').toast({
                    delay: 3000,
                    autohide: true
                }).toast('show');
            @endif

            // SweetAlert Flash Messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                });
            @endif
        });

        $('#addWilayahModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
        });

        $(document).on('click', '.edit-wilayah-btn', function() {
            let id = $(this).data('id');
            let negara = $(this).data('negara');
            let deskripsi = $(this).data('deskripsi');
            let status = $(this).data('status');

            $('#editWilayahId').val(id);
            $('#editNegara').val(negara);
            $('#editDeskripsi').val(deskripsi);
            $('#editStatusWilayah').val(status);

            let actionUrl = "{{ route('wilayah.index') }}/" + id;
            $('#editWilayahForm').attr('action', actionUrl);
        });
    </script>
</body>

</html>
