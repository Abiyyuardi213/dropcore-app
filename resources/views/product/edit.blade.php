<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Produk</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
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
                            <h1 class="m-0">Ubah Data Produk</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-warning card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="info-tab" data-toggle="pill" href="#info"
                                        role="tab" aria-controls="info" aria-selected="true">Informasi Produk</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="specs-tab" data-toggle="pill" href="#specs" role="tab"
                                        aria-controls="specs" aria-selected="false">Inventaris & Spesifikasi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="image-tab" data-toggle="pill" href="#image-sect"
                                        role="tab" aria-controls="image-sect" aria-selected="false">Gambar
                                        Produk</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('product.update', $product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="tab-content" id="productTabsContent">

                                    <!-- Tab Informasi -->
                                    <div class="tab-pane fade show active" id="info" role="tabpanel"
                                        aria-labelledby="info-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Nama Produk <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name', $product->name) }}"
                                                        required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="merk">Merk / Brand</label>
                                                    <input type="text"
                                                        class="form-control @error('merk') is-invalid @enderror"
                                                        name="merk" value="{{ old('merk', $product->merk) }}">
                                                    @error('merk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="category_id">Kategori <span
                                                            class="text-danger">*</span></label>
                                                    <select name="category_id" id="category_id"
                                                        class="form-control @error('category_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Kategori --</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name ?? $category->category_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="uom_id">Satuan (UOM) <span
                                                            class="text-danger">*</span></label>
                                                    <select name="uom_id" id="uom_id"
                                                        class="form-control @error('uom_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Satuan --</option>
                                                        @foreach ($uoms as $uom)
                                                            <option value="{{ $uom->id }}"
                                                                {{ old('uom_id', $product->uom_id) == $uom->id ? 'selected' : '' }}>
                                                                {{ $uom->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('uom_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="price">Harga Satuan (Rp) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('price') is-invalid @enderror"
                                                name="price" value="{{ old('price', $product->price) }}" required
                                                min="0">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Deskripsi Produk</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Tab Spesifikasi -->
                                    <div class="tab-pane fade" id="specs" role="tabpanel"
                                        aria-labelledby="specs-tab">
                                        <div class="form-group">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text"
                                                class="form-control @error('sku') is-invalid @enderror" name="sku"
                                                value="{{ old('sku', $product->sku) }}">
                                            <small class="text-muted">Kode unik untuk identifikasi stok.</small>
                                            @error('sku')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dimensi">Dimensi (PxLxT)</label>
                                                    <input type="text"
                                                        class="form-control @error('dimensi') is-invalid @enderror"
                                                        name="dimensi"
                                                        value="{{ old('dimensi', $product->dimensi) }}">
                                                    @error('dimensi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="weight">Berat (Kg)</label>
                                                    <input type="number" step="0.01"
                                                        class="form-control @error('weight') is-invalid @enderror"
                                                        name="weight" value="{{ old('weight', $product->weight) }}">
                                                    @error('weight')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="min_stock">Minimum Stok Alert</label>
                                                    <input type="number"
                                                        class="form-control @error('min_stock') is-invalid @enderror"
                                                        name="min_stock"
                                                        value="{{ old('min_stock', $product->min_stock) }}"
                                                        min="0">
                                                    @error('min_stock')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="max_stock">Maksimum Kapasitas</label>
                                                    <input type="number"
                                                        class="form-control @error('max_stock') is-invalid @enderror"
                                                        name="max_stock"
                                                        value="{{ old('max_stock', $product->max_stock) }}"
                                                        min="0">
                                                    @error('max_stock')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Gambar -->
                                    <div class="tab-pane fade" id="image-sect" role="tabpanel"
                                        aria-labelledby="image-tab">
                                        <div class="row justify-content-center">
                                            <div class="col-md-6 text-center">
                                                <div class="form-group">
                                                    <label for="image">Upload Gambar Baru</label>
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            class="custom-file-input @error('image') is-invalid @enderror"
                                                            id="image" name="image" accept="image/*"
                                                            onchange="previewImage()">
                                                        <label class="custom-file-label" for="image">Pilih
                                                            file</label>
                                                    </div>
                                                    @error('image')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mt-3">
                                                    <img id="imagePreview"
                                                        src="{{ $product->image ? asset('uploads/product/' . $product->image) : 'https://via.placeholder.com/300?text=No+Image' }}"
                                                        alt="Preview" class="img-fluid rounded border shadow-sm"
                                                        style="max-height: 300px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-white mt-3">
                                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i>
                                        Perbarui Produk</button>
                                    <a href="{{ route('product.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
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

    <script>
        $(document).ready(function() {
            // Custom file input label
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });

        function previewImage() {
            const image = document.getElementById('imagePreview');
            const input = document.getElementById('image');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
