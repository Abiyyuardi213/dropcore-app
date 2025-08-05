<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Stok Produk</title>
    <link rel="icon" type="image/png" href="{{ asset('image/itats-1080.jpg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
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
                        <h1 class="m-0">Tambah Stok Produk</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-plus-circle"></i> Form Tambah Stok Produk</h3>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('stok.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="produk_id">Gudang</label>
                            <select name="produk_id" id="produk_id" class="form-control @error('produk_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produks as $produk)
                                    <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                        {{ $produk->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="gudang_id">Gudang</label>
                            <select name="gudang_id" id="gudang_id" class="form-control @error('gudang_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}" {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                        {{ $gudang->nama_gudang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gudang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="area_id">Area Gudang</label>
                            <select name="area_id" id="area_id" class="form-control @error('area_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Area Gudang --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->kode_area }}
                                    </option>
                                @endforeach
                            </select>
                            @error('area_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="rak_id">Rak Gudang</label>
                            <select name="rak_id" id="rak_id" class="form-control @error('rak_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Rak Gudang --</option>
                                @foreach($raks as $rak)
                                    <option value="{{ $rak->id }}" {{ old('rak_id') == $rak->id ? 'selected' : '' }}>
                                        {{ $rak->kode_rak }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rak_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="quantity">Jumlah Stok</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity') }}">
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="kondisi_id">Kondisi Barang</label>
                            <select name="kondisi_id" class="form-control">
                                <option value="">-- Pilih Kondisi --</option>
                                @foreach($kondisis as $k)
                                    <option value="{{ $k->id }}"
                                        {{ (old('kondisi_id', $stok->kondisi_id ?? '') == $k->id) ? 'selected' : '' }}>
                                        {{ $k->nama_kondisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('stok.index') }}" class="btn btn-secondary">Batal</a>
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
