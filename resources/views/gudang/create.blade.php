<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Gudang</title>
    <link rel="icon" type="image/png" href="{{ asset('image/itats-1080.jpg') }}">
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
                            <h1 class="m-0">Tambah Gudang</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-plus-circle"></i> Form Tambah Gudang</h3>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('gudang.store') }}" method="POST">
                                @csrf

                                <ul class="nav nav-tabs" id="gudangTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general"
                                            role="tab" aria-controls="general" aria-selected="true">Informasi
                                            Umum</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="details-tab" data-toggle="pill" href="#details"
                                            role="tab" aria-controls="details" aria-selected="false">Spesifikasi</a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3" id="gudangTabsContent">
                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel"
                                        aria-labelledby="general-tab">
                                        <div class="form-group">
                                            <label for="nama_gudang">Nama Gudang <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('nama_gudang') is-invalid @enderror"
                                                name="nama_gudang" value="{{ old('nama_gudang') }}" required
                                                placeholder="Contoh: Gudang Utama Jakarta" autocomplete="off">
                                            @error('nama_gudang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jenis_gudang">Jenis Gudang <span
                                                            class="text-danger">*</span></label>
                                                    <select
                                                        class="form-control @error('jenis_gudang') is-invalid @enderror"
                                                        name="jenis_gudang" required>
                                                        <option value="">-- Pilih Jenis --</option>
                                                        <option value="Utama"
                                                            {{ old('jenis_gudang') == 'Utama' ? 'selected' : '' }}>
                                                            Gudang Utama (Pusat)</option>
                                                        <option value="Cabang"
                                                            {{ old('jenis_gudang') == 'Cabang' ? 'selected' : '' }}>
                                                            Gudang Cabang</option>
                                                        <option value="Transit"
                                                            {{ old('jenis_gudang') == 'Transit' ? 'selected' : '' }}>
                                                            Gudang Transit</option>
                                                        <option value="Produksi"
                                                            {{ old('jenis_gudang') == 'Produksi' ? 'selected' : '' }}>
                                                            Gudang Produksi</option>
                                                        <option value="Retur"
                                                            {{ old('jenis_gudang') == 'Retur' ? 'selected' : '' }}>
                                                            Gudang Retur</option>
                                                    </select>
                                                    @error('jenis_gudang')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pic">Penanggung Jawab (PIC)</label>
                                                    <input type="text"
                                                        class="form-control @error('pic') is-invalid @enderror"
                                                        name="pic" value="{{ old('pic') }}"
                                                        placeholder="Nama PIC Gudang" autocomplete="off">
                                                    @error('pic')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="lokasi">Lokasi/Alamat Lengkap <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control @error('lokasi') is-invalid @enderror" name="lokasi" required
                                                placeholder="Alamat lengkap gudang" rows="2">{{ old('lokasi') }}</textarea>
                                            @error('lokasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="gudang_status">Status Operasional <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('gudang_status') is-invalid @enderror"
                                                name="gudang_status" required>
                                                <option value="1"
                                                    {{ old('gudang_status') == '1' ? 'selected' : '' }}>Aktif</option>
                                                <option value="0"
                                                    {{ old('gudang_status') == '0' ? 'selected' : '' }}>Nonaktif
                                                    (Tutup/Maintenance)</option>
                                            </select>
                                            @error('gudang_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Details Tab -->
                                    <div class="tab-pane fade" id="details" role="tabpanel"
                                        aria-labelledby="details-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kapasitas">Kapasitas Penyimpanan</label>
                                                    <input type="text"
                                                        class="form-control @error('kapasitas') is-invalid @enderror"
                                                        name="kapasitas" value="{{ old('kapasitas') }}"
                                                        placeholder="Contoh: 5000 pallet / 1000 m3">
                                                    @error('kapasitas')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="luas_area">Luas Area</label>
                                                    <input type="text"
                                                        class="form-control @error('luas_area') is-invalid @enderror"
                                                        name="luas_area" value="{{ old('luas_area') }}"
                                                        placeholder="Contoh: 500 m2">
                                                    @error('luas_area')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="deskripsi">Deskripsi Tambahan</label>
                                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi"
                                                placeholder="Informasi tambahan mengenai gudang ini" rows="4">{{ old('deskripsi') }}</textarea>
                                            @error('deskripsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Gudang
                                    </button>
                                    <a href="{{ route('gudang.index') }}" class="btn btn-default">Batal</a>
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
