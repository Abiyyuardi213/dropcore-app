<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stok Produk</title>
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
            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Edit Stok Produk</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit"></i> Form Edit Stok Produk</h3>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('stok.update', $stok->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Kolom Kiri: Produk & Jumlah -->
                                    <div class="col-md-6">
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">Informasi Barang</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="produk_id">Produk <span
                                                            class="text-danger">*</span></label>
                                                    <select name="produk_id" id="produk_id"
                                                        class="form-control select2 @error('produk_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Produk --</option>
                                                        @foreach ($produks as $produk)
                                                            <option value="{{ $produk->id }}"
                                                                {{ old('produk_id', $stok->produk_id) == $produk->id ? 'selected' : '' }}>
                                                                {{ $produk->name }} (SKU: {{ $produk->sku }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('produk_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="quantity">Jumlah Stok <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number"
                                                        class="form-control @error('quantity') is-invalid @enderror"
                                                        name="quantity" value="{{ old('quantity', $stok->quantity) }}"
                                                        min="1" required>
                                                    @error('quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="kondisi_id">Kondisi Barang</label>
                                                    <select name="kondisi_id"
                                                        class="form-control @error('kondisi_id') is-invalid @enderror">
                                                        <option value="">-- Pilih Kondisi --</option>
                                                        @foreach ($kondisis as $k)
                                                            <option value="{{ $k->id }}"
                                                                {{ old('kondisi_id', $stok->kondisi_id) == $k->id ? 'selected' : '' }}>
                                                                {{ $k->nama_kondisi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('kondisi_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan: Lokasi -->
                                    <div class="col-md-6">
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">Lokasi Penyimpanan</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="gudang_id">Gudang <span
                                                            class="text-danger">*</span></label>
                                                    <select name="gudang_id" id="gudang_id"
                                                        class="form-control @error('gudang_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Gudang --</option>
                                                        @foreach ($gudangs as $gudang)
                                                            <option value="{{ $gudang->id }}"
                                                                {{ old('gudang_id', $stok->gudang_id) == $gudang->id ? 'selected' : '' }}>
                                                                {{ $gudang->nama_gudang }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('gudang_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="area_id">Area Gudang <span
                                                            class="text-danger">*</span></label>
                                                    <select name="area_id" id="area_id"
                                                        class="form-control @error('area_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Area Gudang --</option>
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->id }}"
                                                                {{ old('area_id', $stok->area_id) == $area->id ? 'selected' : '' }}>
                                                                {{ $area->kode_area }} (Area {{ $area->nama_area }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('area_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="rak_id">Rak Gudang <span
                                                            class="text-danger">*</span></label>
                                                    <select name="rak_id" id="rak_id"
                                                        class="form-control @error('rak_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Rak Gudang --</option>
                                                        @foreach ($raks as $rak)
                                                            <option value="{{ $rak->id }}"
                                                                {{ old('rak_id', $stok->rak_id) == $rak->id ? 'selected' : '' }}>
                                                                {{ $rak->kode_rak }} (Posisi:
                                                                {{ $rak->posisi ?? '-' }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('rak_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-white text-right">
                                    <a href="{{ route('stok.index') }}" class="btn btn-secondary mr-2">Batal</a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save"></i> Perbarui
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    @include('services.logoutModal')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>
