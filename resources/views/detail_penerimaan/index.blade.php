<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penerimaan Barang</title>
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

        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0">Tambah Detail Penerimaan Barang</h1>
                <p class="text-muted">No. Penerimaan: <strong>{{ $penerimaan->no_penerimaan }}</strong></p>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Tambah Produk -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cubes"></i> Tambah Produk
                        </h3>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('detail-penerimaan.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="penerimaan_id" value="{{ $penerimaan->id }}">

                            <!-- Produk -->
                            <div class="form-group">
                                <label>Produk</label>
                                <select name="produk_id" class="form-control" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($produk as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Gudang</label>
                                <select name="gudang_id" class="form-control" required>
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach($gudang as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Area</label>
                                <select name="area_id" class="form-control" required>
                                    <option value="">-- Pilih Area --</option>
                                    @foreach($area as $a)
                                        <option value="{{ $a->id }}">{{ $a->nama_area }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Rak</label>
                                <select name="rak_id" class="form-control" required>
                                    <option value="">-- Pilih Rak --</option>
                                    @foreach($rak as $r)
                                        <option value="{{ $r->id }}">{{ $r->kode_rak }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Qty -->
                            <div class="form-group">
                                <label>Qty Diterima</label>
                                <input type="number" name="qty" class="form-control" min="1" required>
                            </div>

                            <!-- Harga -->
                            <div class="form-group">
                                <label>Harga per Unit</label>
                                <input type="number" name="harga" class="form-control" min="0" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Tambahkan
                            </button>

                            <a href="{{ route('penerimaan-barang.index') }}" class="btn btn-secondary">
                                Selesai
                            </a>
                        </form>
                    </div>
                </div>

                <!-- List Produk -->
                <div class="card mt-4">
                    <div class="card-header bg-dark">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i> Daftar Barang Diterima
                        </h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $d)
                                    <tr>
                                        <td>{{ $d->produk->name }}</td>
                                        <td>{{ $d->qty }}</td>
                                        <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('detail-penerimaan.destroy', $d->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus produk ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($details->count() === 0)
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Belum ada produk ditambahkan
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>

    </div>

    @include('include.footerSistem')
</div>

@include('services.LogoutModal')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
