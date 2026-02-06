<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Kota</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <h1 class="m-0">Manajemen Kota</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Filter Card -->
                    <div class="card collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter"></i> Filter Data</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Filter Provinsi</label>
                                        <select id="filterProvinsi" class="form-control select2">
                                            <option value="">Semua Provinsi</option>
                                            @foreach ($provinsis as $prov)
                                                <option value="{{ $prov->name }}">{{ $prov->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Kota (Sinkronisasi API)</h3>
                            <form action="{{ route('kota.sync') }}" method="POST" class="ml-auto">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Apakah anda yakin ingin sinkronisasi data kota? Proses ini mungkin sedikit lama.')">
                                    <i class="fas fa-sync"></i> Sinkronasi API
                                </button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="kotaTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Kota</th>
                                            <th>Provinsi</th>
                                            <th>Wilayah/Negara</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kotas as $kota)
                                            <tr>
                                                <td>{{ $kota->id }}</td>
                                                <td>{{ $kota->name }}</td>
                                                <td>{{ $kota->provinsi->name ?? '-' }}</td>
                                                <td>{{ $kota->provinsi->wilayah->name ?? '-' }}</td>
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
            var table = $("#kotaTable").DataTable({
                "paging": true,
                "lengthChange": true, // Allow changing length
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            $('.sync-btn-kota').click(function(e) {
                e.preventDefault();
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Konfirmasi Sinkronisasi',
                    text: "Apakah anda yakin ingin sinkronisasi data Kota? Proses ini akan memperbarui data dari API dan mungkin memakan waktu beberapa saat.",
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

            // Filter Logic
            $('#filterProvinsi').on('change', function() {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                // Filter is on column index 2 (Provinsi)
                table.column(2).search(val ? '^' + val + '$' : '', true, false).draw();
            });
        });
    </script>
</body>

</html>
