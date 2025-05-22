<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
        }
        .profile-img {
            width: 100%;
            max-width: 180px;
            height: auto;
            object-fit: cover;
            border: 3px solid #dee2e6;
            border-radius: 10px;
        }
        .table th {
            background-color: #f8f9fa;
        }
        @media (max-width: 768px) {
            .card {
                margin: 0 10px;
            }
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
                        <div class="col-sm-12">
                            <h1 class="m-0">Detail Produk</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid d-flex justify-content-center">
                    <div class="card shadow-lg w-100" style="max-width: 800px;">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title mb-0">Informasi Produk</h3>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4 text-center mb-3">
                                    <img src="{{ $product->image ? asset('uploads/product/' . $product->image) : asset('image/default-avatar.png') }}"
                                        class="profile-img img-fluid rounded" alt="Foto Produk">
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th style="width: 40%;">ID Produk</th>
                                                <td>{{ $product->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>SKU Produk</th>
                                                <td>{{ $product->sku }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <td>{{ $product->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Deskripsi Produk</th>
                                                <td>{{ $product->description }}</td>
                                            </tr>
                                            <tr>
                                                <th>Harga Produk</th>
                                                <td>{{ $product->price }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kategori Produk</th>
                                                <td>{{ $product->category->category_name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Unit Satuann</th>
                                                <td>{{ $product->uom->name ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('product.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    @include('services.logoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
