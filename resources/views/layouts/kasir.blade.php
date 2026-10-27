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
        .kasir-brand-logo { width: 108px; height: auto; padding: 5px 8px; border-radius: 4px; background: #fff; }
        @media (max-width: 575.98px) {
            .kasir-shortcuts-container,
        .content-wrapper > .content-header > .container-fluid,
            .content-wrapper > .content > .container-fluid {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }
            .kasir-nav-container { padding-left: 1rem !important; padding-right: 1rem !important; }
            .kasir-account-name { max-width: 105px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; vertical-align: middle; }
        }
    </style>
    @stack('styles')
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark navbar-navy border-bottom-0 shadow-sm fixed-top" aria-label="Navigasi kasir">
        <div class="container-fluid px-4 kasir-nav-container">
            <a href="{{ route('kasir.index') }}" class="navbar-brand brand-link-custom d-flex align-items-center">
                <img src="{{ asset('image/garuda-fiber.png') }}" class="kasir-brand-logo mr-2" alt="Garuda Fiber">
                <span>KASIR</span>
            </a>
            <ul class="navbar-nav d-none d-xl-flex ml-3">
                <li class="nav-item"><a href="{{ route('kasir.index') }}" class="nav-link {{ request()->routeIs('kasir.index') ? 'active' : '' }}"><i class="fas fa-home mr-1" aria-hidden="true"></i>Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('kasir.penjualan') }}" class="nav-link {{ request()->routeIs('kasir.penjualan') ? 'active' : '' }}"><i class="fas fa-list mr-1" aria-hidden="true"></i>Penjualan</a></li>
                <li class="nav-item"><a href="{{ route('kasir.produk') }}" class="nav-link {{ request()->routeIs('kasir.produk') ? 'active' : '' }}"><i class="fas fa-box mr-1" aria-hidden="true"></i>Produk</a></li>
                <li class="nav-item"><a href="{{ route('kasir.pelanggan') }}" class="nav-link {{ request()->routeIs('kasir.pelanggan') ? 'active' : '' }}"><i class="fas fa-users mr-1" aria-hidden="true"></i>Pelanggan</a></li>
                <li class="nav-item"><a href="{{ route('kasir.laporan') }}" class="nav-link {{ request()->routeIs('kasir.laporan') ? 'active' : '' }}"><i class="fas fa-file-lines mr-1" aria-hidden="true"></i>Laporan</a></li>
                <li class="nav-item"><a href="{{ route('kasir.password') }}" class="nav-link {{ request()->routeIs('kasir.password') ? 'active' : '' }}"><i class="fas fa-lock mr-1" aria-hidden="true"></i>Password</a></li>
            </ul>
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item dropdown">
                    <button class="nav-link btn btn-link text-white font-weight-bold dropdown-toggle" type="button" id="kasirAccount" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle mr-1" aria-hidden="true"></i><span class="kasir-account-name">{{ auth()->user()->name }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="kasirAccount">
                        <span class="dropdown-header text-uppercase font-weight-bold">Role: Kasir</span>
                        <a class="dropdown-item" href="{{ route('kasir.index') }}">Dashboard</a>
                        <a class="dropdown-item" href="{{ route('kasir.penjualan') }}">Penjualan</a>
                        <a class="dropdown-item" href="{{ route('kasir.produk') }}">Produk</a>
                        <a class="dropdown-item" href="{{ route('kasir.pelanggan') }}">Pelanggan</a>
                        <a class="dropdown-item" href="{{ route('kasir.laporan') }}">Laporan</a>
                        <a class="dropdown-item" href="{{ route('kasir.password') }}">Ganti Password</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('kasir.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger font-weight-bold"><i class="fas fa-sign-out-alt mr-2" aria-hidden="true"></i>Logout / Keluar</button>
                        </form>
                    </div>
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
