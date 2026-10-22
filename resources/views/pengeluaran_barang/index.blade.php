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
    <style>
        .pengeluaran-toolbar { display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
        .pengeluaran-filters { display: flex; flex: 1; flex-wrap: wrap; align-items: flex-end; gap: .5rem; min-width: 0; }
        .pengeluaran-filters .form-group { margin-bottom: 0; }
        .pengeluaran-toolbar label { font-size: .875rem; margin-bottom: .25rem; }
        .pengeluaran-search { width: 200px; margin-left: auto; }
        .pengeluaran-filter-actions { display: flex; gap: .5rem; }
        @media (max-width: 767.98px) {
            .pengeluaran-filters, .pengeluaran-filters .form-group, .pengeluaran-search { width: 100%; flex-basis: 100%; }
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
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                            @endif
                            <div class="pengeluaran-toolbar">
                                <form action="{{ route('pengeluaran-barang.index') }}" method="GET" class="pengeluaran-filters">
                                    <div class="form-group">
                                        <label for="tanggal_awal">Tanggal Awal</label>
                                        <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control form-control-sm" value="{{ request('tanggal_awal') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_akhir">Tanggal Akhir</label>
                                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tipe_penerima">Tipe Penerima</label>
                                        <select id="tipe_penerima" name="tipe_penerima" class="form-control form-control-sm">
                                            <option value="">-- Semua Penerima --</option>
                                            <option value="konsumen" @selected(request('tipe_penerima') == 'konsumen')>Konsumen</option>
                                            <option value="distributor" @selected(request('tipe_penerima') == 'distributor')>Distributor</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" name="status" class="form-control form-control-sm">
                                            <option value="">-- Semua Status --</option>
                                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                            <option value="canceled" @selected(request('status') == 'canceled')>Canceled</option>
                                            <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                                        </select>
                                    </div>
                                    <div class="pengeluaran-filter-actions">
                                        <a href="{{ route('pengeluaran-barang.index') }}" class="btn btn-default btn-sm">Reset</a>
                                        <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button>
                                    </div>
                                </form>
                                <div class="pengeluaran-search">
                                    <label for="pengeluaranSearch">Search:</label>
                                    <input type="search" id="pengeluaranSearch" class="form-control form-control-sm" placeholder="Cari pengeluaran..." aria-controls="pengeluaranTable">
                                </div>
                            </div>
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
                                            <th>Status Barang</th>
                                            <th>Pembayaran</th>
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
                                                <td><span class="badge badge-{{ $item->payment_status === 'paid' ? 'success' : 'secondary' }}">{{ ['paid' => 'Lunas', 'unpaid' => 'Belum dibayar', 'reversed' => 'Dibalik'][$item->payment_status] ?? 'Belum dibayar' }}</span></td>
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
            var table = $("#pengeluaranTable").DataTable({
                "dom": 'rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            $('#pengeluaranSearch').on('input', function() {
                table.search(this.value).draw();
            });

            // SweetAlert Delete
            $('.btn-delete').click(function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Batalkan Pengeluaran?',
                    text: "Pembatalan transaksi selesai membalik stok dan pembayaran. Draft tidak mengubah stok atau saldo.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Batalkan Transaksi',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-form-' + id).submit();
                    }
                });
            });

            @if (session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: {!! json_encode(session('success')) !!}
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
