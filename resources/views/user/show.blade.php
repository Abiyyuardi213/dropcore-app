<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
        }
        .profile-img {
            width: 100%;
            max-width: 200px;
            height: auto;
            object-fit: cover;
            border: 4px solid #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .user-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        .info-value {
            font-size: 16px;
            font-weight: 500;
        }
        .badge-custom {
            font-size: 14px;
            padding: 6px 10px;
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
                <h1 class="m-0 text-dark">Detail Pengguna</h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid d-flex justify-content-center">
                <div class="card shadow w-100" style="max-width: 1100px;">

                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0"><i class="fas fa-user-circle mr-2"></i>Profil Pengguna</h3>
                    </div>

                    <div class="card-body">

                        <div class="row align-items-start">

                            <!-- FOTO PROFIL -->
                            <div class="col-md-4 text-center mb-4">
                                <img src="{{ $user->profile_picture ? asset('uploads/profile/' . $user->profile_picture) : asset('image/default-avatar.png') }}"
                                     class="profile-img">
                            </div>

                            <!-- ATRIBUT LENGKAP -->
                            <div class="col-md-8">
                                <div class="user-info">

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-id-card mr-1"></i>NIP</span><br>
                                        <span class="info-value">{{ $user->nip ?? '-' }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-user mr-1"></i>Nama Lengkap</span><br>
                                        <span class="info-value">{{ $user->name }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-user-tag mr-1"></i>Username</span><br>
                                        <span class="info-value">{{ $user->username }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-envelope mr-1"></i>Email</span><br>
                                        <span class="info-value">{{ $user->email }}</span>
                                        @if($user->email_verified_at)
                                            <span class="badge badge-success badge-custom ml-2">Terverifikasi</span>
                                        @else
                                            <span class="badge badge-warning badge-custom ml-2">Belum Verifikasi</span>
                                        @endif
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-phone mr-1"></i>No Telepon</span><br>
                                        <span class="info-value">{{ $user->no_telepon ?? '-' }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-venus-mars mr-1"></i>Jenis Kelamin</span><br>
                                        <span class="info-value">
                                            {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-map-marker-alt mr-1"></i>Alamat</span><br>
                                        <span class="info-value">{{ $user->alamat ?? '-' }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-users mr-1"></i>Divisi</span><br>
                                        <span class="info-value">{{ $user->divisi->name ?? '-' }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-sitemap mr-1"></i>Jabatan</span><br>
                                        <span class="info-value">{{ $user->jabatan->name ?? '-' }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-user-shield mr-1"></i>Role Sistem</span><br>
                                        <span class="badge badge-info badge-custom">
                                            {{ $user->role->role_name ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-calendar-check mr-1"></i>Tanggal Bergabung</span><br>
                                        <span class="info-value">
                                            {{ $user->tanggal_bergabung ? $user->tanggal_bergabung->format('d M Y') : '-' }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-user-clock mr-1"></i>Status Kepegawaian</span><br>
                                        <span class="badge badge-{{ $user->status_kepegawaian === 'aktif' ? 'success' : 'secondary' }} badge-custom">
                                            {{ ucfirst($user->status_kepegawaian) }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-clock mr-1"></i>Dibuat Pada</span><br>
                                        <span class="info-value">{{ optional($user->created_at)->format('d M Y H:i') ?? '-' }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-clock mr-1"></i>Terakhir Diperbarui</span><br>
                                        <span class="info-value">{{ optional($user->updated_at)->format('d M Y H:i') ?? '-' }}</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
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
