<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Penerimaan Barang - {{ $penerimaan->no_penerimaan }}</title>
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
                    Dari (Distributor)
                    <address>
                        <strong>{{ $penerimaan->distributor->nama_distributor ?? ($penerimaan->supplier->nama_supplier ?? 'Unknown') }}</strong><br>
                        {{ $penerimaan->distributor->alamat ?? '-' }}<br>
                        Phone: {{ $penerimaan->distributor->nomor_telepon ?? '-' }}<br>
                        Email: {{ $penerimaan->distributor->email ?? '-' }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    Penerima
                    <address>
                        <strong>{{ $penerimaan->user->name ?? 'System' }}</strong><br>
                        {{ $penerimaan->user->email ?? '-' }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>Invoice #{{ $penerimaan->no_penerimaan }}</b><br>
                    <br>
                    <b>Order Ref:</b> {{ $penerimaan->referensi ?? '-' }}<br>
                    <b>Tgl Terima:</b> {{ $penerimaan->tanggal_penerimaan }}<br>
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
                                <th>Lokasi Simpan</th>
                                <th>Qty</th>
                                <th>Harga Est.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($penerimaan->details as $d)
                                @php $total += $d->subtotal; @endphp
                                <tr>
                                    <td>{{ $d->produk->name }}</td>
                                    <td>{{ $d->produk->code }}</td>
                                    <td>{{ $d->kondisi->nama_kondisi ?? '-' }}</td>
                                    <td>
                                        {{ $d->gudang->nama_gudang ?? '-' }}
                                        / {{ $d->area->nama_area ?? '-' }}
                                        / {{ $d->rak->kode_rak ?? '-' }}
                                    </td>
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
                <!-- accepted payments column -->
                <div class="col-6">
                    <p class="lead">Keterangan:</p>
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        {{ $penerimaan->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                    </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                    <p class="lead">Ringkasan</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Total Jumlah Barang:</th>
                                <td>{{ $penerimaan->details->sum('qty') }}</td>
                            </tr>
                            <tr>
                                <th>Total Estimasi Nilai:</th>
                                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row mt-5">
                <div class="col-sm-4 text-center">
                    <p>Admin Gudang</p>
                    <br><br><br>
                    <p>( ................................. )</p>
                </div>
                <div class="col-sm-4 text-center">
                    <p>Distributor / Pengirim</p>
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
