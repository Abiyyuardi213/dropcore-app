<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Barang | DropCore</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .penerimaan-toolbar { display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; }
        .penerimaan-filters { display: flex; flex: 1; flex-wrap: wrap; align-items: flex-end; gap: .5rem; min-width: 0; }
        .penerimaan-filters .form-group { margin-bottom: 0; }
        .penerimaan-toolbar label { font-size: .875rem; margin-bottom: .25rem; }
        .penerimaan-search { width: 200px; margin-left: auto; }
        .penerimaan-filter-actions { display: flex; gap: .5rem; }
        @media (max-width: 767.98px) {
            .penerimaan-filters, .penerimaan-filters .form-group, .penerimaan-search { width: 100%; flex-basis: 100%; }
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
                            <h1 class="m-0">Manajemen Penerimaan Barang</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Penerimaan Barang</h3>
                            <div class="card-tools">
                                <a href="{{ route('penerimaan-barang.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Penerimaan
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                            @endif
                            <div class="penerimaan-toolbar">
                                <form action="{{ route('penerimaan-barang.index') }}" method="GET" class="penerimaan-filters">
                                    <div class="form-group">
                                        <label for="tanggal_awal">Tanggal Awal</label>
                                        <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control form-control-sm" value="{{ request('tanggal_awal') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_akhir">Tanggal Akhir</label>
                                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tipe_pengirim">Tipe Pengirim</label>
                                        <select id="tipe_pengirim" name="tipe_pengirim" class="form-control form-control-sm">
                                            <option value="">-- Semua Pengirim --</option>
                                            <option value="supplier" @selected(request('tipe_pengirim') == 'supplier')>Supplier</option>
                                            <option value="distributor" @selected(request('tipe_pengirim') == 'distributor')>Distributor</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" name="status" class="form-control form-control-sm">
                                            <option value="">-- Semua Status --</option>
                                            <option value="canceled" @selected(request('status') == 'canceled')>Canceled</option>
                                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                            <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                                        </select>
                                    </div>
                                    <div class="penerimaan-filter-actions">
                                        <a href="{{ route('penerimaan-barang.index') }}" class="btn btn-default btn-sm">Reset</a>
                                        <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button>
                                    </div>
                                </form>
                                <div class="penerimaan-search">
                                    <label for="penerimaanSearch">Search:</label>
                                    <input type="search" id="penerimaanSearch" class="form-control form-control-sm" placeholder="Cari penerimaan..." aria-controls="penerimaanTable">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="penerimaanTable" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Penerimaan</th>
                                            <th>Tanggal</th>
                                            <th>Distributor / Supplier</th>
                                            <th>Referensi</th>
                                            <th>Penerima (User)</th>
                                            <th>Status Barang</th>
                                            <th>Pembayaran</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data as $index => $p)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="text-bold text-primary">{{ $p->no_penerimaan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($p->tanggal_penerimaan)->format('d M Y') }}
                                                </td>
                                                <td>
                                                    @if ($p->distributor)
                                                        {{ $p->distributor->nama_distributor }}
                                                    @elseif($p->supplier)
                                                        {{-- Fallback --}}
                                                        {{ $p->supplier->nama_supplier }}
                                                    @else
                                                        <span class="text-muted text-sm fst-italic">Tidak
                                                            diketahui</span>
                                                    @endif
                                                </td>
                                                <td>{{ $p->referensi ?? '-' }}</td>
                                                <td>{{ $p->user->name ?? 'System' }}</td>
                                                <td>
                                                    @if ($p->status == 'completed')
                                                        <span class="badge badge-success">Completed</span>
                                                    @else
                                                        <span
                                                            class="badge badge-warning">{{ ucfirst($p->status ?? 'Draft') }}</span>
                                                    @endif
                                                </td>
                                                <td><span class="badge badge-{{ $p->payment_status === 'paid' ? 'success' : 'secondary' }}">{{ ['paid' => 'Lunas', 'unpaid' => 'Belum dibayar', 'reversed' => 'Dibalik'][$p->payment_status] ?? 'Belum dibayar' }}</span></td>
                                                <td class="text-center">
                                                    <a href="{{ route('penerimaan-barang.show', $p->id) }}"
                                                        class="btn btn-info btn-xs" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('penerimaan-barang.print', $p->id) }}"
                                                        target="_blank" class="btn btn-secondary btn-xs"
                                                        title="Cetak Surat Jalan/Invoice">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-xs btn-delete"
                                                        data-id="{{ $p->id }}" title="Hapus / Batalkan">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $p->id }}"
                                                        action="{{ route('penerimaan-barang.destroy', $p->id) }}"
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
            var table = $("#penerimaanTable").DataTable({
                "dom": 'rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false, // Disable ordering to keep server side latest() order
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            $('#penerimaanSearch').on('input', function() {
                table.search(this.value).draw();
            });

            // SweetAlert Delete
            $('.btn-delete').click(function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
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
