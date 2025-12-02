<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengeluaran Barang</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        @media print {
            .no-print { display: none; }
        }
        .card-header h3 { margin-bottom: 0; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('include.navbarSistem')
    @include('include.sidebar')

    <div class="content-wrapper">

        <!-- Header -->
        <div class="content-header mb-3">
            <div class="container-fluid">
                <h1 class="m-0">Detail Pengeluaran Barang</h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <!-- Informasi Pengeluaran -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Informasi Pengeluaran
                        </h3>

                        <div class="card-tools no-print">
                            <a href="{{ route('pengeluaran-barang.pdf', $pengeluaran->id) }}"
                            class="btn btn-danger btn-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Cetak PDF
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <p><strong>Nomor Pengeluaran:</strong> {{ $pengeluaran->no_pengeluaran }}</p>
                                <p><strong>Pegawai:</strong> {{ $pengeluaran->pegawai->nama_pegawai ?? '-' }}</p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>Tanggal Pengeluaran:</strong>
                                    {{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d-m-Y') }}
                                </p>

                                <p><strong>Keterangan:</strong>
                                    @if($pengeluaran->keterangan)
                                        {{ $pengeluaran->keterangan }}
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Daftar Produk Keluar -->
                <div class="card mt-3">
                    <div class="card-header bg-dark">
                        <h3 class="card-title">
                            <i class="fas fa-box-open"></i> Daftar Produk Dikeluarkan
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-bordered table-hover m-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Gudang</th>
                                    <th>Area</th>
                                    <th>Rak</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $total = 0; @endphp

                                @forelse($pengeluaran->details as $d)
                                    @php $total += $d->subtotal; @endphp

                                    <tr>
                                        <td>{{ $d->produk->name ?? '-' }}</td>
                                        <td>{{ $d->gudang->nama_gudang ?? '-' }}</td>
                                        <td>{{ $d->area->nama_area ?? '-' }}</td>
                                        <td>{{ $d->rak->kode_rak ?? '-' }}</td>
                                        <td class="text-right">{{ $d->qty }}</td>
                                        <td class="text-right">Rp {{ number_format($d->harga,0,',','.') }}</td>
                                        <td class="text-right">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">
                                            Belum ada produk dikeluarkan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if($pengeluaran->details->count() > 0)
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="6" class="text-right">Total</th>
                                    <th class="text-right">Rp {{ number_format($total,0,',','.') }}</th>
                                </tr>
                            </tfoot>
                            @endif

                        </table>
                    </div>
                </div>

                <!-- Tombol Kembali -->
                <div class="mt-3 no-print">
                    <a href="{{ route('pengeluaran-barang.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

            </div>
        </section>

    </div>

    @include('include.footerSistem')
</div>

@include('services.logoutModal')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
