<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Surat Jalan - {{ $pengeluaran->no_pengeluaran }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h2 class="page-header">
                        <i class="fas fa-globe"></i> PT Garuda Fiber
                        <small class="float-right">Date: {{ date('d/m/Y') }}</small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    Pengirim
                    <address>
                        <strong>PT Garuda Fiber Logistics</strong><br>
                        Operator: {{ $pengeluaran->user->name ?? 'System' }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    Penerima
                    <address>
                        @if ($pengeluaran->tipe_penerima == 'distributor')
                            <strong>{{ $pengeluaran->distributor->nama_distributor ?? '-' }} (Retur)</strong><br>
                            {{ $pengeluaran->distributor->alamat ?? '-' }}<br>
                            Telp: {{ $pengeluaran->distributor->nomor_telepon ?? '-' }}
                        @else
                            <strong>{{ $pengeluaran->nama_konsumen ?? '-' }}</strong><br>
                            {{ $pengeluaran->alamat_konsumen ?? '-' }}<br>
                            Telp: {{ $pengeluaran->telepon_konsumen ?? '-' }}
                        @endif
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>Surat Jalan #{{ $pengeluaran->no_pengeluaran }}</b><br>
                    <br>
                    <b>Ref Order:</b> {{ $pengeluaran->referensi ?? '-' }}<br>
                    <b>Tgl Kirim:</b> {{ $pengeluaran->tanggal_pengeluaran }}<br>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kode</th>
                                <th>Kondisi</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($pengeluaran->details as $d)
                                @php $total += $d->subtotal; @endphp
                                <tr>
                                    <td>{{ $d->produk->name }}</td>
                                    <td>{{ $d->produk->code }}</td>
                                    <td>{{ $d->kondisi->nama_kondisi ?? '-' }}</td>
                                    <td>{{ $d->qty }}</td>
                                    <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-6">
                    <p class="lead">Keterangan:</p>
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        {{ $pengeluaran->keterangan ?? 'Barang diterima dalam kondisi baik.' }}
                    </p>
                </div>
                <div class="col-6">
                    <p class="lead">Ringkasan</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Total Barang:</th>
                                <td>{{ $pengeluaran->details->sum('qty') }}</td>
                            </tr>
                            <tr>
                                <th>Total Nilai:</th>
                                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-sm-4 text-center">
                    <p>Penerima Barang</p>
                    <br><br><br>
                    <p>( ................................. )</p>
                </div>
                <div class="col-sm-4 text-center">
                    <p>Pengirim / Logistik</p>
                    <br><br><br>
                    <p>( ................................. )</p>
                </div>
                <div class="col-sm-4 text-center">
                    <p>Mengetahui (Pimpinan)</p>
                    <br><br><br>
                    <p>( ................................. )</p>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
