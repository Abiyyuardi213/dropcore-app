<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Aktivitas Produk</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
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
                        <h1 class="m-0">Detail Riwayat Aktivitas</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Riwayat Aktivitas</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width:30%">Waktu</th>
                                <td>{{ \Carbon\Carbon::parse($riwayat->created_at)->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Produk</th>
                                <td>{{ $riwayat->produk->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tipe Aktivitas</th>
                                <td>{{ ucfirst($riwayat->tipe_aktivitas) }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $riwayat->user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi Aktivitas</th>
                                <td>{{ $riwayat->deskripsi ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('riwayat-aktivitas-produk.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('include.footerSistem')
</div>

@include('services.LogoutModal')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
