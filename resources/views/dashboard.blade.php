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
        .dashboard-summary-box { border-radius: 7px; overflow: hidden; }
        .dashboard-summary-box .inner { min-height: 130px; padding: 15px; }
        .dashboard-summary-box .inner h3 { position: relative; z-index: 1; font-size: 2.2rem; overflow-wrap: anywhere; }
        .dashboard-summary-box .inner p { position: relative; z-index: 1; margin-bottom: 0; }
        .dashboard-summary-box .icon > i { font-size: 60px; top: 24px; right: 15px; }
        .dashboard-summary-box .small-box-footer { padding: 8px 0; }
        .sales-trend-card { border: 1px solid #d3d6da; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.12); }
        .sales-trend-card .card-header { padding: 1.25rem 1.25rem .75rem; }
        .sales-trend-card .card-title { font-size: 1.2rem; font-weight: 400; margin-right: 1rem; }
        .sales-trend-card .card-body { padding: 1.25rem; }
        .sales-report-link { white-space: nowrap; border-bottom: 1px solid #cce0ff; }
        .sales-summary { gap: 1rem; font-size: 1.05rem; }
        .sales-chart-area { height: 320px; position: relative; }
        @media (max-width: 575.98px) { .sales-chart-area { height: 280px; } }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
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
                    <!-- Rekap Dashboard -->
                    <div class="row">
                        @foreach ([
                            ['Pengguna', $totalPengguna, 'bg-primary', 'fas fa-user-friends', 'user.index', 'Akun pengguna terdaftar'],
                            ['Transaksi Keluar', $totalPengeluaran, 'bg-warning', 'fas fa-truck-loading', 'pengeluaran-barang.index', 'Transaksi pengeluaran selesai'],
                            ['Pesanan Distributor', $totalPesananDistributor, 'bg-danger', 'fas fa-shopping-cart', 'admin.orders.index', 'Seluruh pesanan distributor'],
                            ['Total Stok Tersisa', $totalStok, 'bg-navy', 'fas fa-cubes', 'stok.index', 'Jumlah unit tersisa di seluruh lokasi'],
                        ] as [$label, $value, $color, $icon, $destination, $description])
                            <div class="col-12 col-sm-6 col-xl">
                                <div class="small-box {{ $color }} dashboard-summary-box shadow-sm">
                                    <div class="inner">
                                        <h3>{{ number_format($value ?? 0, 0, ',', '.') }}</h3>
                                        <p>{{ $label }}</p>
                                    </div>
                                    <div class="icon"><i class="{{ $icon }}" aria-hidden="true"></i></div>
                                    <a href="{{ route($destination) }}" class="small-box-footer" title="{{ $description }}" aria-label="Lihat detail {{ $label }}">
                                        Lihat Detail <i class="fas fa-arrow-circle-right ml-1" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Grafik penjualan bergaya AdminLTE -->
                    @php
                        $previousMonthSales = (float) ($salesChartData[4] ?? 0);
                        $currentMonthSales = (float) ($totalPenjualanBulanIni ?? 0);
                        $salesChange = $previousMonthSales > 0 ? (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100 : null;
                    @endphp
                    <div class="row">
                        <div class="col-12">
                            <div class="card sales-trend-card">
                                <div class="card-header border-0 bg-transparent">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <h3 class="card-title">Perkembangan Penjualan &amp; Transaksi Produk</h3>
                                        @if(auth()->user()->role?->hasPermission('transaksi'))
                                            <a href="{{ route('dashboard-transaksi') }}" class="sales-report-link">Lihat Rekap</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body pt-3">
                                    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap sales-summary">
                                        <div>
                                            <span class="d-block text-bold text-lg">Rp {{ number_format($currentMonthSales, 0, ',', '.') }}</span>
                                            <span class="d-block">Penjualan Bulan Ini</span>
                                        </div>
                                        <div class="text-right">
                                            @if($salesChange !== null)
                                                <span class="d-block {{ $salesChange >= 0 ? 'text-success' : 'text-danger' }}">
                                                    <i class="fas fa-arrow-{{ $salesChange >= 0 ? 'up' : 'down' }} mr-1" aria-hidden="true"></i>{{ number_format(abs($salesChange), 1, ',', '.') }}%
                                                </span>
                                            @else
                                                <span class="d-block text-muted">Belum ada pembanding</span>
                                            @endif
                                            <span class="d-block text-muted">Dibanding bulan lalu</span>
                                        </div>
                                    </div>
                                    <div class="sales-chart-area">
                                        <canvas id="salesTrendsChart" role="img" aria-label="Grafik nilai penjualan dan jumlah transaksi pengeluaran selesai selama enam bulan terakhir"></canvas>
                                    </div>
                                    <p class="text-muted small mb-0 mt-2">Tren 6 bulan terakhir. Penjualan mencakup pesanan aktif dan pengeluaran barang selesai, tanpa menghitung pesanan dua kali.</p>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(function () {
            'use strict'

            var ctx = document.getElementById('salesTrendsChart').getContext('2d');

            var salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($salesChartLabels) !!},
                    datasets: [
                        {
                            label: 'Nominal Penjualan (Rp)',
                            backgroundColor: '#007bff',
                            borderRadius: { topLeft: 5, topRight: 5 },
                            maxBarThickness: 22,
                            categoryPercentage: 0.6,
                            barPercentage: 0.8,
                            borderColor: '#007bff',
                            data: {!! json_encode($salesChartData) !!},
                            yAxisID: 'y'
                        },
                        {
                            label: 'Jumlah Transaksi Pengeluaran',
                            type: 'bar',
                            backgroundColor: '#20c997',
                            borderColor: '#20c997',
                            maxBarThickness: 22,
                            categoryPercentage: 0.6,
                            barPercentage: 0.8,
                            borderRadius: { topLeft: 5, topRight: 5 },
                            data: {!! json_encode($pengeluaranChartData) !!},
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            align: 'end',
                            labels: { boxWidth: 16, boxHeight: 16, padding: 20, color: '#343a40' }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 0) {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    } else {
                                        label += context.parsed.y + ' Transaksi';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e9ecef', drawBorder: false },
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Nominal (Rp)'
                            },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + ' Jt';
                                    }
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                            title: {
                                display: true,
                                text: 'Jumlah Transaksi'
                            },
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>

</body>

</html>
