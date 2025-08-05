<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Supplier</title>
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
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Detail Supplier</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Lengkap Supplier</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Kiri -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Supplier</label>
                                        <p class="form-control-plaintext">{{ $supplier->nama_supplier }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <p class="form-control-plaintext">{{ $supplier->email ?? '-' }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <p class="form-control-plaintext">{{ $supplier->no_telepon ?? '-' }}</p>
                                    </div>
                                </div>

                                <!-- Tengah -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Wilayah</label>
                                        <p class="form-control-plaintext">{{ $supplier->wilayah->negara ?? '-' }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <p class="form-control-plaintext">{{ $supplier->provinsi->provinsi ?? '-' }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Kota</label>
                                        <p class="form-control-plaintext">{{ $supplier->kota->kota ?? '-' }}</p>
                                    </div>
                                </div>

                                <!-- Kanan -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Kecamatan</label>
                                        <p class="form-control-plaintext">{{ $supplier->kecamatan->kecamatan ?? '-' }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Kelurahan</label>
                                        <p class="form-control-plaintext">{{ $supplier->kelurahan->kelurahan ?? '-' }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <p class="form-control-plaintext">
                                            @if($supplier->status)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="form-group mt-3">
                                <label>Alamat Lengkap</label>
                                <p class="form-control-plaintext">{{ $supplier->alamat ?? '-' }}</p>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="mt-4">
                                <a href="{{ route('supplier.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                                <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
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
