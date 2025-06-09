<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Kelurahan</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
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
                            <h1 class="m-0">Manajemen Kelurahan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Kelurahan</h3>
                            <a href="#" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#addKelurahanModal">
                                <i class="fas fa-plus"></i> Tambah Kelurahan
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="kelurahanTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kelurahan</th>
                                            <th>Kecamatan</th>
                                            <th>Kota</th>
                                            <th>Provinsi</th>
                                            <th>Wilayah/Negara</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kelurahans as $index => $kelurahan)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $kelurahan->kelurahan }}</td>
                                                <td>{{ $kelurahan->kecamatan->kecamatan }}</td>
                                                <td>{{ $kelurahan->kecamatan->kota->kota }}</td>
                                                <td>{{ $kelurahan->kecamatan->kota->provinsi->provinsi }}</td>
                                                <td>{{ $kelurahan->kecamatan->kota->provinsi->wilayah->negara }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-warning btn-sm edit-kelurahan-btn"
                                                        data-toggle="modal"
                                                        data-target="#editKelurahanModal"
                                                        data-id="{{ $kelurahan->id }}"
                                                        data-kecamatan-id="{{ $kelurahan->kecamatan->id }}"
                                                        data-kelurahan="{{ $kelurahan->kelurahan }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-kelurahan-btn"
                                                        data-toggle="modal"
                                                        data-target="#deletepKelurahanModal"
                                                        data-kelurahan-id="{{ $kelurahan->id }}">
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
    <div class="modal fade" id="deleteKelurahanModal" tabindex="-1" aria-labelledby="deleteKelurahanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteKelurahanModalLabel"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Kelurahan ini? Tindakan ini tidak dapat dibatalkan.
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

    <!-- Modal Tambah Kelurahan -->
    <div class="modal fade" id="addKelurahanModal" tabindex="-1" role="dialog" aria-labelledby="addKelueahanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('kelurahan.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addKelurahanModalLabel">Tambah Kelurahan Baru</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kelurahan_id">Kota</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control @error('kecamatan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kecamatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="kelurahan">Nama Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control" required>
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

    <!-- Modal Edit Kelurahan -->
    <div class="modal fade" id="editKelurahanModal" tabindex="-1" aria-labelledby="editKelurahanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editKelurahanForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editKelurahanLabel"><i class="fas fa-edit"></i> Ubah Kelurahan</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control @error('kecamatan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $kelurahan->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Kelurahan</label>
                            <input type="text" name="kelurahan" id="editKelurahan" class="form-control" required>
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
            $("#kelurahanTable").DataTable({
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
            $('.delete-kelurahan-btn').click(function () {
                let kelurahanId = $(this).data('kelurahan-id');
                let deleteUrl = "{{ url('kelurahan') }}/" + kelurahanId;
                $('#deleteForm').attr('action', deleteUrl);
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

        $('#addWKelurahanModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        $(document).on('click', '.edit-kelurahan-btn', function () {
            let id = $(this).data('id');
            let kecamatanId = $(this).data('kecamatan_id');
            let kelurahanId = $(this).data('kelurahan_id');
            let kelurahan = $(this).data('kelurahan');

            $('#editKelurahanId').val(id);
            $('#edit_kecamatan_id').val(kecamatanId);
            $('#editKelurahan').val(kelurahan);

            let actionUrl = "{{ url('kelurahan') }}/" + id;
            $('#editKelurahanForm').attr('action', actionUrl);
        });
    </script>
</body>
</html>
