<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kasir | Garuda Fiber')</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body { font-family: 'Source Sans Pro', sans-serif; background-color: #f4f6f9; padding-top: 56px; }
        .main-header, .content-wrapper, .main-footer { margin-left: 0 !important; }
        .main-header { min-height: 56px; }
        .content-wrapper { background-color: #f4f6f9; padding-top: 0 !important; }
        .content-header { padding: 12px 0 8px; }
        .kasir-shortcuts-container,
        .content-wrapper > .content-header > .container-fluid,
        .content-wrapper > .content > .container-fluid {
            max-width: 1440px;
            padding-left: 64px !important;
            padding-right: 64px !important;
        }
        .brand-link-custom { font-size: 1.25rem; font-weight: 700; color: #fff !important; }
        .kasir-heading { font-size: 1.6rem !important; }
        .kasir-card { border-radius: 8px; }
        .kasir-card.card-outline { border-top-color: #001f3f; }
        .kasir-brand-logo { width: 108px; height: auto; }
        .kasir-navbar-user { flex-shrink: 0; gap: .75rem; }
        .kasir-navbar-user .nav-link { display: flex; align-items: center; gap: .65rem; height: auto; padding: .35rem .65rem; font-weight: 600; }
        .kasir-navbar-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0; background: #f4f6f9; }
        .kasir-account-name { display: inline-block; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle; }
        .kasir-navbar-role { text-transform: uppercase; }
        body.swal2-toast-shown .swal2-container { pointer-events: none !important; }
        body.swal2-toast-shown .swal2-container .swal2-toast { pointer-events: auto; }
        body.swal2-toast-shown .swal2-container.swal2-top-end { top: 68px !important; }
        @media (max-width: 575.98px) {
            .kasir-shortcuts-container,
        .content-wrapper > .content-header > .container-fluid,
            .content-wrapper > .content > .container-fluid {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }
            .kasir-nav-container { padding-left: 1rem !important; padding-right: 1rem !important; }
            .kasir-account-name, .kasir-navbar-logout-label { display: none; }
            .kasir-navbar-user { gap: 0; }
        }
    </style>
    @stack('styles')
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark navbar-navy border-bottom-0 shadow-sm fixed-top" aria-label="Navigasi kasir">
        <div class="container-fluid px-4 kasir-nav-container">
            <a href="{{ route('kasir.index') }}" class="navbar-brand brand-link-custom d-flex align-items-center">
                <img src="{{ asset('image/gfi-putih.png') }}" class="kasir-brand-logo" alt="Garuda Fiber">
            </a>
            <ul class="navbar-nav d-none d-xl-flex ml-3">
                <li class="nav-item"><a href="{{ route('kasir.index') }}" class="nav-link {{ request()->routeIs('kasir.index') ? 'active' : '' }}"><i class="fas fa-home mr-1" aria-hidden="true"></i>Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('kasir.penjualan') }}" class="nav-link {{ request()->routeIs('kasir.penjualan') ? 'active' : '' }}"><i class="fas fa-list mr-1" aria-hidden="true"></i>Penjualan</a></li>
                <li class="nav-item"><a href="{{ route('kasir.produk') }}" class="nav-link {{ request()->routeIs('kasir.produk') ? 'active' : '' }}"><i class="fas fa-box mr-1" aria-hidden="true"></i>Produk</a></li>
                <li class="nav-item"><a href="{{ route('kasir.pelanggan') }}" class="nav-link {{ request()->routeIs('kasir.pelanggan') ? 'active' : '' }}"><i class="fas fa-users mr-1" aria-hidden="true"></i>Pelanggan</a></li>
                <li class="nav-item"><a href="{{ route('kasir.laporan') }}" class="nav-link {{ request()->routeIs('kasir.laporan') ? 'active' : '' }}"><i class="fas fa-file-lines mr-1" aria-hidden="true"></i>Laporan</a></li>
                <li class="nav-item"><a href="{{ route('kasir.password') }}" class="nav-link {{ request()->routeIs('kasir.password') ? 'active' : '' }}"><i class="fas fa-lock mr-1" aria-hidden="true"></i>Password</a></li>
            </ul>
            <ul class="navbar-nav ml-auto align-items-center kasir-navbar-user">
                <li class="nav-item dropdown">
                    <button class="nav-link btn btn-link text-white" type="button" id="kasirAccount" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Buka menu akun {{ auth()->user()->name }}" title="{{ auth()->user()->name }} - KASIR">
                        <img class="kasir-navbar-avatar" src="{{ auth()->user()->profile_picture ? asset('uploads/profile/'.auth()->user()->profile_picture) : asset('image/default-user.png') }}" alt="Foto profil" onerror="this.onerror=null;this.src='{{ asset('image/default-user.png') }}';">
                        <span class="kasir-account-name">{{ auth()->user()->name }} - <span class="kasir-navbar-role">KASIR</span></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="kasirAccount">
                        <span class="dropdown-header text-uppercase font-weight-bold">Role: Kasir</span>
                        <a class="dropdown-item" href="{{ route('kasir.index') }}">Dashboard</a>
                        <a class="dropdown-item" href="{{ route('kasir.penjualan') }}">Penjualan</a>
                        <a class="dropdown-item" href="{{ route('kasir.produk') }}">Produk</a>
                        <a class="dropdown-item" href="{{ route('kasir.pelanggan') }}">Pelanggan</a>
                        <a class="dropdown-item" href="{{ route('kasir.laporan') }}">Laporan</a>
                        <a class="dropdown-item" href="{{ route('kasir.password') }}">Ganti Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('kasir.logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-white" aria-label="Logout dari sistem kasir"><i class="fas fa-sign-out-alt" aria-hidden="true"></i><span class="kasir-navbar-logout-label">LOGOUT</span></button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <main class="content-wrapper">
        @hasSection('shortcuts')<div class="container-fluid kasir-shortcuts-container pt-4">@yield('shortcuts')</div>@endif
        <div class="content-header">
            <div class="container-fluid px-4">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6"><h1 class="m-0 text-dark font-weight-bold kasir-heading"><i class="fas fa-cash-register text-primary mr-2" aria-hidden="true"></i>@yield('heading', 'Dashboard Kasir')</h1></div>
                    <div class="col-sm-6"><ol class="breadcrumb float-sm-right mb-0"><li class="breadcrumb-item"><a href="{{ route('kasir.index') }}">Kasir</a></li><li class="breadcrumb-item active" aria-current="page">@yield('heading', 'Dashboard Kasir')</li></ol></div>
                </div>
            </div>
        </div>
        <section class="content"><div class="container-fluid px-4 pb-3">@yield('content')</div></section>
    </main>
    <footer class="main-footer"><div class="container-fluid px-2 text-muted small">Garuda Fiber &middot; Portal Kasir</div></footer>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
