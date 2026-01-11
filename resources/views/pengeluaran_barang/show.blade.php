<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengeluaran Barang - DropCore</title>
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
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Detail Pengeluaran Barang</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('pengeluaran-barang.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Detail Pengeluaran</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="invoice p-3 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> PT Garuda Fiber
                                    <small class="float-right">Date: {{ date('d/m/Y') }}</small>
                                </h4>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Pengirim (Gudang)
                                <address>
                                    <strong>PT Garuda Fiber Logistics</strong><br>
                                    Jl. Raya Gudang No. 1<br>
                                    Depok, Jawa Barat<br>
                                    Operator: {{ $pengeluaran->user->name ?? 'System' }}
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                Penerima ({{ ucfirst($pengeluaran->tipe_penerima) }})
                                <address>
                                    @if ($pengeluaran->tipe_penerima == 'distributor')
                                        <strong>{{ $pengeluaran->distributor->nama_distributor ?? '-' }}</strong><br>
                                        {{ $pengeluaran->distributor->alamat ?? '-' }}<br>
                                        Phone: {{ $pengeluaran->distributor->nomor_telepon ?? '-' }}
                                    @else
                                        <strong>{{ $pengeluaran->nama_konsumen ?? '-' }}</strong><br>
                                        {{ $pengeluaran->alamat_konsumen ?? '-' }}<br>
                                        Phone: {{ $pengeluaran->telepon_konsumen ?? '-' }}
                                    @endif
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <b>No. Surat Jalan #{{ $pengeluaran->no_pengeluaran }}</b><br>
                                <br>
                                <b>Referensi:</b> {{ $pengeluaran->referensi ?? '-' }}<br>
                                <b>Tgl. Kirim:</b>
                                {{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d M Y') }}<br>
                                <b>Status:</b> {{ ucfirst($pengeluaran->status ?? 'Completed') }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Kode</th>
                                            <th>Sumber (Gudang > Area > Rak)</th>
                                            <th>Kondisi</th>
                                            <th>Qty</th>
                                            <th>Harga Jual</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; @endphp
                                        @foreach ($pengeluaran->details as $d)
                                            @php $total += $d->subtotal; @endphp
                                            <tr>
                                                <td>{{ $d->produk->name ?? 'Item Terhapus' }}</td>
                                                <td>{{ $d->produk->code ?? '-' }}</td>
                                                <td>
                                                    @if ($d->gudang)
                                                        {{ $d->gudang->nama_gudang }}
                                                    @endif
                                                    @if ($d->area)
                                                        > {{ $d->area->nama_area }}
                                                    @endif
                                                    @if ($d->rak)
                                                        > {{ $d->rak->kode_rak }}
                                                    @endif
                                                </td>
                                                <td><span
                                                        class="badge badge-info">{{ $d->kondisi->nama_kondisi ?? '-' }}</span>
                                                </td>
                                                <td>{{ $d->qty }}</td>
                                                <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <p class="lead">Keterangan:</p>
                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                    {{ $pengeluaran->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="lead">Ringkasan</p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Total Barang:</th>
                                            <td>{{ $pengeluaran->details->sum('qty') }} Item</td>
                                        </tr>
                                        <tr>
                                            <th>Total Nilai:</th>
                                            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row no-print">
                            <div class="col-12">
                                <a href="{{ route('pengeluaran-barang.print', $pengeluaran->id) }}" target="_blank"
                                    class="btn btn-default"><i class="fas fa-print"></i> Cetak Surat Jalan</a>

                                <a href="{{ route('pengeluaran-barang.index') }}" class="btn btn-secondary float-right"
                                    style="margin-right: 5px;">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('include.footerSistem')
    </div>
    @include('services.logoutModal')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>
