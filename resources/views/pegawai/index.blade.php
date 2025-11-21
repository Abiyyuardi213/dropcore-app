<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Pegawai</title>
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
                        <h1 class="m-0">Manajemen Pegawai</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Pegawai</h3>

                        <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm ml-auto">
                            <i class="fas fa-user-plus"></i> Tambah Pegawai
                        </a>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="pegawaiTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Divisi</th>
                                    <th>Jabatan</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($pegawai as $index => $pgw)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pgw->nip }}</td>
                                        <td>{{ $pgw->name }}</td>
                                        <td>{{ $pgw->divisi->name ?? '-' }}</td>
                                        <td>{{ $pgw->jabatan->name ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pgw->tanggal_bergabung)->translatedFormat('d F Y') }}</td>
                                        <td>
                                            @if($pgw->status_kepegawaian == 'aktif')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('pegawai.show', $pgw->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>

                                            <a href="{{ route('pegawai.edit', $pgw->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <button class="btn btn-danger btn-sm delete-pegawai-btn"
                                                    data-toggle="modal"
                                                    data-target="#deletePegawaiModal"
                                                    data-pegawai-id="{{ $pgw->id }}">
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

<!-- Modal Hapus -->
<div class="modal fade" id="deletePegawaiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pegawai ini? Tindakan ini tidak dapat dibatalkan.
            </div>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
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
<script src="{{ asset('js/ToastScript.js') }}"></script>

<script>
    $(document).ready(function () {
        $("#pegawaiTable").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true
        });
    });

    $(document).on('click', '.delete-pegawai-btn', function () {
        let id = $(this).data('pegawai-id');
        let deleteUrl = "{{ url('pegawai') }}/" + id;
        $('#deleteForm').attr('action', deleteUrl);
    });

    $(document).ready(function() {
        @if (session('success') || session('error'))
        $('#toastNotification').toast({
            delay: 3000,
            autohide: true
        }).toast('show');
        @endif
    });
</script>

</body>
</html>
