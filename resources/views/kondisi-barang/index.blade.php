<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Kondisi Barang</title>
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
                            <h1 class="m-0">Manajemen Kondisi Barang</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Kondisi Barang</h3>
                            <a href="#" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#addKondisiModal">
                                <i class="fas fa-plus"></i> Tambah Kondisi
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="kondisiTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kondisi</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kondisis as $index => $kondisi)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $kondisi->nama_kondisi }}</td>
                                                <td>{{ $wilayah->deskripsi }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-warning btn-sm edit-kondisi-btn"
                                                        data-toggle="modal"
                                                        data-target="#editKondisiModal"
                                                        data-id="{{ $kondisi->id }}"
                                                        data-negara="{{ $kondisi->nama_kondisi }}"
                                                        data-deskripsi="{{ $kondisi->deskripsi }}"
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-kondisi-btn"
                                                        data-toggle="modal"
                                                        data-target="#deleteKondisiModal"
                                                        data-kondisi-id="{{ $kondisi->id }}">
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
    <div class="modal fade" id="deleteKondisiModal" tabindex="-1" aria-labelledby="deleteKondisiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteKondisisModalLabel"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kondisi ini? Tindakan ini tidak dapat dibatalkan.
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
    <div class="modal fade" id="addKondisiModal" tabindex="-1" role="dialog" aria-labelledby="addKondisiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('kondisi-barang.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addKondisiModalLabel">Tambah Kondisi Baru</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_kondisi">Nama Kondisi</label>
                            <input type="text" name="negara" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
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
    <div class="modal fade" id="editKondisiModal" tabindex="-1" aria-labelledby="editKondisiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editWilayahForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editKondisiLabel"><i class="fas fa-edit"></i> Ubah Kondisi</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="kondisi_id" id="editKondisiId">
                        <div class="form-group">
                            <label>Nama Kondisi Barang</label>
                            <input type="text" name="nama_kondisi" id="editKondisiBarang" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control"></textarea>
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
            $("#kondisiTable").DataTable({
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
            $('.delete-kondisi-btn').click(function () {
                let kondisiId = $(this).data('kondisi-id');
                let deleteUrl = "{{ url('kondisi-barang') }}/" + kondisiId;
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

        $('#addKondisiModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        $(document).on('click', '.edit-kondisi-btn', function () {
            let id = $(this).data('id');
            let nama_kondisi = $(this).data('nama_kondisi');
            let deskripsi = $(this).data('deskripsi');

            $('#editKondisiId').val(id);
            $('#editNamaKondisi').val(nama_kondisi);
            $('#editDeskripsi').val(deskripsi);

            let actionUrl = "{{ url('kondisi-barang') }}/" + id;
            $('#editKondisiForm').attr('action', actionUrl);
        });
    </script>
</body>
</html>
