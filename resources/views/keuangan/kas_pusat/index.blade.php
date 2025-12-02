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
        .saldo-box {
            font-size: 26px;
            font-weight: bold;
            color: #28a745;
        }

        .card-info {
            font-size: 15px;
            line-height: 1.6;
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
                        <h1 class="m-0">Kas Pusat Perusahaan</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <section class="content">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Informasi Kas Pusat</h3>

                        <a href="{{ route('kas-pusat.edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Saldo Awal
                        </a>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Saldo Awal -->
                            <div class="col-md-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h4>Saldo Awal</h4>
                                        <div class="saldo-box">
                                            Rp {{ number_format($kas->saldo_awal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Saldo Saat Ini -->
                            <div class="col-md-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h4>Saldo Saat Ini</h4>
                                        <div class="saldo-box">
                                            Rp {{ number_format($kas->saldo_saat_ini, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="card-info">
                            <p><strong>Diperbarui:</strong> {{ $kas->updated_at->format('d/m/Y H:i') }}</p>
                            <p>Kas Pusat digunakan untuk mencatat saldo utama perusahaan, termasuk semua pemasukan dan pengeluaran yang berasal dari transaksi barang maupun operasional.</p>
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
            $('#toastNotification').toast({
                delay: 3000,
                autohide: true
            }).toast('show');
        @endif
    });
</script>

</body>
</html>
