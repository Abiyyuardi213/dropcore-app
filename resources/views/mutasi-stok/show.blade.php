<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mutasi Stok</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
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
                            <div class="row">
                                <div class="col-md-12 mb-3 text-center">
                                    @if ($mutasi->jenis_mutasi == 'masuk')
                                        <span class="badge badge-success px-3 py-2" style="font-size: 1.1em">INBOUND
                                            (Masuk)</span>
                                    @elseif($mutasi->jenis_mutasi == 'keluar')
                                        <span class="badge badge-danger px-3 py-2" style="font-size: 1.1em">OUTBOUND
                                            (Keluar)</span>
                                    @else
                                        <span class="badge badge-info px-3 py-2" style="font-size: 1.1em">TRANSFER
                                            (Pindah)</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-outline card-secondary h-100">
                                        <div class="card-header">
                                            <h3 class="card-title">Detail Barang</h3>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td style="width: 35%"><strong>Produk</strong></td>
                                                    <td>: {{ $mutasi->produk->name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kondisi</strong></td>
                                                    <td>: <span
                                                            class="badge badge-secondary">{{ $mutasi->kondisi->nama_kondisi ?? 'Standar' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jumlah (Qty)</strong></td>
                                                    <td>: <strong>{{ $mutasi->quantity }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Referensi</strong></td>
                                                    <td>: {{ $mutasi->referensi ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal</strong></td>
                                                    <td>:
                                                        {{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d F Y') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Dicatat Oleh</strong></td>
                                                    <td>: {{ $mutasi->user->name ?? 'System' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Keterangan</strong></td>
                                                    <td>: {{ $mutasi->keterangan ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        {{-- Tampilkan Asal jika BUKAN Masuk --}}
                                        @if ($mutasi->jenis_mutasi != 'masuk')
                                            <div class="col-12 mb-3">
                                                <div class="card card-outline card-warning">
                                                    <div class="card-header">
                                                        <h3 class="card-title text-warning font-weight-bold">Asal
                                                            (Source)</h3>
                                                    </div>
                                                    <div class="card-body py-2">
                                                        <i class="fas fa-warehouse mr-2 text-muted"></i>
                                                        {{ $mutasi->gudangAsal->nama_gudang ?? '-' }} <br>
                                                        <i class="fas fa-th-large mr-2 text-muted"></i>
                                                        {{ $mutasi->areaAsal->kode_area ?? '-' }} <br>
                                                        <i class="fas fa-box mr-2 text-muted"></i>
                                                        {{ $mutasi->rakAsal->kode_rak ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Tampilkan Tujuan jika BUKAN Keluar --}}
                                        @if ($mutasi->jenis_mutasi != 'keluar')
                                            <div class="col-12">
                                                <div class="card card-outline card-success">
                                                    <div class="card-header">
                                                        <h3 class="card-title text-success font-weight-bold">Tujuan
                                                            (Destination)</h3>
                                                    </div>
                                                    <div class="card-body py-2">
                                                        <i class="fas fa-warehouse mr-2 text-muted"></i>
                                                        {{ $mutasi->gudangTujuan->nama_gudang ?? '-' }} <br>
                                                        <i class="fas fa-th-large mr-2 text-muted"></i>
                                                        {{ $mutasi->areaTujuan->kode_area ?? '-' }} <br>
                                                        <i class="fas fa-box mr-2 text-muted"></i>
                                                        {{ $mutasi->rakTujuan->kode_rak ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('mutasi-stok.print', $mutasi->id) }}" target="_blank"
                                class="btn btn-default mr-1"><i class="fas fa-print"></i> Cetak Invoice</a>
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
