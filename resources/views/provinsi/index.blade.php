<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provinsi - Garuda Fiber</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
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
                            <h1 class="m-0">Manajemen Provinsi</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Provinsi</h3>
                            <a href="#" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#addProvinsiModal">
                                <i class="fas fa-plus"></i> Tambah Provinsi
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="provinsiTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Provinsi</th>
                                            <th>Deskripsi</th>
                                            <th>Wilayah/Negara</th>
                                            <th>Status Provinsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($provinsis as $index => $provinsi)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $provinsi->provinsi }}</td>
                                                <td>{{ $provinsi->deskripsi }}</td>
                                                <td>{{ $provinsi->wilayah->negara }}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-provinsi-id="{{ $provinsi->id }}"
                                                        {{ $provinsi->status_provinsi ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-warning btn-sm edit-provinsi-btn"
                                                        data-toggle="modal"
                                                        data-target="#editProvinsiModal"
                                                        data-id="{{ $provinsi->id }}"
                                                        data-wilayah-id="{{ $provinsi->wilayah->negara }}"
                                                        data-provinsi="{{ $provinsi->provinsi }}"
                                                        data-deskripsi="{{ $provinsi->deskripsi }}"
                                                        data-status="{{ $provinsi->status_provinsi }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-provinsi-btn"
                                                        data-toggle="modal"
                                                        data-target="#deletepProvinsiModal"
                                                        data-provinsi-id="{{ $provinsi->id }}">
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

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteProvinsiModal" tabindex="-1" aria-labelledby="deleteProvinsiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteProvinsiModalLabel"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus provinsi ini? Tindakan ini tidak dapat dibatalkan.
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

    <!-- Modal Tambah Wilayah -->
    <div class="modal fade" id="addProvinsiModal" tabindex="-1" role="dialog" aria-labelledby="addProvinsiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('provinsi.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addProvinsiModalLabel">Tambah Provinsi Baru</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="wilayah_id">Negara</label>
                            <select name="wilayah_id" id="wilayah_id" class="form-control @error('wilayah_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Wilayah/Negara --</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id }}" {{ old('wilayah_id') == $wilayah->id ? 'selected' : '' }}>
                                        {{ $wilayah->negara }}
                                    </option>
                                @endforeach
                            </select>
                            @error('wilayah_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="negara">Nama Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status_provinsi">Status Provinsi</label>
                            <select class="form-control @error('status_provinsi') is-invalid @enderror"
                                    name="status_provinsi" required>
                                <option value="1" {{ old('status_provinsi') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status_provinsi') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status_provinsi')
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
    <div class="modal fade" id="editProvinsiModal" tabindex="-1" aria-labelledby="editProvinsiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editProvinsiForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editProvinsiLabel"><i class="fas fa-edit"></i> Ubah Provinsi</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        {{-- <input type="hidden" name="provinsi_id" id="editProvinsiId"> --}}
                        <div class="form-group">
                            <label for="wilayah_id">Wilayah</label>
                            <select name="wilayah_id" id="wilayah_id" class="form-control @error('wilayah_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Wilayah/Negara --</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id }}" {{ old('wilayah_id', $provinsi->wilayah_id) == $wilayah->id ? 'selected' : '' }}>
                                        {{ $wilayah->negara }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Provinsi</label>
                            <input type="text" name="provinsi" id="editProvinsi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Status Wilayah</label>
                            <select name="status_provinsi" id="editStatusProvinsi" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Simpan Perubahan</button>
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
        $(document).ready(function () {
            $("#provinsiTable").DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });

        $(document).ready(function () {
            $('.delete-provinsi-btn').click(function () {
                let provinsiId = $(this).data('provinsi-id');
                let deleteUrl = "{{ url('provinsi') }}/" + provinsiId;
                $('#deleteForm').attr('action', deleteUrl);
            });
        });

        $(document).ready(function () {
            $(".toggle-status").change(function () {
                let provinsiId = $(this).data("provinsi-id");
                let status = $(this).prop("checked") ? 1 : 0;

                $.post("{{ url('provinsi') }}/" + provinsiId + "/toggle-status", {
                    _token: '{{ csrf_token() }}',
                    status_provinsi: status
                }, function (res) {
                    if (res.success) {
                        $(".toast-body").text(res.message);
                        $("#toastNotification").toast({ autohide: true, delay: 3000 }).toast("show");
                    } else {
                        alert("Gagal memperbarui status.");
                    }
                }).fail(function () {
                    alert("Terjadi kesalahan dalam mengubah status.");
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
        });

        $('#addWProvinsiModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        $(document).on('click', '.edit-provinsi-btn', function () {
            let id = $(this).data('id');
            let wilayahId = $(this).data('wilayah_id')
            let provinsi = $(this).data('provinsi');
            let deskripsi = $(this).data('deskripsi');
            let status = $(this).data('status');

            $('#editProvinsiId').val(id);
            $('#wilayah_id').val(wilayahId);
            $('#editProvinsi').val(provinsi);
            $('#editDeskripsi').val(deskripsi);
            $('#editStatusProvinsi').val(status);

            let actionUrl = "{{ url('provinsi') }}/" + id;
            $('#editProvinsiForm').attr('action', actionUrl);
        });
    </script>
</body>
</html>
