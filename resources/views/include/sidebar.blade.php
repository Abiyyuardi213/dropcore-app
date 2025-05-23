<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Logo -->
    <a href="#" class="brand-link d-flex flex-column align-items-center p-2" style="overflow: hidden;">
        <img src="{{ asset('image/logo-gfs.png') }}"
             alt="Logo Dropcore"
             class="img-fluid d-none d-md-block"
             style="max-height: 60px; object-fit: contain;">
        <img src="{{ asset('image/dropcore-sidebar.png') }}"
             alt="Logo Mini Dropcore"
             class="img-fluid d-block d-md-none"
             style="max-height: 100px; object-fit: contain;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Info -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="#" class="img-circle elevation-2" alt="User"
                     style="width: 45px; height: 45px; object-fit: cover; border: 2px solid white;">
            </div>
            <div class="info">
                <a href="#" class="d-block text-white font-weight-bold">username</a>
                <span class="badge badge-success">Online</span>
                <span class="d-block" style="color: #f39c12; font-size: 14px; font-weight: 600;">role name</span>
            </div>
        </div>

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false" role="menu">
                <li class="nav-item">
                    <a href="{{ url('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>Dashboard Gudang</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('role.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Manajemen Peran</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('user') }}" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Manajemen Pengguna</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('category') }}" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Manajemen Kategori</p>
                    </a>
                </li>

                @php
                    $isGudang = request()->is('dashboardGudang*') || request()->is('gudang*') || request()->is('areaGudang*') || request()->is('rak-gudang*');
                @endphp
                <li class="nav-item has-treeview {{ $isGudang ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isGudang ? 'active' : '' }}">
                        <i class="nav-icon fas fa-warehouse"></i> {{-- ganti dari fa-map-marker-alt --}}
                        <p>
                            Gudang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ $isGudang ? 'display: block;' : '' }}">
                        <li class="nav-item">
                            <a href="{{ url('dashboardGudang') }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-pie"></i> {{-- lebih representatif untuk dashboard --}}
                                <p>Dashboard Gudang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('gudang') }}" class="nav-link">
                                <i class="nav-icon fas fa-building"></i> {{-- tetap relevan untuk gudang --}}
                                <p>Gudang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('areaGudang.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-th-large"></i> {{--  --}}
                                <p>Area Gudang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rak-gudang.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-layer-group"></i> {{--  --}}
                                <p>Rak Gudang</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @php
                    $isProduk = request()->is('dashboardProduk*') || request()->is('product*') || request()->is('stok*');
                @endphp
                <li class="nav-item has-treeview {{ $isProduk ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isProduk ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i> {{-- Ikon untuk grup produk secara umum --}}
                        <p>
                            Produk
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ $isProduk ? 'display: block;' : '' }}">
                        <li class="nav-item">
                            <a href="{{ url('dashboardProduk') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i> {{-- Ikon dashboard --}}
                                <p>Dashboard Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('product') }}" class="nav-link">
                                <i class="nav-icon fas fa-box"></i> {{-- Representasi satu produk --}}
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stok.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-warehouse"></i> {{-- Representasi stok/gudang --}}
                                <p>Stok Produk</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ url('stok') }}" class="nav-link">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Stok Barang</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('penerimaan-barang') }}" class="nav-link">
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p>Penerimaan Barang</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('pengeluaran-barang') }}" class="nav-link">
                        <i class="nav-icon fas fa-dolly"></i>
                        <p>Pengeluaran Barang</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('supplier') }}" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Supplier</p>
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
