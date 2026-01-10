<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran Barang | DropCore</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                            <h1 class="m-0">Manajemen Pengeluaran Barang</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Pengeluaran Barang</h3>
                            <div class="card-tools">
                                <a href="{{ route('pengeluaran-barang.create') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Pengeluaran
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pengeluaranTable" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Pengeluaran</th>
                                            <th>Tanggal</th>
                                            <th>Tujuan (Penerima)</th>
                                            <th>Ref.</th>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="text-bold text-primary">{{ $item->no_pengeluaran }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d M Y') }}
                                                </td>
                                                <td>
                                                    @if ($item->tipe_penerima == 'distributor')
                                                        <span class="badge badge-info"><i class="fas fa-truck"></i>
                                                            Dist.</span>
                                                        {{ $item->distributor->nama_distributor ?? 'Unknown' }}
                                                    @else
                                                        <span class="badge badge-success"><i class="fas fa-user"></i>
                                                            Kons.</span>
                                                        {{ $item->nama_konsumen }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->referensi ?? '-' }}</td>
                                                <td>{{ $item->user->name ?? 'System' }}</td>
                                                <td>
                                                    @if ($item->status == 'completed')
                                                        <span class="badge badge-success">Completed</span>
                                                    @elseif($item->status == 'canceled')
                                                        <span class="badge badge-danger">Canceled</span>
                                                    @else
                                                        <span
                                                            class="badge badge-secondary">{{ ucfirst($item->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('pengeluaran-barang.show', $item->id) }}"
                                                        class="btn btn-info btn-xs" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pengeluaran-barang.print', $item->id) }}"
                                                        target="_blank" class="btn btn-secondary btn-xs"
                                                        title="Cetak Surat Jalan">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-xs btn-delete"
                                                        data-id="{{ $item->id }}" title="Batal / Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('pengeluaran-barang.destroy', $item->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
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

    @include('services.LogoutModal')

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#pengeluaranTable").DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [
                    [2, "desc"]
                ]
            });

            // SweetAlert Delete
            $('.btn-delete').click(function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Batalkan Pengeluaran?',
                    text: "Menghapus data ini akan MENGEMBALIKAN stok barang ke gudang!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus & Refund Stok!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-form-' + id).submit();
                    }
                });
            });

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
    </script>
</body>

</html>
