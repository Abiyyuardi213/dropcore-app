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
        @php
            $currentUserRole = Auth::user()->role ?? null;
            $canAccess = function ($perm) use ($currentUserRole) {
                return $currentUserRole ? $currentUserRole->hasPermission($perm) : false;
            };
            $canAnyMaster =
                $canAccess('master_role') ||
                $canAccess('master_hr') ||
                $canAccess('master_wilayah') ||
                $canAccess('master_mitra');
        @endphp

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false"
                role="menu">
                @if ($canAccess('dashboard'))
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ Request::is('administrator/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>Dashboard Utama</p>
                        </a>
                    </li>
                @endif

                @php
                    $isMaster =
                        request()->is('administrator/dashboard-master*') ||
                        request()->is('administrator/role*') ||
                        request()->is('administrator/user*') ||
                        request()->is('administrator/wilayah*') ||
                        request()->is('administrator/provinsi*') ||
                        request()->is('administrator/kota*') ||
                        request()->is('administrator/kecamatan*') ||
                        request()->is('administrator/kelurahan*') ||
                        request()->is('administrator/supplier*') ||
                        request()->is('administrator/kondisi-barang*') ||
                        request()->is('administrator/divisi*') ||
                        request()->is('administrator/jasa-pengiriman*') ||
                        request()->is('administrator/metode-pembayaran*') ||
                        request()->is('administrator/jabatan*');
                @endphp

                @if ($canAnyMaster)
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
                                <a href="{{ route('dashboard-master') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/dashboard-master*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-warehouse"></i>
                                    <p>Dashboard Master</p>
                                </a>
                            </li>

                            @if ($canAccess('master_role'))
                                <li class="nav-item">
                                    <a href="{{ route('role.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/role*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user-shield"></i>
                                        <p>Master Peran</p>
                                    </a>
                                </li>
                            @endif

                            @if ($canAccess('master_hr'))
                                <li class="nav-item">
                                    <a href="{{ route('divisi.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/divisi*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-sitemap"></i>
                                        <p>Master Divisi</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('jabatan.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/jabatan*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-id-badge"></i>
                                        <p>Master Jabatan</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('user.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/user*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-users-cog"></i>
                                        <p>Master Pengguna</p>
                                    </a>
                                </li>
                            @endif

                            @if ($canAccess('master_wilayah'))
                                <li class="nav-item">
                                    <a href="{{ route('wilayah.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/wilayah*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-globe"></i>
                                        <p>Master Wilayah</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('provinsi.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/provinsi*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-map"></i>
                                        <p>Master Provinsi</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('kota.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/kota*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-city"></i>
                                        <p>Master Kota</p>
                                    </a>
                                </li>
                            @endif

                            @if ($canAccess('master_mitra'))
                                <li class="nav-item">
                                    <a href="{{ route('supplier.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/supplier*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-truck"></i>
                                        <p>Master Supplier</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('kondisi-barang.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/kondisi-barang*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-box-open"></i>
                                        <p>Master Kondisi Barang</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('jasa-pengiriman.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/jasa-pengiriman*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-shipping-fast"></i>
                                        <p>Jasa Pengiriman</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('metode-pembayaran.index') }}"
                                        class="nav-link text-sm pl-4 {{ Request::is('administrator/metode-pembayaran*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-money-check-alt"></i>
                                        <p>Metode Pembayaran</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @php
                    $isGudang =
                        request()->is('administrator/dashboardGudang*') ||
                        request()->is('administrator/gudang*') ||
                        request()->is('administrator/areaGudang*') ||
                        request()->is('administrator/rak-gudang*');
                @endphp

                @if ($canAccess('gudang'))
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
                                <a href="{{ route('dashboardGudang') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/dashboardGudang*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>Dashboard Gudang</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('gudang.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/gudang*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>Gudang</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('areaGudang.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/areaGudang*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-th-large"></i>
                                    <p>Area Gudang</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('rak-gudang.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/rak-gudang*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-layer-group"></i>
                                    <p>Rak Gudang</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @php
                    $isProduk =
                        request()->is('administrator/dashboardProduk*') ||
                        request()->is('administrator/product*') ||
                        request()->is('administrator/category*') ||
                        request()->is('administrator/stok*');
                @endphp

                @if ($canAccess('produk'))
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
                                <a href="{{ route('dashboardProduk') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/dashboardProduk*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard Produk</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('product.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/product*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-box"></i>
                                    <p>Produk</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('category.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/category*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tags"></i>
                                    <p>Kategori Produk</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('stok.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/stok*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-warehouse"></i>
                                    <p>Stok Produk</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @php
                    $isKeuangan =
                        request()->is('administrator/dashboard-keuangan*') ||
                        request()->is('administrator/kas-pusat*') ||
                        request()->is('administrator/sumber-keuangan*') ||
                        request()->is('administrator/keuangan*');
                @endphp

                @if ($canAccess('keuangan'))
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
                                <a href="{{ route('dashboard-keuangan') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/dashboard-keuangan*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-chart-line"></i>
                                    <p>Dashboard Keuangan</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('kas-pusat.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/kas-pusat*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-coins"></i>
                                    <p>Kas Pusat</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('sumber-keuangan.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/sumber-keuangan*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-money-bill-wave"></i>
                                    <p>Sumber Keuangan</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('keuangan.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/keuangan*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>Keuangan</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if ($canAccess('orders'))
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}"
                            class="nav-link {{ Request::is('administrator/orders*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Pesanan Distributor</p>
                        </a>
                    </li>
                @endif

                @if ($canAccess('distributor'))
                    <li class="nav-item">
                        <a href="{{ route('distributor.index') }}"
                            class="nav-link {{ Request::is('administrator/distributor*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck-moving"></i>
                            <p>Distributor</p>
                        </a>
                    </li>
                @endif

                @if ($canAccess('mutasi_stok'))
                    <li class="nav-item">
                        <a href="{{ route('mutasi-stok.index') }}"
                            class="nav-link {{ Request::is('administrator/mutasi-stok*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Mutasi Stok</p>
                        </a>
                    </li>
                @endif

                @if ($canAccess('stock_opname'))
                    <li class="nav-item">
                        <a href="{{ route('stock-opname.index') }}"
                            class="nav-link {{ Request::is('administrator/stock-opname*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>Stock Opname</p>
                        </a>
                    </li>
                @endif

                @if ($canAccess('riwayat'))
                    <li class="nav-item">
                        <a href="{{ route('riwayat-aktivitas-produk.index') }}"
                            class="nav-link {{ Request::is('administrator/riwayat-aktivitas-produk*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Aktivitas Produk</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('riwayat-log.index') }}"
                            class="nav-link {{ Request::is('administrator/riwayat-logs*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>Log Aktivitas</p>
                        </a>
                    </li>
                @endif

                @php
                    $isTransaksi =
                        request()->is('administrator/dashboard-transaksi') ||
                        request()->is('administrator/penerimaan-barang*') ||
                        request()->is('administrator/pengeluaran-barang*');
                @endphp

                @if ($canAccess('transaksi'))
                    <li class="nav-item has-treeview {{ $isTransaksi ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isTransaksi ? 'active' : '' }} font-weight-bold">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>
                                Transaksi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview" style="{{ $isTransaksi ? 'display: block;' : '' }}">
                            <li class="nav-item">
                                <a href="{{ route('dashboard-transaksi') }}" class="nav-link text-sm pl-4 {{ Request::is('administrator/dashboard-transaksi') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line nav-icon"></i><p>Dashboard Transaksi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('penerimaan-barang.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/penerimaan-barang*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-truck-loading"></i>
                                    <p>Penerimaan Barang</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('pengeluaran-barang.index') }}"
                                    class="nav-link text-sm pl-4 {{ Request::is('administrator/pengeluaran-barang*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-dolly"></i>
                                    <p>Pengeluaran Barang</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if ($canAccess('laporan'))
                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}"
                            class="nav-link {{ Request::is('administrator/laporan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>

<style>
    /* Styling khusus untuk sub-menu aktif agar berwarna kuning */
    .nav-sidebar .nav-treeview>.nav-item>.nav-link.active {
        background-color: #f39c12 !important;
        color: #1f2d3d !important;
        font-weight: bold;
    }
</style>

<script>
    /**
     * Script untuk menjaga posisi scroll sidebar agar tidak reset ke atas saat reload/navigasi
     */
    document.addEventListener("DOMContentLoaded", function() {
        var sidebar = document.querySelector('.sidebar');

        if (sidebar) {
            // Check if there's a saved scroll position
            var scrollPos = localStorage.getItem('sidebarScrollPos');
            if (scrollPos) {
                sidebar.scrollTop = scrollPos;
            }

            // Save scroll position before unloading the page
            window.addEventListener('beforeunload', function() {
                localStorage.setItem('sidebarScrollPos', sidebar.scrollTop);
            });
        }
    });
</script>
