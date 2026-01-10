<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Logo -->
    <a href="#" class="brand-link d-flex flex-column align-items-center p-2" style="overflow: hidden;">
        <img src="{{ asset('image/gfi-putih.png') }}" alt="Logo Dropcore" class="img-fluid d-none d-md-block"
            style="max-height: 40px; object-fit: contain;">
        <img src="{{ asset('image/gfi-putih.png') }}" alt="Logo Mini Dropcore" class="img-fluid d-block d-md-none"
            style="max-height: 100px; object-fit: contain;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Info -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="{{ Auth::user()->profile_picture ? asset('uploads/profile/' . Auth::user()->profile_picture) : asset('image/default-user.png') }}"
                    class="img-circle elevation-2" alt="User"
                    style="width: 45px; height: 45px; object-fit: cover; border: 2px solid white;">
            </div>
            <div class="info">
                <a href="#" class="d-block text-white font-weight-bold">
                    {{ Auth::user()->name }}
                </a>
                <span class="badge badge-success">Online</span>
                <span class="d-block" style="color: #f39c12; font-size: 14px; font-weight: 600;">
                    {{ Auth::user()->role->role_name ?? 'Tanpa Role' }}
                </span>
            </div>
        </div>

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false"
                role="menu">
                <li class="nav-item">
                    <a href="{{ url('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p>Dashboard Utama</p>
                    </a>
                </li>

                @php
                    $isMaster =
                        request()->is('dashboard-master*') ||
                        request()->is('role*') ||
                        request()->is('user*') ||
                        request()->is('category*') ||
                        request()->is('wilayah*') ||
                        request()->is('provinsi*') ||
                        request()->is('kota*') ||
                        request()->is('kecamatan*') ||
                        request()->is('kelurahan*') ||
                        request()->is('supplier*') ||
                        request()->is('kondisi-barang*');
                @endphp

                <li class="nav-item has-treeview {{ $isMaster ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isMaster ? 'active' : '' }} font-weight-bold">
                        <i class="nav-icon fas fa-folder-open"></i>
                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="{{ $isMaster ? 'display:block;' : '' }}">

                        <li class="nav-item">
                            <a href="{{ url('dashboard-master') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>Dashboard Master</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('role') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Master Peran</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('user') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Master Pengguna</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Master Kategori</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('wilayah.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-globe"></i>
                                <p>Master Wilayah</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('provinsi.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Master Provinsi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('kota.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-city"></i>
                                <p>Master Kota</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('kecamatan.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Master Kecamatan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('kelurahan.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Master Kelurahan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('supplier.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-truck"></i>
                                <p>Master Supplier</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('kondisi-barang.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>Master Kondisi Barang</p>
                            </a>
                        </li>

                    </ul>
                </li>



                @php
                    $isGudang =
                        request()->is('dashboardGudang*') ||
                        request()->is('gudang*') ||
                        request()->is('areaGudang*') ||
                        request()->is('rak-gudang*');
                @endphp

                <li class="nav-item has-treeview {{ $isGudang ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isGudang ? 'active' : '' }} font-weight-bold">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Gudang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="{{ $isGudang ? 'display: block;' : '' }}">

                        <li class="nav-item">
                            <a href="{{ url('dashboardGudang') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Dashboard Gudang</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('gudang') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Gudang</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('areaGudang.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-th-large"></i>
                                <p>Area Gudang</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('rak-gudang.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-layer-group"></i>
                                <p>Rak Gudang</p>
                            </a>
                        </li>

                    </ul>
                </li>

                @php
                    $isProduk =
                        request()->is('dashboardProduk*') || request()->is('product*') || request()->is('stok*');
                @endphp

                <li class="nav-item has-treeview {{ $isProduk ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isProduk ? 'active' : '' }} font-weight-bold">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Produk
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="{{ $isProduk ? 'display: block;' : '' }}">

                        <li class="nav-item">
                            <a href="{{ url('dashboardProduk') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard Produk</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('product') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Produk</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('stok.index') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>Stok Produk</p>
                            </a>
                        </li>

                    </ul>
                </li>

                @php
                    $isKeuangan =
                        request()->is('dashboard-keuangan*') ||
                        request()->is('kas-pusat*') ||
                        request()->is('sumber-keuangan*') ||
                        request()->is('keuangan*');
                @endphp

                <li class="nav-item has-treeview {{ $isKeuangan ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isKeuangan ? 'active' : '' }} font-weight-bold">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>
                            Keuangan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" style="{{ $isKeuangan ? 'display: block;' : '' }}">

                        <li class="nav-item">
                            <a href="{{ url('dashboard-keuangan') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Dashboard Keuangan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('kas-pusat') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-coins"></i>
                                <p>Kas Pusat</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('sumber-keuangan') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-money-bill-wave"></i>
                                <p>Sumber Keuangan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('keuangan') }}" class="nav-link text-sm pl-4">
                                <i class="nav-icon fas fa-wallet"></i>
                                <p>Keuangan</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ url('distributor') }}" class="nav-link">
                        <i class="nav-icon fas fa-truck-moving"></i>
                        <p>Distributor</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('mutasi-stok') }}" class="nav-link">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Mutasi Stok</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('riwayat-aktivitas-produk') }}" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat Aktivitas Produk</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('riwayat-logs') }}"
                        class="nav-link {{ Request::is('riwayat-logs*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p>Log Aktivitas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('penerimaan-barang') }}"
                        class="nav-link {{ Request::is('penerimaan-barang*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p>Penerimaan Barang</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('pengeluaran-barang') }}"
                        class="nav-link {{ Request::is('pengeluaran-barang*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-dolly"></i>
                        <p>Pengeluaran Barang</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('laporan') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
