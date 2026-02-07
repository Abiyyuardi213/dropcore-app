<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Area Gudang</title>
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
                            <h1 class="m-0">Tambah Area Gudang Baru</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-plus-circle"></i> Form Tambah Area Gudang</h3>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('areaGudang.store') }}" method="POST">
                                @csrf

                                <ul class="nav nav-tabs" id="areaGudangTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general"
                                            role="tab" aria-controls="general" aria-selected="true">Informasi
                                            Umum</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="details-tab" data-toggle="pill" href="#details"
                                            role="tab" aria-controls="details" aria-selected="false">Spesifikasi
                                            Area</a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3" id="areaGudangTabsContent">
                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel"
                                        aria-labelledby="general-tab">
                                        <div class="form-group">
                                            <label for="gudang_id">Gudang Induk <span
                                                    class="text-danger">*</span></label>
                                            <select name="gudang_id" id="gudang_id"
                                                class="form-control @error('gudang_id') is-invalid @enderror" required>
                                                <option value="">-- Pilih Gudang --</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}"
                                                        {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                                        {{ $gudang->nama_gudang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('gudang_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kode_area">Kode Area</label>
                                                    <input type="text" name="kode_area"
                                                        class="form-control @error('kode_area') is-invalid @enderror"
                                                        value="{{ old('kode_area') }}"
                                                        placeholder="Auto-generated (e.g., WH-001-A01)">
                                                    <small class="text-muted">Biarkan kosong untuk generate
                                                        otomatis.</small>
                                                    @error('kode_area')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama_area">Nama Area <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="nama_area"
                                                        class="form-control @error('nama_area') is-invalid @enderror"
                                                        value="{{ old('nama_area') }}" required
                                                        placeholder="Contoh: Area Frozen Food">
                                                    @error('nama_area')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jenis_area">Jenis Area</label>
                                                    <select name="jenis_area"
                                                        class="form-control @error('jenis_area') is-invalid @enderror">
                                                        <option value="">-- Pilih Jenis --</option>
                                                        <option value="Dry"
                                                            {{ old('jenis_area') == 'Dry' ? 'selected' : '' }}>Dry
                                                            (Kering)</option>
                                                        <option value="Cold Storage"
                                                            {{ old('jenis_area') == 'Cold Storage' ? 'selected' : '' }}>
                                                            Cold Storage (Pendingin)</option>
                                                        <option value="Dangerous Goods"
                                                            {{ old('jenis_area') == 'Dangerous Goods' ? 'selected' : '' }}>
                                                            Bahan Berbahaya</option>
                                                        <option value="Transit"
                                                            {{ old('jenis_area') == 'Transit' ? 'selected' : '' }}>
                                                            Transit</option>
                                                    </select>
                                                    @error('jenis_area')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pic">PIC Area</label>
                                                    <input type="text" name="pic"
                                                        class="form-control @error('pic') is-invalid @enderror"
                                                        value="{{ old('pic') }}"
                                                        placeholder="Nama Penanggung Jawab">
                                                    @error('pic')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="area_status">Status Operasional <span
                                                    class="text-danger">*</span></label>
                                            <select name="area_status"
                                                class="form-control @error('area_status') is-invalid @enderror"
                                                required>
                                                <option value="1"
                                                    {{ old('area_status') == '1' ? 'selected' : '' }}>Aktif</option>
                                                <option value="0"
                                                    {{ old('area_status') == '0' ? 'selected' : '' }}>Nonaktif
                                                    (Maintenance)</option>
                                            </select>
                                            @error('area_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Details Tab -->
                                    <div class="tab-pane fade" id="details" role="tabpanel"
                                        aria-labelledby="details-tab">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kapasitas_area">Kapasitas Area</label>
                                                    <input type="text" name="kapasitas_area"
                                                        class="form-control @error('kapasitas_area') is-invalid @enderror"
                                                        value="{{ old('kapasitas_area') }}"
                                                        placeholder="Contoh: 500 Pallet">
                                                    @error('kapasitas_area')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="suhu">Suhu (°C)</label>
                                                    <input type="text" name="suhu"
                                                        class="form-control @error('suhu') is-invalid @enderror"
                                                        value="{{ old('suhu') }}" placeholder="Contoh: -4">
                                                    @error('suhu')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kelembaban">Kelembaban (%)</label>
                                                    <input type="text" name="kelembaban"
                                                        class="form-control @error('kelembaban') is-invalid @enderror"
                                                        value="{{ old('kelembaban') }}" placeholder="Contoh: 60">
                                                    @error('kelembaban')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="keterangan">Keterangan Tambahan</label>
                                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                placeholder="Deskripsi detail area">{{ old('keterangan') }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Area
                                    </button>
                                    <a href="{{ route('areaGudang.index') }}" class="btn btn-default">Batal</a>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>
