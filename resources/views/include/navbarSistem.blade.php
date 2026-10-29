<style>
    .admin-module-search { position: relative; width: 360px; max-width: 100%; }
    .admin-module-search .form-control { border-right: 0; }
    .admin-module-results { position: absolute; top: calc(100% + 6px); left: 0; right: 0; max-height: 360px; overflow-y: auto; z-index: 1040; background: #fff; border: 1px solid #dee2e6; border-radius: .25rem; }
    .admin-module-results[hidden] { display: none !important; }
    .admin-module-results .list-group-item { border-left: 0; border-right: 0; }
    .admin-navbar-user { flex-shrink: 0; align-items: center; gap: .75rem; }
    .admin-navbar-user .nav-link { display: flex; align-items: center; gap: .65rem; height: auto; padding: .35rem .65rem; font-weight: 600; }
    .admin-navbar-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0; background: #f4f6f9; }
    .admin-navbar-username { display: inline-block; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle; }
    .admin-navbar-role { text-transform: uppercase; }
    .admin-module-search { min-width: 0; }
    body.swal2-toast-shown .swal2-container { pointer-events: none !important; }
    body.swal2-toast-shown .swal2-container .swal2-toast { pointer-events: auto; }
    body.layout-navbar-fixed.swal2-toast-shown .swal2-container.swal2-top-end { top: var(--admin-toast-top, 68px) !important; }
    @media (max-width: 575.98px) {
        .admin-module-search { width: 100%; }
        .admin-navbar-username, .admin-navbar-logout-label { display: none; }
        .admin-navbar-user { gap: 0; }
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
        <li class="nav-item" id="adminUserMenu">
            <a class="nav-link" href="{{ route('user.profil') }}" aria-label="Buka profil {{ Auth::user()->name }}" title="{{ Auth::user()->name }} - {{ strtoupper(Auth::user()->role->role_name ?? 'Pengguna') }}">
                <img class="admin-navbar-avatar" src="{{ Auth::user()->profile_picture ? asset('uploads/profile/'.Auth::user()->profile_picture) : asset('image/default-user.png') }}" alt="Foto profil" onerror="this.onerror=null;this.src='{{ asset('image/default-user.png') }}';">
                <span class="admin-navbar-username">{{ Auth::user()->name }} - <span class="admin-navbar-role">{{ Auth::user()->role->role_name ?? 'Pengguna' }}</span></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="confirmLogout(event)" aria-label="Logout dari sistem"><i class="fas fa-sign-out-alt" aria-hidden="true"></i><span class="admin-navbar-logout-label">LOGOUT</span></a>
        </li>
    </ul>
</nav>
<script>document.body.classList.add('layout-navbar-fixed');</script>
<script src="{{ asset('resources/js/AdminNavbar.js') }}" defer></script>
