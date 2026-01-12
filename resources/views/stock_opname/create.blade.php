<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mulai Stock Opname | DropCore</title>
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
                            <h1 class="m-0">Mulai Stock Opname Baru</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('stock-opname.index') }}">Stock Opname</a>
                                </li>
                                <li class="breadcrumb-item active">Buat Baru</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Setup Opname</h3>
                                </div>
                                <form action="{{ route('stock-opname.store') }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <div class="alert alert-warning">
                                            <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                                            Memulai Stock Opname akan mengambil <b>Snapshot</b> stok sistem pada gudang
                                            yang dipilih saat ini. Pastikan tidak ada transaksi barang masuk/keluar yang
                                            sedang berlangsung untuk akurasi data.
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Pelaksanaan</label>
                                            <input type="date" name="tanggal" class="form-control"
                                                value="{{ date('Y-m-d') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Pilih Gudang Target</label>
                                            <select name="gudang_id" class="form-control" required>
                                                <option value="">-- Pilih Gudang --</option>
                                                @foreach ($gudangs as $g)
                                                    <option value="{{ $g->id }}">{{ $g->nama_gudang }}
                                                        ({{ $g->kode_gudang }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Keterangan (Opsional)</label>
                                            <textarea name="keterangan" class="form-control" placeholder="Contoh: Opname Triwulan 1"></textarea>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-info">Mulai Proses Opname</button>
                                        <a href="{{ route('stock-opname.index') }}" class="btn btn-default">Batal</a>
                                    </div>
                                </form>
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
