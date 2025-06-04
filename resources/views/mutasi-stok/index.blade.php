<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Garuda Fiber - Mutasi Stok</title>
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
                            <h1 class="m-0">Manajemen Mutasi Stok</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Mutasi Stok</h3>
                            <a href="{{ route('mutasi-stok.create') }}" class="btn btn-primary btn-sm ml-auto">
                                <i class="fas fa-plus"></i> Tambah Mutasi
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="mutasiStokTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Tipe</th>
                                            <th>Gudang Asal</th>
                                            <th>Gudang Tujuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mutasi as $index => $m)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $m->tanggal_mutasi }}</td>
                                                <td>{{ $m->produk->name ?? '-' }}</td>
                                                <td>{{ $m->quantity }}</td>
                                                <td>{{ ucfirst($m->tipe) }}</td>
                                                <td>
                                                    {{ $m->gudangAsal->nama_gudang ?? '-' }} /
                                                    {{ $m->areaAsal->kode_area ?? '-' }} /
                                                    {{ $m->rakAsal->kode_rak ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $m->gudangTujuan->nama_gudang ?? '-' }} /
                                                    {{ $m->areaTujuan->kode_area ?? '-' }} /
                                                    {{ $m->rakTujuan->kode_rak ?? '-' }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('mutasi-stok.show', $m->id) }}" class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                    <a href="{{ route('mutasi-stok.edit', $m->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('mutasi-stok.destroy', $m->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
            $('#mutasiStokTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            @if (session('success') || session('error'))
                $('#toastNotification').toast({ delay: 3000, autohide: true }).toast('show');
            @endif
        });
    </script>
</body>
</html>
