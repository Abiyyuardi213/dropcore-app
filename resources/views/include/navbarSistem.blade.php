<style>
    .admin-module-search { position: relative; width: 360px; max-width: 100%; }
    .admin-module-search .form-control { border-right: 0; }
    .admin-module-results { position: absolute; top: calc(100% + 6px); left: 0; right: 0; max-height: 360px; overflow-y: auto; z-index: 1040; background: #fff; border: 1px solid #dee2e6; border-radius: .25rem; }
    .admin-module-results[hidden] { display: none !important; }
    .admin-module-results .list-group-item { border-left: 0; border-right: 0; }
    .admin-navbar-user { flex-shrink: 0; }
    .admin-navbar-username { display: inline-block; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle; }
    body.swal2-toast-shown .swal2-container { pointer-events: none !important; }
    body.swal2-toast-shown .swal2-container .swal2-toast { pointer-events: auto; }
    body.layout-navbar-fixed.swal2-toast-shown .swal2-container.swal2-top-end { top: var(--admin-toast-top, 68px) !important; }
    @media (max-width: 575.98px) {
        .admin-module-search { width: 100%; }
        .admin-navbar-username { display: none; }
        .admin-module-results { width: min(320px, calc(100vw - 80px)); }
    }
</style>
<nav class="main-header navbar navbar-expand navbar-white navbar-light" aria-label="Navigasi administrator">
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Buka atau tutup sidebar"><i class="fas fa-bars" aria-hidden="true"></i></a></li>
    </ul>
    <form class="admin-module-search mx-2" id="adminModuleSearchForm" role="search" autocomplete="off">
        <div class="input-group input-group-sm">
            <input id="adminModuleSearch" class="form-control form-control-navbar" type="search" placeholder="Search modul & fitur" aria-label="Search modul dan fitur" role="combobox" aria-autocomplete="list" aria-controls="adminModuleResults" aria-expanded="false">
            <div class="input-group-append"><button class="btn btn-navbar" type="submit" aria-label="Cari modul"><i class="fas fa-search" aria-hidden="true"></i></button></div>
        </div>
        <div id="adminModuleResults" class="admin-module-results list-group shadow" role="listbox" aria-label="Hasil pencarian modul" hidden></div>
    </form>
    <ul class="navbar-nav ml-auto admin-navbar-user">
        <li class="nav-item dropdown" id="adminUserMenu">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle" aria-hidden="true"></i> <span class="admin-navbar-username">{{ Auth::user()->username }}</span><span class="sr-only">Menu profil pengguna</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt mr-2" aria-hidden="true"></i>Dashboard</a>
                <a class="dropdown-item" href="{{ route('user.profil') }}"><i class="fas fa-user mr-2" aria-hidden="true"></i>Profil</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#" onclick="confirmLogout(event)"><i class="fas fa-sign-out-alt mr-2" aria-hidden="true"></i>Logout</a>
            </div>
        </li>
    </ul>
</nav>
<script>document.body.classList.add('layout-navbar-fixed');</script>
<script src="{{ asset('resources/js/AdminNavbar.js') }}" defer></script>
