<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Keuangan</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">

    <style>
        .info-box-number {
            font-size: 1.5rem;
            font-weight: 700;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('include.navbarSistem')
        @include('include.sidebar')

        <div class="content-wrapper">

            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Keuangan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <!-- Statistik Pemasukkan & Pengeluaran -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box shadow-lg">
                                <span class="info-box-icon bg-success elevation-1">
                                    <i class="fas fa-arrow-down"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pemasukkan</span>
                                    <span class="info-box-number text-success">
                                        Rp
                                        {{ number_format($data->where('jenis_transaksi', 'pemasukkan')->sum('jumlah'), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box shadow-lg">
                                <span class="info-box-icon bg-danger elevation-1">
                                    <i class="fas fa-arrow-up"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pengeluaran</span>
                                    <span class="info-box-number text-danger">
                                        Rp
                                        {{ number_format($data->where('jenis_transaksi', 'pengeluaran')->sum('jumlah'), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Transaksi Keuangan -->
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Transaksi Keuangan</h3>
                            <a href="{{ route('keuangan.create') }}" class="btn btn-primary btn-sm ml-auto">
                                <i class="fas fa-plus"></i> Tambah Transaksi
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="keuanganTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Transaksi</th>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th>Akun</th>
                                            <th>Debit (Masuk)</th>
                                            <th>Kredit (Keluar)</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td class="font-weight-bold">{{ $item->no_transaksi ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    @if ($item->kategori)
                                                        <span
                                                            class="badge badge-light border">{{ $item->kategori->nama }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $item->keterangan }}</td>
                                                <td>{{ $item->sumber ? $item->sumber->nama_sumber : '-' }}</td>

                                                <!-- Pemasukkan -->
                                                <td class="text-right text-success">
                                                    @if ($item->jenis_transaksi == 'pemasukkan')
                                                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                                    @endif
                                                </td>

                                                <!-- Pengeluaran -->
                                                <td class="text-right text-danger">
                                                    @if ($item->jenis_transaksi == 'pengeluaran')
                                                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if ($item->bukti_transaksi)
                                                        <a href="{{ asset('uploads/keuangan/' . $item->bukti_transaksi) }}"
                                                            target="_blank" class="btn btn-xs btn-info"
                                                            title="Lihat Bukti">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </a>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <a href="{{ route('keuangan.edit', $item->id) }}"
                                                        class="btn btn-warning btn-xs" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-xs delete-keuangan-btn"
                                                        data-toggle="modal" data-target="#deleteKeuanganModal"
                                                        data-keuangan-id="{{ $item->id }}"
                                                        data-delete-route="{{ route('keuangan.destroy', $item->id) }}">
                                                        <i class="fas fa-trash"></i>
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

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteKeuanganModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.
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

    @include('services.ToastModal')
    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#keuanganTable").DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            $('.delete-keuangan-btn').click(function() {
                let keuanganId = $(this).data('keuangan-id');
                let deleteUrl = "{{ url('keuangan') }}/" + keuanganId;
                $('#deleteForm').attr('action', deleteUrl);
            });

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
