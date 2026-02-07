<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Garuda Fiber</title>
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
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Info Boxes -->
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Peran</span>
                                    <span class="info-box-number">{{ $totalPeran ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i
                                        class="fas fa-user-friends"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pengguna</span>
                                    <span class="info-box-number">{{ $totalPengguna ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Produk</span>
                                    <span class="info-box-number">{{ $totalProduk ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cubes"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Stok</span>
                                    <span class="info-box-number">{{ number_format($totalStok ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Row -->
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <!-- Mutasi Stok Terakhir -->
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <h3 class="card-title">Mutasi Stok Terakhir</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>ID Mutasi</th>
                                                    <th>Produk</th>
                                                    <th>Jenis</th>
                                                    <th>Qty</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recentMutations as $mutasi)
                                                    <tr>
                                                        <td><a
                                                                href="{{ route('mutasi-stok.show', $mutasi->id) }}">{{ \Illuminate\Support\Str::limit($mutasi->id, 8, '') }}</a>
                                                        </td>
                                                        <td>{{ $mutasi->produk->name ?? '-' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $mutasi->jenis_mutasi == 'masuk' ? 'success' : ($mutasi->jenis_mutasi == 'keluar' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($mutasi->jenis_mutasi) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $mutasi->quantity }}</td>
                                                        <td>
                                                            @if ($mutasi->jenis_mutasi == 'pindah')
                                                                <small>{{ $mutasi->gudangAsal->nama_gudang ?? '-' }} <i
                                                                        class="fas fa-arrow-right"></i>
                                                                    {{ $mutasi->gudangTujuan->nama_gudang ?? '-' }}</small>
                                                            @else
                                                                <small>{{ $mutasi->keterangan }}</small>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer clearfix">
                                    <a href="{{ route('mutasi-stok.create') }}"
                                        class="btn btn-sm btn-info float-left">Buat Mutasi Baru</a>
                                    <a href="{{ route('mutasi-stok.index') }}"
                                        class="btn btn-sm btn-secondary float-right">Lihat Semua Mutasi</a>
                                </div>
                            </div>

                            <!-- Pesanan Terakhir (Optional if Order module active) -->
                            @if (isset($recentOrders) && count($recentOrders) > 0)
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Pesanan Terakhir</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table m-0">
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Status</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($recentOrders as $order)
                                                        <tr>
                                                            <td><a href="#">{{ $order->id }}</a></td>
                                                            <td><span
                                                                    class="badge badge-success">{{ $order->status ?? 'Processed' }}</span>
                                                            </td>
                                                            <td>Rp
                                                                {{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <!-- Low Stock Alert -->
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Peringatan Stok Rendah</h3>
                                    <div class="card-tools">
                                        <span class="badge badge-light">{{ count($lowStockProducts) }} Items</span>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                        @forelse($lowStockProducts as $prod)
                                            <li class="item">
                                                <div class="product-info ml-2">
                                                    <a href="javascript:void(0)"
                                                        class="product-title">{{ $prod->name }}
                                                        <span
                                                            class="badge badge-warning float-right">{{ $prod->total_stock }}</span></a>
                                                    <span class="product-description">
                                                        Min Stock: {{ $prod->min_stock }}
                                                    </span>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="item text-center p-3">Tidak ada produk stok rendah.</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('product.index') }}" class="uppercase">Lihat Semua Produk</a>
                                </div>
                            </div>

                            <!-- Recent Activities -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Aktivitas Terbaru</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="d-flex flex-column list-unstyled p-2">
                                        @foreach ($recentLogs as $log)
                                            <li class="border-bottom py-2">
                                                <i class="fas fa-history text-muted mr-2"></i>
                                                <span
                                                    class="text-sm font-weight-bold">{{ $log->user->name ?? 'System' }}</span>
                                                <span class="text-sm">{{ $log->description }}</span>
                                                <br>
                                                <small class="text-muted ml-4"><i class="far fa-clock"></i>
                                                    {{ $log->created_at->diffForHumans() }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('riwayat-log.index') }}">Lihat Semua Log</a>
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

</body>

</html>
