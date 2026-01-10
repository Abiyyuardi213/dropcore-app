<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Distributor</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .profile-user-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        #map {
            height: 300px;
            width: 100%;
            border-radius: 4px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('include.navbarSistem')
        @include('include.sidebar')

        <div class="content-wrapper">
            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Detail Distributor</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="{{ route('distributor.index') }}" class="btn btn-secondary"><i
                                    class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Left Column: Primary Info -->
                        <div class="col-md-4">
                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <i class="fas fa-building fa-4x text-gray mb-3"></i>
                                    </div>

                                    <h3 class="profile-username text-center">{{ $distributor->nama_distributor }}</h3>

                                    <p class="text-muted text-center">{{ $distributor->kode_distributor }}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Tipe</b> <a class="float-right">
                                                @if ($distributor->tipe_distributor == 'Principal')
                                                    <span class="badge badge-primary">Principal</span>
                                                @elseif($distributor->tipe_distributor == 'Distributor')
                                                    <span class="badge badge-info">Distributor</span>
                                                @else
                                                    <span class="badge badge-secondary">Reseller</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Status</b> <a class="float-right">
                                                @if ($distributor->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @elseif($distributor->status == 'inactive')
                                                    <span class="badge badge-danger">Inactive</span>
                                                @else
                                                    <span class="badge badge-dark">Blacklisted</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Kota</b> <a
                                                class="float-right">{{ $distributor->kota ? $distributor->kota->kota : '-' }}</a>
                                        </li>
                                    </ul>
                                    <a href="{{ route('distributor.edit', $distributor->id) }}"
                                        class="btn btn-warning btn-block"><b><i class="fas fa-edit"></i> Edit
                                            Data</b></a>
                                </div>
                            </div>

                            <!-- About Me Box -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Kontak & Info</h3>
                                </div>
                                <div class="card-body">
                                    <strong><i class="fas fa-phone mr-1"></i> Telepon</strong>
                                    <p class="text-muted">{{ $distributor->telepon ?? '-' }}</p>
                                    <hr>

                                    <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                                    <p class="text-muted">{{ $distributor->email ?? '-' }}</p>
                                    <hr>

                                    <strong><i class="fas fa-globe mr-1"></i> Website</strong>
                                    <p class="text-muted">
                                        @if ($distributor->website)
                                            <a href="{{ $distributor->website }}"
                                                target="_blank">{{ $distributor->website }}</a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <hr>

                                    <strong><i class="fas fa-id-card mr-1"></i> NPWP</strong>
                                    <p class="text-muted">{{ $distributor->npwp ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Details -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#details"
                                                data-toggle="tab">Detail Lengkap</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#location"
                                                data-toggle="tab">Lokasi Peta</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="details">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5><i class="fas fa-user-friends mr-1"></i> Person In Charge (PIC)
                                                    </h5>
                                                    <div class="callout callout-info">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Nama PIC:</strong><br>
                                                                {{ $distributor->pic_nama ?? '-' }}
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Telepon PIC:</strong><br>
                                                                {{ $distributor->pic_telepon ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <h5><i class="fas fa-map-marker-alt mr-1"></i> Alamat Lengkap</h5>
                                                    <p class="text-muted well well-sm no-shadow"
                                                        style="margin-top: 10px;">
                                                        {{ $distributor->alamat ?? 'Belum ada data alamat.' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <h5><i class="fas fa-sticky-note mr-1"></i> Keterangan Tambahan</h5>
                                                    <p class="text-muted">
                                                        {{ $distributor->keterangan ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="location">
                                            @if ($distributor->latitude && $distributor->longitude)
                                                <div id="map"></div>
                                                <div class="mt-2 text-muted text-sm">
                                                    <i class="fas fa-map-pin"></i> {{ $distributor->latitude }},
                                                    {{ $distributor->longitude }}
                                                </div>
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="icon fas fa-exclamation-triangle"></i> Koordinat lokasi
                                                    (Latitude/Longitude) belum diatur untuk distributor ini.
                                                </div>
                                            @endif
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

    @include('services.logoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        $(document).ready(function() {
            @if ($distributor->latitude && $distributor->longitude)
                // Initialize map when the tab is shown to prevent rendering issues
                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    if (e.target.hash === '#location') {
                        // Check if map is already initialized
                        var container = L.DomUtil.get('map');
                        if (container != null) {
                            container._leaflet_id = null;
                        }

                        var map = L.map('map').setView([{{ $distributor->latitude }},
                            {{ $distributor->longitude }}
                        ], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        L.marker([{{ $distributor->latitude }}, {{ $distributor->longitude }}]).addTo(map)
                            .bindPopup(
                                '<b>{{ $distributor->nama_distributor }}</b><br>{{ $distributor->alamat }}'
                                )
                            .openPopup();
                    }
                });
            @endif
        });
    </script>
</body>

</html>
