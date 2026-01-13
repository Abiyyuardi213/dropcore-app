<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Rak Gudang</title>
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
                            <h1 class="m-0">Tambah Rak Gudang Baru</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-plus-circle"></i> Form Tambah Rak Gudang</h3>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('rak-gudang.store') }}" method="POST">
                                @csrf

                                <ul class="nav nav-tabs" id="rakGudangTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general"
                                            role="tab" aria-controls="general" aria-selected="true">Informasi
                                            Umum</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="details-tab" data-toggle="pill" href="#details"
                                            role="tab" aria-controls="details" aria-selected="false">Spesifikasi
                                            Rak</a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3" id="rakGudangTabsContent">
                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel"
                                        aria-labelledby="general-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gudang_id">Gudang <span
                                                            class="text-danger">*</span></label>
                                                    <select name="gudang_id" id="gudang_id"
                                                        class="form-control @error('gudang_id') is-invalid @enderror"
                                                        required>
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
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="area_id">Area Gudang <span
                                                            class="text-danger">*</span></label>
                                                    <select name="area_id" id="area_id"
                                                        class="form-control @error('area_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Area Gudang --</option>
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->id }}"
                                                                {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                                                {{ $area->kode_area }} - {{ $area->nama_area }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('area_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kode_rak">Kode Rak <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="kode_rak"
                                                        class="form-control @error('kode_rak') is-invalid @enderror"
                                                        value="{{ old('kode_rak') }}" required
                                                        placeholder="Contoh: R-A1-01">
                                                    @error('kode_rak')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="posisi">Posisi Rak</label>
                                                    <input type="text" name="posisi"
                                                        class="form-control @error('posisi') is-invalid @enderror"
                                                        value="{{ old('posisi') }}"
                                                        placeholder="Contoh: Baris 1, Level 2">
                                                    @error('posisi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="rak_status">Status Rak <span
                                                    class="text-danger">*</span></label>
                                            <select name="rak_status"
                                                class="form-control @error('rak_status') is-invalid @enderror"
                                                required>
                                                <option value="1"
                                                    {{ old('rak_status') == '1' ? 'selected' : '' }}>Aktif</option>
                                                <option value="0"
                                                    {{ old('rak_status') == '0' ? 'selected' : '' }}>Nonaktif
                                                    (Maintenance)</option>
                                            </select>
                                            @error('rak_status')
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
                                                    <label for="jenis_rak">Jenis Rak</label>
                                                    <select name="jenis_rak"
                                                        class="form-control @error('jenis_rak') is-invalid @enderror">
                                                        <option value="">-- Pilih Jenis --</option>
                                                        <option value="Heavy Duty"
                                                            {{ old('jenis_rak') == 'Heavy Duty' ? 'selected' : '' }}>
                                                            Heavy Duty</option>
                                                        <option value="Medium Duty"
                                                            {{ old('jenis_rak') == 'Medium Duty' ? 'selected' : '' }}>
                                                            Medium Duty</option>
                                                        <option value="Light Duty"
                                                            {{ old('jenis_rak') == 'Light Duty' ? 'selected' : '' }}>
                                                            Light Duty</option>
                                                        <option value="Pallet Racking"
                                                            {{ old('jenis_rak') == 'Pallet Racking' ? 'selected' : '' }}>
                                                            Pallet Racking</option>
                                                        <option value="Cantilever"
                                                            {{ old('jenis_rak') == 'Cantilever' ? 'selected' : '' }}>
                                                            Cantilever</option>
                                                    </select>
                                                    @error('jenis_rak')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bahan_rak">Bahan Material</label>
                                                    <input type="text" name="bahan_rak"
                                                        class="form-control @error('bahan_rak') is-invalid @enderror"
                                                        value="{{ old('bahan_rak') }}"
                                                        placeholder="Contoh: Besi Baja">
                                                    @error('bahan_rak')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kapasitas_max">Kapasitas Maksimum</label>
                                                    <input type="text" name="kapasitas_max"
                                                        class="form-control @error('kapasitas_max') is-invalid @enderror"
                                                        value="{{ old('kapasitas_max') }}"
                                                        placeholder="Contoh: 2000 kg">
                                                    @error('kapasitas_max')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dimensi">Dimensi (PxLxT)</label>
                                                    <input type="text" name="dimensi"
                                                        class="form-control @error('dimensi') is-invalid @enderror"
                                                        value="{{ old('dimensi') }}"
                                                        placeholder="Contoh: 200x100x300 cm">
                                                    @error('dimensi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="keterangan">Keterangan Tambahan</label>
                                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                placeholder="Deskripsi kondiri rak atau catatan lainnya">{{ old('keterangan') }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Rak
                                    </button>
                                    <a href="{{ route('rak-gudang.index') }}" class="btn btn-default">Batal</a>
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
