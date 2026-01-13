<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Supplier</title>
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
                            <h1 class="m-0">Tambah Supplier Baru</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="supplierTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general"
                                        role="tab" aria-controls="general" aria-selected="true">Informasi Umum</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-toggle="pill" href="#contact"
                                        role="tab" aria-controls="contact" aria-selected="false">Kontak</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="address-tab" data-toggle="pill" href="#address"
                                        role="tab" aria-controls="address" aria-selected="false">Alamat</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content" id="supplierTabsContent">

                                    <!-- Tab Informasi Umum -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel"
                                        aria-labelledby="general-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kode_supplier">Kode Supplier</label>
                                                    <input type="text" class="form-control" name="kode_supplier"
                                                        value="{{ old('kode_supplier') }}"
                                                        placeholder="Auto-generated (Leave blank)">
                                                    <small class="text-muted">Biarkan kosong untuk generate
                                                        otomatis.</small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama_supplier">Nama Supplier <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('nama_supplier') is-invalid @enderror"
                                                        name="nama_supplier" value="{{ old('nama_supplier') }}"
                                                        required>
                                                    @error('nama_supplier')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="tipe_supplier">Tipe Supplier</label>
                                                    <input type="text"
                                                        class="form-control @error('tipe_supplier') is-invalid @enderror"
                                                        name="tipe_supplier" value="{{ old('tipe_supplier') }}"
                                                        placeholder="Contoh: Distributor, Principal">
                                                </div>
                                                <div class="form-group">
                                                    <label for="website">Website</label>
                                                    <input type="url"
                                                        class="form-control @error('website') is-invalid @enderror"
                                                        name="website" value="{{ old('website') }}"
                                                        placeholder="https://example.com">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="logo">Logo Supplier</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                            id="logo" name="logo" accept="image/*">
                                                        <label class="custom-file-label" for="logo">Pilih
                                                            file</label>
                                                    </div>
                                                    <small class="text-muted">Max 2MB. Format: JPG, PNG.</small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="keterangan">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="4">{{ old('keterangan') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="status" name="status" value="1" checked>
                                                        <label class="custom-control-label" for="status">Status
                                                            Aktif</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Kontak -->
                                    <div class="tab-pane fade" id="contact" role="tabpanel"
                                        aria-labelledby="contact-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="penanggung_jawab">Penanggung Jawab (Contact
                                                        Person)</label>
                                                    <input type="text"
                                                        class="form-control @error('penanggung_jawab') is-invalid @enderror"
                                                        name="penanggung_jawab" value="{{ old('penanggung_jawab') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_telepon">Nomor Telepon</label>
                                                    <input type="text"
                                                        class="form-control @error('no_telepon') is-invalid @enderror"
                                                        name="no_telepon" value="{{ old('no_telepon') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Alamat -->
                                    <div class="tab-pane fade" id="address" role="tabpanel"
                                        aria-labelledby="address-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wilayah_id">Wilayah</label>
                                                    <select name="wilayah_id" id="wilayah_id"
                                                        class="form-control @error('wilayah_id') is-invalid @enderror">
                                                        <option value="">-- Pilih Wilayah --</option>
                                                        @foreach ($wilayahs as $wilayah)
                                                            <option value="{{ $wilayah->id }}"
                                                                {{ old('wilayah_id') == $wilayah->id ? 'selected' : '' }}>
                                                                {{ $wilayah->negara }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="provinsi_id">Provinsi</label>
                                                    <select name="provinsi_id" id="provinsi_id"
                                                        class="form-control @error('provinsi_id') is-invalid @enderror">
                                                        <option value="">-- Pilih Provinsi --</option>
                                                        @foreach ($provinsis as $provinsi)
                                                            <option value="{{ $provinsi->id }}"
                                                                {{ old('provinsi_id') == $provinsi->id ? 'selected' : '' }}>
                                                                {{ $provinsi->provinsi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kota_id">Kota</label>
                                                    <select name="kota_id" id="kota_id"
                                                        class="form-control @error('kota_id') is-invalid @enderror">
                                                        <option value="">-- Pilih Kota --</option>
                                                        @foreach ($kotas as $kota)
                                                            <option value="{{ $kota->id }}"
                                                                {{ old('kota_id') == $kota->id ? 'selected' : '' }}>
                                                                {{ $kota->kota }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="kecamatan_id">Kecamatan</label>
                                                    <select name="kecamatan_id" id="kecamatan_id"
                                                        class="form-control @error('kecamatan_id') is-invalid @enderror">
                                                        <option value="">-- Pilih Kecamatan --</option>
                                                        @foreach ($kecamatans as $kecamatan)
                                                            <option value="{{ $kecamatan->id }}"
                                                                {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                                                {{ $kecamatan->kecamatan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kelurahan_id">Kelurahan</label>
                                                    <select name="kelurahan_id" id="kelurahan_id"
                                                        class="form-control @error('kelurahan_id') is-invalid @enderror">
                                                        <option value="">-- Pilih Kelurahan --</option>
                                                        @foreach ($kelurahans as $kelurahan)
                                                            <option value="{{ $kelurahan->id }}"
                                                                {{ old('kelurahan_id') == $kelurahan->id ? 'selected' : '' }}>
                                                                {{ $kelurahan->kelurahan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="alamat">Alamat Lengkap</label>
                                                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-white">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan
                                        Supplier</button>
                                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Batal</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();
            $('[data-widget="treeview"]').Treeview('init');
        });
    </script>
</body>

</html>
