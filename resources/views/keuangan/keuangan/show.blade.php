<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Detail Transaksi</title>

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
                            <h1 class="m-0">Detail Transaksi</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h3 class="card-title">
                                Transaksi: <strong>{{ $data->no_transaksi }}</strong>
                            </h3>
                            <div class="card-tools">
                                <span
                                    class="badge {{ $data->status == 'approved' ? 'badge-success' : 'badge-secondary' }}">
                                    {{ ucfirst($data->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- LEFT COLUMN: Transaction Details -->
                                <div class="col-md-7">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi
                                                Transaksi
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered table-sm">
                                                <tr>
                                                    <th style="width: 35%">Jenis Transaksi</th>
                                                    <td>
                                                        @if ($data->jenis_transaksi == 'pemasukkan')
                                                            <span class="text-success font-weight-bold"><i
                                                                    class="fas fa-arrow-down"></i> Pemasukkan</span>
                                                        @else
                                                            <span class="text-danger font-weight-bold"><i
                                                                    class="fas fa-arrow-up"></i> Pengeluaran</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Jumlah</th>
                                                    <td class="font-weight-bold text-lg">Rp
                                                        {{ number_format($data->jumlah, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <td>{{ \Carbon\Carbon::parse($data->tanggal_transaksi)->format('d F Y') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kategori</th>
                                                    <td>{{ $data->kategori ? $data->kategori->nama : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Akun Keuangan</th>
                                                    <td>{{ $data->sumber ? $data->sumber->nama_sumber : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Dibuat Oleh</th>
                                                    <td>{{ $data->user ? $data->user->name : 'Sistem' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Waktu Input</th>
                                                    <td>{{ $data->created_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Keterangan</th>
                                                    <td>
                                                        <div class="text-muted">
                                                            {{ $data->keterangan ?: 'Tidak ada keterangan' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT COLUMN: Proof File -->
                                <div class="col-md-5">
                                    <div class="card card-secondary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-file-invoice"></i> Bukti Transaksi
                                            </h3>
                                        </div>
                                        <div class="card-body text-center">
                                            @if ($data->bukti_transaksi)
                                                @php
                                                    $extension = pathinfo($data->bukti_transaksi, PATHINFO_EXTENSION);
                                                @endphp

                                                @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <a href="{{ asset('uploads/keuangan/' . $data->bukti_transaksi) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('uploads/keuangan/' . $data->bukti_transaksi) }}"
                                                            alt="Bukti Transaksi" class="img-fluid img-thumbnail"
                                                            style="max-height: 400px; width: auto;">
                                                    </a>
                                                @elseif(strtolower($extension) == 'pdf')
                                                    <iframe
                                                        src="{{ asset('uploads/keuangan/' . $data->bukti_transaksi) }}"
                                                        style="width: 100%; height: 400px;" frameborder="0"></iframe>
                                                @else
                                                    <div class="alert alert-info">
                                                        File bukti tersedia namun format tidak didukung untuk pratinjau.
                                                    </div>
                                                @endif
                                            @else
                                                <div class="py-5 text-muted">
                                                    <i class="fas fa-file-excel mb-3"
                                                        style="font-size: 4rem; color: #dee2e6;"></i>
                                                    <p>Tidak ada bukti transaksi yang diunggah.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('keuangan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <div>
                                <a href="{{ route('keuangan.print', $data->id) }}" target="_blank"
                                    class="btn btn-danger mr-1">
                                    <i class="fas fa-print"></i> Print PDF
                                </a>
                                <a href="{{ route('keuangan.edit', $data->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>
