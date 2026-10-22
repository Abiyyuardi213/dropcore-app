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
        .keuangan-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .keuangan-filters,
        .keuangan-filter-fields,
        .keuangan-filter-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: .5rem;
        }

        .keuangan-filters {
            flex: 1;
            min-width: 0;
        }

        .keuangan-filters .form-group {
            margin-bottom: 0;
        }

        .keuangan-filters label,
        .keuangan-search label {
            margin-bottom: .25rem;
            font-size: .875rem;
        }

        .keuangan-filter-fields .row {
            margin: 0 -.25rem;
        }

        .keuangan-filter-fields .row > div {
            padding: 0 .25rem;
        }

        .keuangan-search {
            margin-left: auto;
            width: 200px;
        }

        @media (max-width: 767.98px) {
            .keuangan-filters,
            .keuangan-filter-fields,
            .keuangan-filters .form-group,
            .keuangan-search {
                width: 100%;
                flex-basis: 100%;
            }
        }

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
                            <div class="ml-auto">
                                <a href="{{ route('keuangan.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Transaksi
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="keuangan-toolbar">
                                <form id="keuanganFilters" class="keuangan-filters" action="{{ route('keuangan.index') }}" method="GET">
                                    <div class="keuangan-filter-fields">
                                        <div class="form-group">
                                            <label for="tanggal_awal">Rentang Tanggal</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="date" id="tanggal_awal" name="tanggal_awal" aria-label="Tanggal awal" class="form-control form-control-sm"
                                                        value="{{ request('tanggal_awal') }}">
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" id="tanggal_akhir" name="tanggal_akhir" aria-label="Tanggal akhir" class="form-control form-control-sm"
                                                        value="{{ request('tanggal_akhir') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="jenis_transaksi">Jenis Transaksi</label>
                                            <select id="jenis_transaksi" name="jenis_transaksi" class="form-control form-control-sm">
                                                <option value="">-- Semua Jenis --</option>
                                                <option value="pemasukkan"
                                                    {{ request('jenis_transaksi') == 'pemasukkan' ? 'selected' : '' }}>Pemasukkan
                                                </option>
                                                <option value="pengeluaran"
                                                    {{ request('jenis_transaksi') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="kategori_keuangan_id">Kategori</label>
                                            <select id="kategori_keuangan_id" name="kategori_keuangan_id" class="form-control form-control-sm">
                                                <option value="">-- Semua Kategori --</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ request('kategori_keuangan_id') == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="sumber_id">Akun Keuangan</label>
                                            <select id="sumber_id" name="sumber_id" class="form-control form-control-sm">
                                                <option value="">-- Semua Akun --</option>
                                                @foreach ($sumberKeuangan as $sumber)
                                                    <option value="{{ $sumber->id }}"
                                                        {{ request('sumber_id') == $sumber->id ? 'selected' : '' }}>
                                                        {{ $sumber->nama_sumber }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="keuangan-filter-actions">
                                        <a href="{{ route('keuangan.index') }}" class="btn btn-default btn-sm">Reset</a>
                                        <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button>
                                    </div>
                                </form>
                                <div class="keuangan-search">
                                    <label for="keuanganSearch">Search:</label>
                                    <input type="search" id="keuanganSearch" class="form-control form-control-sm"
                                        placeholder="Cari transaksi..." aria-controls="keuanganTable">
                                </div>
                            </div>
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
                                                    @if ($data->first()->status)
                                                        <span
                                                            class="badge {{ $item->status == 'approved' ? 'badge-success' : 'badge-warning' }}">
                                                            {{ ucfirst($item->status) }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">Pending</span>
                                                    @endif
                                                </td>

                                                {{-- 
                                                <td class="text-center">
                                                    @if ($item->bukti_transaksi)
                                                        <a href="{{ asset('uploads/keuangan/' . $item->bukti_transaksi) }}"
                                                            target="_blank" class="btn btn-xs btn-info"
                                                            title="Lihat Bukti">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                --}}

                                                <td class="text-center">
                                                    <a href="{{ route('keuangan.show', $item->id) }}"
                                                        class="btn btn-info btn-xs" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('keuangan.edit', $item->id) }}"
                                                        class="btn btn-warning btn-xs" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-xs delete-keuangan-btn"
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
            var table = $("#keuanganTable").DataTable({
                "dom": 'rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            $('#keuanganSearch').on('input', function() {
                table.search(this.value).draw();
            });

            // SweetAlert Delete Confirmation with Event Delegation for DataTables
            $(document).on('click', '.delete-keuangan-btn', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).data('delete-route');

                // Debugging (optional, removed in prod)
                // console.log("Delete URL:", deleteUrl);

                if (!deleteUrl) {
                    Swal.fire('Error', 'URL Hapus tidak ditemukan', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data transaksi ini akan dihapus secara permanen dan saldo akan dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#deleteForm').attr('action', deleteUrl);
                        $('#deleteForm').submit();
                    }
                });
            });


        });
    </script>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>


</body>

</html>
