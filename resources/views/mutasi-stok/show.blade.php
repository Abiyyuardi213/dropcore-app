<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mutasi Stok</title>
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
                        <h1 class="m-0">Detail Mutasi Stok</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Mutasi</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">Produk</th>
                                <td>{{ $mutasi->produk->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah (Qty)</th>
                                <td>{{ $mutasi->quantity }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Mutasi</th>
                                <td>{{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $mutasi->keterangan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th colspan="2" class="bg-light">Lokasi Asal</th>
                            </tr>
                            <tr>
                                <th>Gudang Asal</th>
                                <td>{{ $mutasi->gudangAsal->nama_gudang ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Area Asal</th>
                                <td>{{ $mutasi->areaAsal->kode_area ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Rak Asal</th>
                                <td>{{ $mutasi->rakAsal->kode_rak ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th colspan="2" class="bg-light">Lokasi Tujuan</th>
                            </tr>
                            <tr>
                                <th>Gudang Tujuan</th>
                                <td>{{ $mutasi->gudangTujuan->nama_gudang ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Area Tujuan</th>
                                <td>{{ $mutasi->areaTujuan->kode_area ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Rak Tujuan</th>
                                <td>{{ $mutasi->rakTujuan->kode_rak ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('mutasi-stok.index') }}" class="btn btn-secondary">
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
