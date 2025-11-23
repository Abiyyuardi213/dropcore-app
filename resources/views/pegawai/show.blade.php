<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pegawai</title>
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
                <h1 class="m-0">Detail Pegawai</h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-circle"></i> Informasi Pegawai
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- KOLOM KIRI -->
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-info">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-image"></i> Foto Profil
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div style="
                                            width: 260px;
                                            height: 260px;
                                            margin: auto;
                                            border-radius: 15px;
                                            overflow: hidden;
                                            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
                                            border: 1px solid #ddd;
                                        ">
                                            <img src="{{ $pegawai->profile_picture
                                                ? asset('uploads/profile/'.$pegawai->profile_picture)
                                                : 'https://via.placeholder.com/260?text=Foto' }}"
                                                style="width:100%; height:100%; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- KOLOM INFORMASI -->
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>NIP</th>
                                                <td>{{ $pegawai->nip }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <td>{{ $pegawai->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIK</th>
                                                <td>{{ $pegawai->nik }}</td>
                                            </tr>
                                            <tr>
                                                <th>Username</th>
                                                <td>{{ $pegawai->username }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $pegawai->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>No Telepon</th>
                                                <td>{{ $pegawai->no_telepon }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Jenis Kelamin</th>
                                                <td>
                                                    {{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Bergabung</th>
                                                <td>
                                                    {{ $pegawai->tanggal_bergabung
                                                        ? \Carbon\Carbon::parse($pegawai->tanggal_bergabung)->translatedFormat('d F Y')
                                                        : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Divisi</th>
                                                <td>{{ $pegawai->divisi->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jabatan</th>
                                                <td>{{ $pegawai->jabatan->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge badge-{{ $pegawai->status_kepegawaian == 'aktif' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($pegawai->status_kepegawaian) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Alamat</th>
                                                <td>{{ $pegawai->alamat ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary mt-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning mt-3">
                            <i class="fas fa-edit"></i> Edit
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
