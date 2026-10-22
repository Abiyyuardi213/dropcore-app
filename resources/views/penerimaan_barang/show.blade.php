<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penerimaan Barang - DropCore</title>
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
                            <h1>Detail Penerimaan Barang</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('penerimaan-barang.index') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Detail Penerimaan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                    <div class="invoice p-3 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <img src="{{ asset('image/garuda-fiber.png') }}" alt="Logo Garuda Fiber"
                                        style="width: 150px; max-width: 100%; height: auto;">
                                    <small class="float-right">Date: {{ date('d/m/Y') }}</small>
                                </h4>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                @php
                                    $pengirim = $penerimaan->supplier ?? $penerimaan->distributor;
                                    $isSupplier = $penerimaan->supplier !== null;
                                @endphp
                                Dari ({{ $isSupplier ? 'Supplier' : 'Distributor' }})
                                <address>
                                    <strong>{{ ($isSupplier ? $pengirim?->nama_supplier : $pengirim?->nama_distributor) ?: '-' }}</strong><br>
                                    {{ $pengirim?->alamat ?: '-' }}<br>
                                    Phone: {{ ($isSupplier ? $pengirim?->no_telepon : $pengirim?->telepon) ?: '-' }}<br>
                                    Email: {{ $pengirim?->email ?: '-' }}
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                Penerima (User)
                                <address>
                                    <strong>{{ $penerimaan->user->name ?? 'System' }}</strong><br>
                                    Email: {{ $penerimaan->user->email ?? 'admin@system.com' }}
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #{{ $penerimaan->no_penerimaan }}</b><br>
                                <br>
                                <b>Ref (SJ/PO):</b> {{ $penerimaan->referensi ?? '-' }}<br>
                                <b>Tgl. Terima:</b>
                                {{ \Carbon\Carbon::parse($penerimaan->tanggal_penerimaan)->format('d M Y') }}<br>
                                <b>Status:</b> {{ ucfirst($penerimaan->status ?? 'Completed') }}<br>
                                <b>Pembayaran:</b> {{ ['paid' => 'Lunas', 'unpaid' => 'Belum dibayar', 'reversed' => 'Dibalik / dibatalkan'][$penerimaan->payment_status] ?? 'Belum dibayar' }}<br>
                                <b>Sisa Utang:</b> Rp {{ number_format($penerimaan->status === 'completed' && $penerimaan->payment_status === 'unpaid' ? app(\App\Services\InventoryPaymentService::class)->total($penerimaan) : 0, 2, ',', '.') }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Kode Produk / SKU</th>
                                            <th>Kondisi</th>
                                            <th>Lokasi (Gudang > Area > Rak)</th>
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
                                                <td>{{ $d->produk->name ?? 'Item Terhapus' }}</td>
                                                <td>{{ $d->produk->sku ?? '-' }}</td>
                                                <td><span
                                                        class="badge badge-info">{{ $d->kondisi->nama_kondisi ?? '-' }}</span>
                                                </td>
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
                                                <td>{{ $d->qty }}</td>
                                                <td>Rp {{ number_format($d->harga, 2, ',', '.') }}</td>
                                                <td>Rp {{ number_format($d->subtotal, 2, ',', '.') }}</td>
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
                                    {{ $penerimaan->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="lead">Ringkasan</p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Total Barang:</th>
                                            <td>{{ $penerimaan->details->sum('qty') }} Item</td>
                                        </tr>
                                        <tr>
                                            <th>Subtotal:</th>
                                            <td>Rp
                                                {{ number_format($penerimaan->subtotal > 0 ? $penerimaan->subtotal : $total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>PPN
                                                ({{ $penerimaan->ppn_percentage > 0 ? $penerimaan->ppn_percentage + 0 : 10 }}%):
                                            </th>
                                            <td>Rp {{ number_format($penerimaan->ppn_amount, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Bayar:</th>
                                            <td>Rp
                                                {{ number_format($penerimaan->total_amount > 0 ? $penerimaan->total_amount : $total + $penerimaan->ppn_amount, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

        @if ($penerimaan->status === 'completed' && $penerimaan->payment_status === 'unpaid')
            <div class="card card-outline card-primary no-print mt-3">
                <div class="card-header"><h3 class="card-title">Pelunasan Utang Supplier</h3></div>
                <div class="card-body">
                    <p>Pelunasan mengubah saldo akun dan tidak mengubah stok. Pembayaran dicatat penuh sesuai total transaksi.</p>
                    <form action="{{ route('penerimaan-barang.pay', $penerimaan->id) }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-5 form-group"><label for="payAccount">Akun Pembayaran</label>
                                <select id="payAccount" name="sumber_id" class="form-control" required>
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach ($sumberKeuangan as $akun)<option value="{{ $akun->id }}">{{ $akun->nama_sumber }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group"><label for="payDate">Tanggal Pembayaran</label>
                                <input id="payDate" name="tanggal_pembayaran" type="date" class="form-control" value="{{ date('Y-m-d') }}" min="{{ \Carbon\Carbon::parse($penerimaan->tanggal_penerimaan)->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3 form-group"><button class="btn btn-primary" type="submit">Catat Pelunasan</button></div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="{{ route('penerimaan-barang.print', $penerimaan->id) }}" target="_blank"
                                    class="btn btn-default"><i class="fas fa-print"></i> Cetak Invoice</a>

                                <a href="{{ route('penerimaan-barang.index') }}" class="btn btn-secondary float-right"
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
    @include('services.LogoutModal')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>
