<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Dashboard Produk</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
        }
    </style>
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
                            <h1 class="m-0">Dashboard</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('dashboardGudang') }}">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard Produk</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Info Boxes -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalProduk ?? 0 }}</h3>
                                    <p>Total Produk Tersedia</p>
                                </div>
                                <div class="icon"><i class="fas fa-box-open"></i></div>
                                <a href="{{ url('admin/product') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ number_format($totalStok ?? 0) }}</h3>
                                    <p>Total Stok Keseluruhan</p>
                                </div>
                                <div class="icon"><i class="fas fa-cubes"></i></div>
                                <a href="{{ url('admin/stok') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="row">
                        <!-- Left Col: Products by Category -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Produk per Kategori</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Nama Kategori</th>
                                                <th>Jumlah Produk</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produkPerKategori as $index => $cat)
                                                <tr>
                                                    <td>{{ $index + 1 }}.</td>
                                                    <td>{{ $cat->category_name }}</td>
                                                    <td><span class="badge badge-info">{{ $cat->products_count }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Expensive Products -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Top 5 Produk Termahal</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expensiveProducts as $p)
                                                <tr>
                                                    <td>{{ $p->name }}</td>
                                                    <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Right Col: Low Stock Alerts -->
                        <div class="col-md-6">
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Peringatan Stok Rendah</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                        @forelse($lowStockProducts as $product)
                                            <li class="item">
                                                <div class="product-info ml-0">
                                                    <a href="javascript:void(0)"
                                                        class="product-title">{{ $product->name }}
                                                        <span
                                                            class="badge badge-danger float-right">{{ $product->total_stock }}
                                                            Unit</span>
                                                    </a>
                                                    <span class="product-description">
                                                        Min Stock: {{ $product->min_stock }} | SKU:
                                                        {{ $product->sku }}
                                                    </span>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="item p-3 text-center text-muted">Aman! Tidak ada produk dengan
                                                stok rendah.</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ url('admin/product') }}" class="uppercase">Lihat Semua Produk</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    @include('services.ToastModal')
    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="{{ asset('resources/js/ToastScript.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('[data-widget="treeview"]').each(function() {
                AdminLTE.Treeview._jQueryInterface.call($(this));
            });
        });
    </script>
</body>

</html>
