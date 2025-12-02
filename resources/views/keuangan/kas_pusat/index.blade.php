<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Kas Pusat</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        .content-header h1 {
            font-weight: 700;
            color: #343a40;
        }
        /* Styling untuk info-box kustom agar sedikit lebih besar */
        .info-box-number {
            font-size: 1.5rem; /* Membuat angka saldo lebih besar */
            font-weight: 700;
        }
        .card-info {
            font-size: 14px;
            line-height: 1.7;
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
                    <div class="col-sm-12">
                        <h1 class="m-0"><i class="fas fa-coins mr-2"></i>Kas Pusat Perusahaan</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="card card-secondary card-outline shadow">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-white font-weight-bold">
                            <i class="fas fa-info-circle mr-1"></i> Status Keuangan Utama
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 text-right">
                                <a href="{{ route('kas-pusat.edit') }}" class="btn btn-warning elevation-2">
                                    <i class="fas fa-edit mr-1"></i> Edit Saldo Awal
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box shadow-lg">
                                    <span class="info-box-icon bg-primary elevation-1">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Saldo Awal (Dana Dasar)</span>
                                        <span class="info-box-number text-primary">
                                            Rp {{ number_format($kas->saldo_awal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box shadow-lg">
                                    <span class="info-box-icon bg-success elevation-1">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Saldo Saat Ini (Aktual)</span>
                                        <span class="info-box-number text-success">
                                            Rp {{ number_format($kas->saldo_saat_ini, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="card-info text-muted">
                            <p class="mb-2">
                                <strong class="text-dark"><i class="far fa-clock mr-1"></i>Terakhir Diperbarui:</strong>
                                {{ $kas->updated_at->format('d F Y \j\a\m H:i') }}
                            </p>
                            <p>
                                Kas Pusat berfungsi sebagai **sumber dana utama** perusahaan yang mencatat setiap pemasukan dan pengeluaran
                                yang terjadi melalui sistem. Saldo ini bersifat **real-time** dan mencerminkan posisi likuiditas perusahaan.
                            </p>
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

<script>
    $(document).ready(function() {
        @if (session('success') || session('error'))
            // Pastikan Anda memiliki include.ToastModal yang benar
            $('#toastNotification').toast({
                delay: 3000,
                autohide: true
            }).toast('show');
        @endif
    });
</script>
</body>
</html>
