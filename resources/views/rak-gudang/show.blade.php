<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rak Gudang</title>
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
                            <h1 class="m-0">Detail Rak Gudang</h1>
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
                                    <div class="text-center mb-3">
                                        <div class="bg-primary rounded-circle mx-auto d-flex justify-content-center align-items-center"
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-cubes text-white" style="font-size: 32px;"></i>
                                        </div>
                                    </div>

                                    <h3 class="profile-username text-center">{{ $rak->kode_rak }}</h3>
                                    <p class="text-muted text-center">{{ $rak->jenis_rak ?? 'Standard' }}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Gudang</b> <a
                                                class="float-right">{{ $rak->gudang->nama_gudang ?? '-' }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Area</b> <a class="float-right">{{ $rak->area->kode_area ?? '-' }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Status</b> <a class="float-right">
                                                @if ($rak->rak_status)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                @endif
                                            </a>
                                        </li>
                                    </ul>

                                    <a href="{{ route('rak-gudang.edit', $rak->id) }}"
                                        class="btn btn-warning btn-block"><b>Edit Rak</b></a>
                                    <a href="{{ route('rak-gudang.index') }}"
                                        class="btn btn-default btn-block"><b>Kembali</b></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#info"
                                                data-toggle="tab">Informasi & Lokasi</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#specs"
                                                data-toggle="tab">Spesifikasi & Kapasitas</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <!-- Info Tab -->
                                        <div class="active tab-pane" id="info">
                                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Posisi Detail</strong>
                                            <p class="text-muted">
                                                {{ $rak->posisi ?? 'Belum ditentukan' }}
                                            </p>
                                            <hr>
                                            <strong><i class="fas fa-file-alt mr-1"></i> Keterangan</strong>
                                            <p class="text-muted">
                                                {!! nl2br(e($rak->keterangan ?? 'Tidak ada keterangan tambahan.')) !!}
                                            </p>
                                        </div>

                                        <!-- Specs Tab -->
                                        <div class="tab-pane" id="specs">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong><i class="fas fa-weight-hanging mr-1"></i> Kapasitas
                                                        Maksimum</strong>
                                                    <p class="text-muted">
                                                        {{ $rak->kapasitas_max ?? '-' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong><i class="fas fa-ruler-combined mr-1"></i> Dimensi</strong>
                                                    <p class="text-muted">
                                                        {{ $rak->dimensi ?? '-' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong><i class="fas fa-layer-group mr-1"></i> Bahan
                                                        Material</strong>
                                                    <p class="text-muted">
                                                        {{ $rak->bahan_rak ?? '-' }}
                                                    </p>
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
