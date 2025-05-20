<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Produk</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>
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
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit"></i> Form Ubah Data Produk</h3>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <!-- Kiri -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sku">SKU Barang</label>
                                            <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Nama Barang</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" required>
                                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Deskripsi</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description', $product->description) }}</textarea>
                                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="stock">Jumlah Stok</label>
                                            <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $product->stock) }}">
                                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Tengah -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price">Harga Barang</label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}">
                                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="category_id">Role</label>
                                            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                                <option value="">-- Pilih Role --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="uom_id">Unit Satuan Barang</label>
                                            <select name="uom_id" id="uom_id" class="form-control @error('uom_id') is-invalid @enderror">
                                                <option value="">-- Pilih Satuan --</option>
                                                @foreach($uoms as $uom)
                                                    <option value="{{ $uom->id }}" {{ old('uom_id', $product->uom_id) == $uom->id ? 'selected' : '' }}>{{ $uom->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('uom_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Kanan: Foto -->
                                    <div class="col-md-4 text-center">
                                        <div class="form-group">
                                            <label for="image">Foto Produk</label>
                                            <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror" accept="image/*">
                                            @error('image')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div style="width: 300px; height: 300px; border: 2px dashed #ccc; margin: auto; display: flex; align-items: center; justify-content: center;">
                                            <img id="preview" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=Preview' }}" class="img-fluid rounded" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                        <input type="hidden" name="cropped_image" id="cropped_image">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Simpan Perubahan</button>
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

    @include('services.logoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-widget="treeview"]').Treeview('init');
        });

        let cropper;
        const image = document.getElementById('preview');
        const input = document.getElementById('image');

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                image.src = reader.result;

                if (cropper) cropper.destroy();
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    crop(event) {
                        const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
                        canvas.toBlob((blob) => {
                            const formData = new FormData();
                            formData.append('cropped_image', blob);
                        });
                    }
                });
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>
</html>
