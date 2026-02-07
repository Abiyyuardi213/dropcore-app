<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Stok Barang</title>
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
                            <h1 class="m-0">Detail Stok Barang</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Product Profile -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center mb-3">
                                        {{-- If product has an image, show it, otherwise show a placeholder --}}
                                        @if ($stok->produk->image)
                                            <img class="profile-user-img img-fluid img-circle"
                                                src="{{ asset('uploads/product/' . $stok->produk->image) }}"
                                                alt="User profile picture" style="width: 100px; height: 100px;">
                                        @else
                                            <div class="bg-primary rounded-circle mx-auto d-flex justify-content-center align-items-center"
                                                style="width: 100px; height: 100px; font-size: 40px;">
                                                <i class="fas fa-box-open text-white"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <h3 class="profile-username text-center">{{ $stok->produk->name }}</h3>
                                    <p class="text-muted text-center">{{ $stok->produk->sku }}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Kategori</b> <a
                                                class="float-right">{{ $stok->produk->category->name ?? '-' }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Merk/Brand</b> <a
                                                class="float-right">{{ $stok->produk->brand ?? '-' }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Unit (UOM)</b> <a
                                                class="float-right">{{ $stok->produk->uom->name ?? '-' }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Status Card -->
                            <div class="card card-{{ $stok->quantity > 0 ? 'success' : 'danger' }}">
                                <div class="card-header">
                                    <h3 class="card-title">Status Stok</h3>
                                </div>
                                <div class="card-body text-center">
                                    <h1>{{ number_format($stok->quantity, 0, ',', '.') }}</h1>
                                    <p class="mb-0">{{ $stok->produk->uom->name ?? 'Unit' }}</p>
                                    @if ($stok->quantity <= ($stok->produk->min_stock ?? 0))
                                        <span class="badge badge-warning mt-2">Low Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#lokasi"
                                                data-toggle="tab">Lokasi Penyimpanan</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#kondisi"
                                                data-toggle="tab">Kondisi Barang</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#log"
                                                data-toggle="tab">Informasi Lain</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <!-- Lokasi Tab -->
                                        <div class="active tab-pane" id="lokasi">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="callout callout-info">
                                                        <h5><i class="fas fa-warehouse text-info mr-2"></i> Gudang</h5>
                                                        <p class="text-lg">
                                                            {{ $stok->gudang->nama_gudang }}
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $stok->gudang->lokasi }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon bg-info"><i
                                                                class="fas fa-map-signs"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Area Gudang</span>
                                                            <span class="info-box-number">{{ $stok->area->nama_area }}
                                                                ({{ $stok->area->kode_area }})</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon bg-warning"><i
                                                                class="fas fa-dolly"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Rak Penyimpanan</span>
                                                            <span
                                                                class="info-box-number">{{ $stok->rak->kode_rak }}</span>
                                                            <small>{{ $stok->rak->posisi ?? '-' }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Kondisi Tab -->
                                        <div class="tab-pane" id="kondisi">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Kondisi Saat Ini</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $stok->kondisi->nama_kondisi ?? 'Belum ditentukan' }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Keterangan Kondisi</label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control" rows="3" readonly>{{ $stok->kondisi->keterangan ?? '-' }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Log Tab -->
                                        <div class="tab-pane" id="log">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Terakhir Diupdate</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $stok->updated_at->format('d M Y H:i:s') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Dibuat Pada</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $stok->created_at->format('d M Y H:i:s') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('stok.edit', $stok->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Stok
                                    </a>
                                    <a href="{{ route('stok.index') }}" class="btn btn-default float-right">
                                        Kembali
                                    </a>
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
