<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Master - Garuda Fiber</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
        }
    </style>
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
                            <h1 class="m-0">Dashboard Master</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard Master</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Row 1: Users & Facilities -->
                    <h5 class="mb-2">Admin & Fasilitas</h5>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalPeran ?? 0 }}</h3>
                                    <p>Total Peran</p>
                                </div>
                                <div class="icon"><i class="fas fa-user-tag"></i></div>
                                <a href="{{ url('admin/role') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalPengguna ?? 0 }}</h3>
                                    <p>Total Pengguna</p>
                                </div>
                                <div class="icon"><i class="fas fa-user-friends"></i></div>
                                <a href="{{ url('admin/user') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $totalGudang ?? 0 }}</h3>
                                    <p>Total Gudang</p>
                                </div>
                                <div class="icon"><i class="fas fa-warehouse"></i></div>
                                <a href="{{ url('admin/gudang') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $totalSupplier ?? 0 }}</h3>
                                    <p>Total Supplier</p>
                                </div>
                                <div class="icon"><i class="fas fa-truck"></i></div>
                                <a href="{{ url('admin/supplier') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Regions -->
                    <h5 class="mb-2 mt-2">Data Wilayah</h5>
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-map"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Provinsi</span>
                                    <span class="info-box-number">{{ $totalProvinsi ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-city"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kota/Kab</span>
                                    <span class="info-box-number">{{ $totalKota ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-fuchsia elevation-1"><i
                                        class="fas fa-map-signs"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kecamatan</span>
                                    <span class="info-box-number">{{ $totalKecamatan ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-pink elevation-1"><i
                                        class="fas fa-map-marker-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kelurahan</span>
                                    <span class="info-box-number">{{ $totalKelurahan ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="row">
                        <!-- Left Col: Recent Users -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <h3 class="card-title">Pengguna Terbaru</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Bergabung</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recentUsers as $u)
                                                    <tr>
                                                        <td>{{ $u->name }}</td>
                                                        <td>{{ $u->email }}</td>
                                                        <td><span
                                                                class="badge badge-info">{{ $u->role->role_name ?? '-' }}</span>
                                                        </td>
                                                        <td>{{ optional($u->created_at)->format('d M Y') ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer clearfix">
                                    <a href="{{ url('admin/user/create') }}"
                                        class="btn btn-sm btn-info float-left">Tambah User</a>
                                    <a href="{{ url('admin/user') }}"
                                        class="btn btn-sm btn-secondary float-right">Lihat Semua</a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Col: Activity Log -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Aktivitas Terbaru</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="d-flex flex-column list-unstyled p-2">
                                        @foreach ($recentLogs as $log)
                                            <li class="border-bottom py-2">
                                                <i class="fas fa-history text-muted mr-2"></i>
                                                <span
                                                    class="text-sm font-weight-bold">{{ $log->user->name ?? 'System' }}</span>
                                                <span class="text-sm">{{ $log->description }}</span>
                                                <br>
                                                <small class="text-muted ml-4"><i class="far fa-clock"></i>
                                                    {{ $log->created_at->diffForHumans() }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('riwayat-log.index') }}">Lihat Semua Log</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    @include('services.ToastModal')
    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="{{ asset('resources/js/ToastScript.js') }}"></script>
</body>

</html>
