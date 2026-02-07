<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Dashboard Gudang</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
        }

        .fc-daygrid-day-number {
            color: white !important;
        }

        .fc-daygrid-day {
            border: none !important;
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
                            <h1 class="m-0">Dashboard</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('dashboardGudang') }}">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard Gudang</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Info Boxes -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-dark">
                                <div class="inner">
                                    <h3>{{ $totalGudang ?? 0 }}</h3>
                                    <p>Total Gudang</p>
                                </div>
                                <div class="icon"><i class="fas fa-warehouse"></i></div>
                                <a href="{{ url('admin/gudang') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ $totalAreaGudang ?? 0 }}</h3>
                                    <p>Total Area</p>
                                </div>
                                <div class="icon"><i class="fas fa-th-large"></i></div>
                                <a href="{{ url('admin/areaGudang') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-light">
                                <div class="inner">
                                    <h3>{{ $totalRakGudang ?? 0 }}</h3>
                                    <p>Total Rak</p>
                                </div>
                                <div class="icon"><i class="fas fa-layer-group"></i></div>
                                <a href="{{ url('admin/rak-gudang') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ number_format($totalStokReal ?? 0) }}</h3>
                                    <p>Total Real Stock (Items)</p>
                                </div>
                                <div class="icon"><i class="fas fa-boxes"></i></div>
                                <a href="{{ url('admin/stok') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="row">
                        <!-- Left Col: Warehouse Capacities -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Distribusi Stok per Gudang</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Nama Gudang</th>
                                                <th>Lokasi</th>
                                                <th>Total Stok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stokPerGudang as $index => $g)
                                                <tr>
                                                    <td>{{ $index + 1 }}.</td>
                                                    <td>{{ $g->nama_gudang }}</td>
                                                    <td>{{ $g->lokasi }}</td>
                                                    <td><span
                                                            class="badge badge-primary">{{ number_format($g->total_items ?? 0) }}
                                                            Items</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Right Col: Recent Mutations -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <h3 class="card-title">Mutasi Stok Terakhir</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Jenis</th>
                                                    <th>Qty</th>
                                                    <th>Ket</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recentMutations as $mutasi)
                                                    <tr>
                                                        <td>{{ $mutasi->produk->name ?? '-' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $mutasi->jenis_mutasi == 'masuk' ? 'success' : ($mutasi->jenis_mutasi == 'keluar' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($mutasi->jenis_mutasi) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $mutasi->quantity }}</td>
                                                        <td><small>{{ $mutasi->jenis_mutasi == 'pindah' ? 'Transfer' : 'Log' }}</small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer clearfix">
                                    <a href="{{ route('mutasi-stok.index') }}"
                                        class="btn btn-sm btn-secondary float-right">Lihat Semua</a>
                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="{{ asset('resources/js/ToastScript.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('[data-widget="treeview"]').each(function() {
                AdminLTE.Treeview._jQueryInterface.call($(this));
            });
        });
    </script>
</body>

</html>
