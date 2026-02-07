<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Supplier</title>
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
                            <h1 class="m-0">Detail Supplier</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        @if ($supplier->logo)
                                            <img class="profile-user-img img-fluid img-circle"
                                                src="{{ asset($supplier->logo) }}" alt="User profile picture"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle mx-auto d-flex justify-content-center align-items-center"
                                                style="width: 100px; height: 100px;">
                                                <i class="fas fa-truck text-white" style="font-size: 40px;"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <h3 class="profile-username text-center mt-3">{{ $supplier->nama_supplier }}</h3>
                                    <p class="text-muted text-center">{{ $supplier->tipe_supplier ?? 'Supplier' }}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Kode</b> <a class="float-right">{{ $supplier->kode_supplier }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Status</b> <a class="float-right">
                                                @if ($supplier->status)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Non-Aktif</span>
                                                @endif
                                            </a>
                                        </li>
                                    </ul>

                                    <a href="{{ route('supplier.edit', $supplier->id) }}"
                                        class="btn btn-warning btn-block"><b>Edit Supplier</b></a>
                                    <a href="{{ route('supplier.index') }}"
                                        class="btn btn-default btn-block"><b>Kembali</b></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#overview"
                                                data-toggle="tab">Overview</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#contact"
                                                data-toggle="tab">Kontak</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#address"
                                                data-toggle="tab">Alamat</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="overview">
                                            <strong><i class="fas fa-file-alt mr-1"></i> Keterangan</strong>
                                            <p class="text-muted">
                                                {{ $supplier->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                                            </p>
                                        </div>

                                        <div class="tab-pane" id="contact">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong><i class="fas fa-user mr-1"></i> Penanggung Jawab</strong>
                                                    <p class="text-muted">{{ $supplier->penanggung_jawab ?? '-' }}</p>
                                                    <hr>
                                                    <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                                                    <p class="text-muted">{{ $supplier->email ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong><i class="fas fa-phone mr-1"></i> No. Telepon</strong>
                                                    <p class="text-muted">{{ $supplier->no_telepon ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="address">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Wilayah</strong>
                                                    <p class="text-muted">{{ $supplier->wilayah->name ?? '-' }}</p>
                                                    <hr>
                                                    <strong><i class="fas fa-map mr-1"></i> Provinsi</strong>
                                                    <p class="text-muted">{{ $supplier->provinsi->name ?? '-' }}
                                                    </p>
                                                    <hr>
                                                    <strong><i class="fas fa-city mr-1"></i> Kota</strong>
                                                    <p class="text-muted">{{ $supplier->kota->name ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong><i class="fas fa-map-pin mr-1"></i> Alamat Lengkap</strong>
                                                    <p class="text-muted">{{ $supplier->alamat ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
