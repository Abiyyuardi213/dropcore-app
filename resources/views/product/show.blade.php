<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
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
                            <h1 class="m-0">Detail Produk</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center mb-3">
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="{{ $product->image ? asset('uploads/product/' . $product->image) : asset('image/logo-dropcore.jpg') }}"
                                            alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>

                                    <h3 class="profile-username text-center">{{ $product->name }}</h3>
                                    <p class="text-muted text-center">{{ $product->sku }}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Harga</b> <a class="float-right text-success">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Kategori</b> <a
                                                class="float-right">{{ $product->category->name ?? ($product->category->category_name ?? '-') }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Satuan</b> <a class="float-right">{{ $product->uom->name ?? '-' }}</a>
                                        </li>
                                    </ul>

                                    <a href="{{ route('product.edit', $product->id) }}"
                                        class="btn btn-warning btn-block"><b>Edit Produk</b></a>
                                    <a href="{{ route('product.index') }}"
                                        class="btn btn-default btn-block"><b>Kembali</b></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#info"
                                                data-toggle="tab">Informasi Detail</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#specs"
                                                data-toggle="tab">Inventaris & Spesifikasi</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <!-- Info Tab -->
                                        <div class="active tab-pane" id="info">
                                            <strong><i class="fas fa-tag mr-1"></i> Merk / Brand</strong>
                                            <p class="text-muted">{{ $product->merk ?? '-' }}</p>
                                            <hr>

                                            <strong><i class="fas fa-align-left mr-1"></i> Deskripsi</strong>
                                            <p class="text-muted">
                                                {!! nl2br(e($product->description ?? 'Tidak ada deskripsi.')) !!}
                                            </p>
                                        </div>

                                        <!-- Specs Tab -->
                                        <div class="tab-pane" id="specs">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong><i class="fas fa-ruler-combined mr-1"></i> Dimensi</strong>
                                                    <p class="text-muted">{{ $product->dimensi ?? '-' }}</p>
                                                    <hr>

                                                    <strong><i class="fas fa-weight-hanging mr-1"></i> Berat</strong>
                                                    <p class="text-muted">
                                                        @php $displayWeight = $product->weight > 0 ? $product->weight : $product->berat; @endphp
                                                        {{ $displayWeight ? $displayWeight . ' Kg' : '-' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong><i class="fas fa-exclamation-triangle mr-1"></i> Min. Stock
                                                        Alert</strong>
                                                    <p class="text-muted text-danger">{{ $product->min_stock }}
                                                        {{ $product->uom->name ?? 'Unit' }}</p>
                                                    <hr>

                                                    <strong><i class="fas fa-warehouse mr-1"></i> Max. Stock
                                                        Cap</strong>
                                                    <p class="text-muted">
                                                        {{ $product->max_stock ? $product->max_stock . ' ' . ($product->uom->name ?? 'Unit') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
