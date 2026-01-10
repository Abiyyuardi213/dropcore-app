<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna</title>
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
                            <h1 class="m-0">Profile Pengguna</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Data Pengguna</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Left Column: Profile Card -->
                        <div class="col-md-3">

                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="{{ $user->profile_picture ? asset('uploads/profile/' . $user->profile_picture) : asset('image/default-user.png') }}"
                                            alt="User profile picture"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>

                                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                                    <p class="text-muted text-center">
                                        {{ $user->jabatan->name ?? 'Jabatan tidak diatur' }}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>NIP</b> <a class="float-right">{{ $user->nip ?? '-' }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Username</b> <a class="float-right">{{ $user->username }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Status</b> <a class="float-right">
                                                <span
                                                    class="badge badge-{{ $user->status_kepegawaian == 'aktif' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($user->status_kepegawaian) }}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('user.edit', $user->id) }}"
                                            class="btn btn-warning btn-block mr-1"><b>Edit</b></a>
                                        <a href="{{ route('user.index') }}"
                                            class="btn btn-secondary btn-block mt-0"><b>Kembali</b></a>
                                    </div>

                                </div>
                            </div>

                            <!-- About Me Box -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Tentang Saya</h3>
                                </div>
                                <div class="card-body">
                                    <strong><i class="fas fa-venus-mars mr-1"></i> Jenis Kelamin</strong>
                                    <p class="text-muted">
                                        {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                                    </p>
                                    <hr>

                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                                    <p class="text-muted">{{ $user->alamat ?? 'Belum diisi' }}</p>
                                    <hr>

                                    <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                                    <p class="text-muted">{{ $user->email }}</p>
                                    <hr>

                                    <strong><i class="fas fa-phone mr-1"></i> No. Telepon</strong>
                                    <p class="text-muted">{{ $user->no_telepon ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Details Tabs -->
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#kepegawaian"
                                                data-toggle="tab">Kepegawaian</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#akun" data-toggle="tab">Info
                                                Akun</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane" id="kepegawaian">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Divisi</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control"
                                                            value="{{ $user->divisi->name ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Jabatan</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control"
                                                            value="{{ $user->jabatan->name ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Role Sistem</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control"
                                                            value="{{ $user->role->role_name ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Bergabung</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control"
                                                            value="{{ $user->tanggal_bergabung ? $user->tanggal_bergabung->format('d F Y') : '-' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">NIK</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control"
                                                            value="{{ $user->nik ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="akun">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Email Verified</label>
                                                    <div class="col-sm-10">
                                                        @if ($user->email_verified_at)
                                                            <span class="badge badge-success">Terverifikasi pada
                                                                {{ $user->email_verified_at->format('d M Y H:i') }}</span>
                                                        @else
                                                            <span class="badge badge-warning">Belum Verifikasi</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Dibuat Pada</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control"
                                                            value="{{ $user->created_at ? $user->created_at->format('d F Y H:i') : '-' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Terakhir Update</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control"
                                                            value="{{ $user->updated_at ? $user->updated_at->format('d F Y H:i') : '-' }}"
                                                            readonly>
                                                    </div>
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

    @include('services.logoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>

</html>
