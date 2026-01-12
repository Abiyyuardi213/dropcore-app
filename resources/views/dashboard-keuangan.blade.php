<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Dashboard Keuangan</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        .small-box .icon>i {
            top: 10px;
            right: 10px;
            font-size: 60px;
            opacity: 0.3;
        }

        .card-header {
            background-color: #f4f6f9;
            border-bottom: 2px solid #007bff;
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
                            <h1 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-line mr-2"></i>Executive
                                Financial Dashboard</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <!-- Liquidity Status -->
                    <h5 class="mb-3 text-secondary border-bottom pb-2">Posisi Likuiditas</h5>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info shadow-sm">
                                <div class="inner">
                                    <h3>Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h3>
                                    <p>Total Likuiditas</p>
                                </div>
                                <div class="icon"><i class="fas fa-wallet"></i></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary shadow-sm">
                                <div class="inner">
                                    <h3>Rp {{ number_format($saldoBank, 0, ',', '.') }}</h3>
                                    <p>Saldo Bank</p>
                                </div>
                                <div class="icon"><i class="fas fa-university"></i></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success shadow-sm">
                                <div class="inner">
                                    <h3>Rp {{ number_format($saldoTunai, 0, ',', '.') }}</h3>
                                    <p>Kas Tunai</p>
                                </div>
                                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning shadow-sm">
                                <div class="inner">
                                    <h3>Rp {{ number_format($saldoEwallet, 0, ',', '.') }}</h3>
                                    <p>E-Wallet</p>
                                </div>
                                <div class="icon"><i class="fas fa-mobile-alt"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts & Summaries -->
                    <div class="row mt-2">
                        <div class="col-md-8">
                            <div class="card shadow">
                                <div class="card-header border-0">
                                    <h3 class="card-title font-weight-bold">Arus Kas Tahun {{ date('Y') }}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="keuanganChart" height="300" style="height: 300px;"></canvas>
                                </div>
                            </div>

                            <!-- Transaction Summary -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-widget widget-user-2 shadow-sm">
                                        <div class="widget-user-header bg-success">
                                            <div class="widget-user-image"><i
                                                    class="fas fa-arrow-down fa-3x text-white-50"></i></div>
                                            <h3 class="widget-user-username">Total Pemasukkan</h3>
                                            <h5 class="widget-user-desc">Akumulasi</h5>
                                        </div>
                                        <div class="card-footer p-0">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link text-dark font-weight-bold">
                                                        Rp {{ number_format($totalPemasukkan, 0, ',', '.') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-widget widget-user-2 shadow-sm">
                                        <div class="widget-user-header bg-danger">
                                            <div class="widget-user-image"><i
                                                    class="fas fa-arrow-up fa-3x text-white-50"></i></div>
                                            <h3 class="widget-user-username">Total Pengeluaran</h3>
                                            <h5 class="widget-user-desc">Akumulasi</h5>
                                        </div>
                                        <div class="card-footer p-0">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link text-dark font-weight-bold">
                                                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow">
                                <div class="card-header border-0 bg-white border-bottom">
                                    <h3 class="card-title font-weight-bold text-dark">Top 5 Pengeluaran</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="expensePieChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <div class="card-footer bg-white p-0">
                                    <ul class="nav nav-pills flex-column">
                                        @foreach ($expenseByCategory as $cat)
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                    {{ $cat->kategori }}
                                                    <span class="float-right text-danger font-weight-bold">
                                                        Rp {{ number_format($cat->total, 0, ',', '.') }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
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
        // 1. Line Chart (Income vs Expense)
        const ctx = document.getElementById('keuanganChart').getContext('2d');
        const chartData = {
            labels: @json($dataBulanan->pluck('bulan')),
            datasets: [{
                    label: 'Pemasukkan',
                    data: @json($dataBulanan->pluck('total_pemasukkan')),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Pengeluaran',
                    data: @json($dataBulanan->pluck('total_pengeluaran')),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        };
        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    }, // Modern grid
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // 2. Pie Chart (Expense Breakdown)
        const ctxPie = document.getElementById('expensePieChart').getContext('2d');
        const pieData = {
            labels: @json($expenseByCategory->pluck('kategori')),
            datasets: [{
                data: @json($expenseByCategory->pluck('total')),
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }]
        };
        new Chart(ctxPie, {
            type: 'doughnut',
            data: pieData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

</body>

</html>
