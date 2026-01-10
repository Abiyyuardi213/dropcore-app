<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Log Sistem | DropCore</title>
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
                            <h1 class="m-0">Detail Log Sistem</h1>
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
                                if (stripos($log->action, 'create') !== false) {
                                    $color = 'success';
                                    $icon = 'check-circle';
                                } elseif (stripos($log->action, 'update') !== false) {
                                    $color = 'warning';
                                    $icon = 'pen';
                                } elseif (stripos($log->action, 'delete') !== false) {
                                    $color = 'danger';
                                    $icon = 'trash-alt';
                                } elseif (stripos($log->action, 'login') !== false) {
                                    $color = 'primary';
                                    $icon = 'user-check';
                                } elseif (stripos($log->action, 'logout') !== false) {
                                    $color = 'secondary';
                                    $icon = 'user-times';
                                }
                            @endphp

                            <div class="card card-outline card-{{ $color }}">
                                <div class="card-header text-center border-bottom-0">
                                    <span class="display-4 text-{{ $color }}">
                                        <i class="fas fa-{{ $icon }}"></i>
                                    </span>
                                    <h3 class="mt-2">{{ ucfirst($log->action) }}</h3>
                                    <span
                                        class="badge badge-{{ $color }} px-3 py-2 text-md">{{ ucfirst($log->module) }}</span>
                                    <p class="text-muted mt-2 mb-0">
                                        {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('l, d F Y - H:i:s') }}
                                    </p>
                                </div>
                                <div class="card-body box-profile">
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>User (Pelaku)</b>
                                            <a class="float-right text-dark">
                                                @if ($log->user)
                                                    <i class="fas fa-user-circle mr-1"></i> {{ $log->user->name }}
                                                    <small
                                                        class="text-muted">({{ $log->user->email ?? 'No Email' }})</small>
                                                @else
                                                    <i class="fas fa-robot mr-1"></i> System / Guest
                                                @endif
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Modul</b>
                                            <a class="float-right">{{ ucfirst($log->module) }}</a>
                                        </li>
                                    </ul>

                                    <div class="callout callout-{{ $color }}">
                                        <h5>Deskripsi Log:</h5>
                                        <p>{{ $log->description ?? 'Tidak ada data detail tambahan.' }}</p>
                                    </div>
                                </div>
                                <div class="card-footer text-center bg-white">
                                    <a href="{{ route('riwayat-log.index') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Log
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
