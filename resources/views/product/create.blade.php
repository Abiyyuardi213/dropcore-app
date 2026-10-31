<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
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
                            <h1 class="m-0">Tambah Data Produk</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Form Tambah Produk</h3>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            @if($errors->any())<div class="alert alert-danger" role="alert">Periksa kembali isian yang ditandai pada form.</div>@endif

                            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Nama Produk <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name') }}" required
                                                        placeholder="Contoh: Kabel Fiber Optik 100m">
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
                                                        name="merk" value="{{ old('merk') }}"
                                                        placeholder="Contoh: Supreme">
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
                                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                                                {{ old('uom_id') == $uom->id ? 'selected' : '' }}>
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
                                            <label for="harga_jual">Harga Jual (Rp) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('harga_jual') is-invalid @enderror"
                                                id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" required min="0" step="0.01"
                                                placeholder="0">
                                            @error('harga_jual')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="harga_beli">Harga Beli (Rp)</label>
                                            <input type="number" id="harga_beli" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror"
                                                min="0" step="0.01" value="{{ old('harga_beli') }}" placeholder="Isi harga beli dari supplier">
                                            <small class="text-muted">Harga modal/acuan per satuan untuk transaksi baru. Kosong berarti belum diketahui; isi 0 hanya jika barang diperoleh tanpa biaya. Harga pembelian aktual dicatat saat penerimaan barang.</small>
                                            @error('harga_beli')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Deskripsi Produk</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3"
                                                placeholder="Deskripsi lengkap produk...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text"
                                                class="form-control @error('sku') is-invalid @enderror" name="sku"
                                                value="{{ old('sku') }}"
                                                placeholder="Biarkan kosong untuk generate otomatis">
                                            <small class="text-muted">Kode unik untuk identifikasi stok.</small>
                                            @error('sku')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="weight">Berat (Kg)</label>
                                                    <input type="number" step="0.01"
                                                        class="form-control @error('weight') is-invalid @enderror"
                                                        name="weight" value="{{ old('weight') }}"
                                                        placeholder="0.00">
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
                                                        name="min_stock" value="{{ old('min_stock', 0) }}"
                                                        min="0" step="1">
                                                    <small class="text-muted">Sistem akan memberi peringatan jika stok
                                                        di bawah angka ini.</small>
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
                                                        name="max_stock" value="{{ old('max_stock') }}"
                                                        min="0" step="1">
                                                    <small class="text-muted">Opsional, untuk manajemen kapasitas
                                                        gudang.</small>
                                                    @error('max_stock')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 mt-3 mt-lg-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="image">Upload Gambar</label>
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            class="custom-file-input @error('image') is-invalid @enderror"
                                                            id="image" name="image" accept="image/jpeg,image/png,image/webp"
                                                            onchange="previewImage()">
                                                        <label class="custom-file-label" for="image">Pilih
                                                            file</label>
                                                    </div>
                                                    <small class="text-muted">JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.</small>
                                                    @error('image')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mt-3 text-center bg-light rounded border p-3">
                                                    <img id="imagePreview"
                                                        src="{{ asset('image/logo-dropcore.jpg') }}"
                                                        alt="Preview gambar produk" class="img-fluid rounded"
                                                        style="width: 100%; max-height: 300px; object-fit: contain;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-white mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan
                                        Produk</button>
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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // bs-custom-file-input for filename display
            // We don't have bs-custom-file-input included in imports, so manual handling or just valid logic
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                                                $(this).next('.custom-file-label').addClass("selected").text(fileName);
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
