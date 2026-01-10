<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Aktivitas | DropCore</title>
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
                            <h1 class="m-0">Detail Riwayat Aktivitas</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('riwayat-aktivitas-produk.index') }}">Riwayat</a></li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            @php
                                $color = 'secondary';
                                $icon = 'info';
                                if (stripos($riwayat->tipe_aktivitas, 'create') !== false) {
                                    $color = 'success';
                                    $icon = 'check';
                                } elseif (stripos($riwayat->tipe_aktivitas, 'update') !== false) {
                                    $color = 'warning';
                                    $icon = 'edit';
                                } elseif (stripos($riwayat->tipe_aktivitas, 'delete') !== false) {
                                    $color = 'danger';
                                    $icon = 'trash';
                                } elseif (stripos($riwayat->tipe_aktivitas, 'stok') !== false) {
                                    $color = 'info';
                                    $icon = 'box-open';
                                }
                            @endphp

                            <div class="card card-outline card-{{ $color }}">
                                <div class="card-header text-center border-bottom-0">
                                    <span class="display-4 text-{{ $color }}">
                                        <i class="fas fa-{{ $icon }}"></i>
                                    </span>
                                    <h3 class="mt-2">{{ ucfirst($riwayat->tipe_aktivitas) }}</h3>
                                    <p class="text-muted">
                                        {{ \Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('l, d F Y - H:i') }}
                                    </p>
                                </div>
                                <div class="card-body box-profile">
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Produk</b>
                                            <a class="float-right">
                                                @if ($riwayat->produk)
                                                    {{ $riwayat->produk->name }}
                                                @else
                                                    <span class="text-danger font-italic">Produk Telah Dihapus</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>User (Pelaku)</b>
                                            <a class="float-right text-dark">
                                                @if ($riwayat->user)
                                                    <i class="fas fa-user mr-1"></i> {{ $riwayat->user->name }}
                                                @else
                                                    <i class="fas fa-robot mr-1"></i> System / Unknown
                                                @endif
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="callout callout-{{ $color }}">
                                        <h5>Deskripsi:</h5>
                                        <p>{{ $riwayat->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}</p>
                                    </div>
                                </div>
                                <div class="card-footer text-center bg-white">
                                    <a href="{{ route('riwayat-aktivitas-produk.index') }}"
                                        class="btn btn-secondary px-4">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                                    </a>
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
