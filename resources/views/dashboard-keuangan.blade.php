<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Keuangan</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">

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

        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard Keuangan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard-keuangan') }}">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard Keuangan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- Total Saldo -->
                    <div class="col-lg-4 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h3>
                                <p>Total Saldo Saat Ini</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <a href="{{ url('kas-pusat') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Total Pemasukkan -->
                    <div class="col-lg-4 col-12">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>Rp {{ number_format($totalPemasukkan, 0, ',', '.') }}</h3>
                                <p>Total Pemasukkan</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <a href="{{ url('keuangan?pemasukkan') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Total Pengeluaran -->
                    <div class="col-lg-4 col-12">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                                <p>Total Pengeluaran</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <a href="{{ url('keuangan?pengeluaran') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title">Grafik Keuangan 12 Bulan Terakhir</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="keuanganChart" style="height: 300px;"></canvas>
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
<script src="{{ asset('resources/js/ToastScript.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function () {
        $('[data-widget="treeview"]').each(function () {
            AdminLTE.Treeview._jQueryInterface.call($(this));
        });
    });

    const bulan = @json($dataBulanan->pluck('bulan'));
    const pemasukkan = @json($dataBulanan->pluck('total_pemasukkan'));
    const pengeluaran = @json($dataBulanan->pluck('total_pengeluaran'));

    const ctx = document.getElementById('keuanganChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: bulan,
            datasets: [
                {
                    label: 'Pemasukkan',
                    data: pemasukkan,
                    borderWidth: 3,
                    tension: 0.4,
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaran,
                    borderWidth: 3,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
