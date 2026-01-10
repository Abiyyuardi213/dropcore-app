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
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
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
                                <table id="mutasiStokTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th>Tanggal</th>
                                            <th class="text-center">Jenis</th>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Lokasi Asal</th>
                                            <th>Lokasi Tujuan</th>
                                            <th>User</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mutasi as $index => $m)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($m->tanggal_mutasi)->format('d M Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($m->jenis_mutasi == 'masuk')
                                                        <span class="badge badge-success">Masuk</span>
                                                    @elseif($m->jenis_mutasi == 'keluar')
                                                        <span class="badge badge-danger">Keluar</span>
                                                    @else
                                                        <span class="badge badge-info">Pindah</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $m->produk->name ?? '-' }}</strong><br>
                                                    <small class="text-muted">{{ $m->referensi ?? '' }}</small>
                                                </td>
                                                <td>{{ $m->quantity }}</td>
                                                <td>
                                                    @if ($m->jenis_mutasi == 'masuk')
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        {{ $m->gudangAsal->nama_gudang ?? '-' }} <br>
                                                        <small
                                                            class="text-muted">{{ $m->rakAsal->kode_rak ?? '' }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($m->jenis_mutasi == 'keluar')
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        {{ $m->gudangTujuan->nama_gudang ?? '-' }} <br>
                                                        <small
                                                            class="text-muted">{{ $m->rakTujuan->kode_rak ?? '' }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $m->user->name ?? 'System' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('mutasi-stok.show', $m->id) }}"
                                                        class="btn btn-sm btn-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('mutasi-stok.print', $m->id) }}" target="_blank"
                                                        class="btn btn-sm btn-default" title="Print Invoice"><i
                                                            class="fas fa-print"></i></a>
                                                    {{-- Edit/Delete disabled for integrity/safety unless strictly allowed --}}
                                                    <form action="{{ route('mutasi-stok.destroy', $m->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Hapus"><i class="fas fa-trash"></i></button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#mutasiStokTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            // SweetAlert for Session Messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}'
                });
            @endif

            // Delete Confirmation
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Apakah Anda yakin ingin menghapus data mutasi ini? Stok tidak akan dikembalikan otomatis (Manual Adjustment mungkin diperlukan).",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
