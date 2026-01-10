<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice Mutasi | DropCore</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Main content -->
        <section class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h4>
                        <i class="fas fa-cubes"></i> DropCore Logistics.
                        <small class="float-right">Tanggal:
                            {{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d/m/Y') }}</small>
                    </h4>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    Dari (Asal)
                    <address>
                        @if (in_array($mutasi->jenis_mutasi, ['keluar', 'pindah']))
                            <strong>{{ $mutasi->gudangAsal->nama_gudang ?? 'Gudang Utama' }}</strong><br>
                            {{ $mutasi->gudangAsal->lokasi ?? '-' }}<br>
                            Area: {{ $mutasi->areaAsal->kode_area ?? '-' }}, Rak:
                            {{ $mutasi->rakAsal->kode_rak ?? '-' }}<br>
                        @else
                            <strong>Pihak Luar (Supplier)</strong><br>
                            Inbound<br>
                        @endif
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    Ke (Tujuan)
                    <address>
                        @if (in_array($mutasi->jenis_mutasi, ['masuk', 'pindah']))
                            <strong>{{ $mutasi->gudangTujuan->nama_gudang ?? 'Gudang Tujuan' }}</strong><br>
                            {{ $mutasi->gudangTujuan->lokasi ?? '-' }}<br>
                            Area: {{ $mutasi->areaTujuan->kode_area ?? '-' }}, Rak:
                            {{ $mutasi->rakTujuan->kode_rak ?? '-' }}<br>
                        @else
                            <strong>Pihak Luar (Konsumen/Usage)</strong><br>
                            Outbound<br>
                        @endif
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>Invoice #{{ str_pad($mutasi->id, 6, '0', STR_PAD_LEFT) }}</b><br>
                    <br>
                    <b>Referensi:</b> {{ $mutasi->referensi ?? '-' }}<br>
                    <b>Jenis Mutasi:</b> {{ strtoupper($mutasi->jenis_mutasi) }}<br>
                    <b>User:</b> {{ $mutasi->user->name ?? 'System' }}
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
                                <th>Qty</th>
                                <th>Produk</th>
                                <th>Serial #</th>
                                <th>Kondisi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $mutasi->quantity }}</td>
                                <td>{{ $mutasi->produk->name }}</td>
                                <td>{{ $mutasi->produk->code ?? '-' }}</td>
                                <td>{{ $mutasi->kondisi->nama_kondisi ?? 'Standard' }}</td>
                                <td>{{ $mutasi->keterangan ?? 'Tidak ada keterangan tambahan' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Signatures -->
            <div class="row mt-5">
                <div class="col-4 text-center">
                    <p>Admin Gudang</p>
                    <br><br><br>
                    <p>(______________________)</p>
                </div>
                <div class="col-4 text-center">
                    <p>Pengirim / Pembawa</p>
                    <br><br><br>
                    <p>(______________________)</p>
                </div>
                <div class="col-4 text-center">
                    <p>Penerima / Mengetahui</p>
                    <br><br><br>
                    <p>(______________________)</p>
                </div>
            </div>

            <!-- this row will not appear when printing -->
            <div class="row no-print mt-4">
                <div class="col-12">
                    <button onclick="window.print()" class="btn btn-default"><i class="fas fa-print"></i> Cetak
                        Invoice</button>
                    <a href="{{ route('mutasi-stok.show', $mutasi->id) }}" class="btn btn-secondary float-right">
                        Kembali
                    </a>
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
