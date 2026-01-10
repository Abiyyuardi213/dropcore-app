<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Distributor</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
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
                            <h1 class="m-0">Tambah Distributor</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-truck-loading mr-1"></i> Form Tambah Distributor
                            </h3>
                        </div>

                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('distributor.store') }}" method="POST">
                                @csrf
                                <div class="card card-primary card-tabs">
                                    <div class="card-header p-0 pt-1">
                                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-tabs-one-home-tab"
                                                    data-toggle="pill" href="#custom-tabs-one-home" role="tab"
                                                    aria-controls="custom-tabs-one-home" aria-selected="true">Informasi
                                                    Utama</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                                    href="#custom-tabs-one-profile" role="tab"
                                                    aria-controls="custom-tabs-one-profile" aria-selected="false">Kontak
                                                    & Alamat</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill"
                                                    href="#custom-tabs-one-messages" role="tab"
                                                    aria-controls="custom-tabs-one-messages"
                                                    aria-selected="false">Detail Lainnya</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-one-tabContent">
                                            <!-- Tab 1: Informasi Utama -->
                                            <div class="tab-pane fade show active" id="custom-tabs-one-home"
                                                role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="nama_distributor">Nama Distributor <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-building"></i></span>
                                                                </div>
                                                                <input type="text" name="nama_distributor"
                                                                    class="form-control @error('nama_distributor') is-invalid @enderror"
                                                                    value="{{ old('nama_distributor') }}" required
                                                                    placeholder="Contoh: PT. Maju Jaya"
                                                                    autocomplete="off">
                                                            </div>
                                                            @error('nama_distributor')
                                                                <span class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tipe_distributor">Tipe Distributor <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="tipe_distributor"
                                                                class="form-control select2bs4 @error('tipe_distributor') is-invalid @enderror"
                                                                required data-placeholder="Pilih Tipe">
                                                                <option value="Distributor"
                                                                    {{ old('tipe_distributor') == 'Distributor' ? 'selected' : '' }}>
                                                                    Distributor</option>
                                                                <option value="Principal"
                                                                    {{ old('tipe_distributor') == 'Principal' ? 'selected' : '' }}>
                                                                    Principal</option>
                                                                <option value="Reseller"
                                                                    {{ old('tipe_distributor') == 'Reseller' ? 'selected' : '' }}>
                                                                    Reseller</option>
                                                            </select>
                                                            @error('tipe_distributor')
                                                                <span class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="status">Status <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="status"
                                                                class="form-control select2bs4 @error('status') is-invalid @enderror"
                                                                required data-placeholder="Pilih Status">
                                                                <option value="active"
                                                                    {{ old('status') == 'active' ? 'selected' : '' }}>
                                                                    Active</option>
                                                                <option value="inactive"
                                                                    {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                                    Inactive</option>
                                                                <option value="blacklisted"
                                                                    {{ old('status') == 'blacklisted' ? 'selected' : '' }}>
                                                                    Blacklisted</option>
                                                            </select>
                                                            @error('status')
                                                                <span
                                                                    class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="kota_id">Kota <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="kota_id"
                                                                class="form-control select2bs4 @error('kota_id') is-invalid @enderror"
                                                                data-placeholder="Cari & Pilih Kota">
                                                                <option value="">-- Pilih Kota --</option>
                                                                @foreach ($kotas as $k)
                                                                    <option value="{{ $k->id }}"
                                                                        {{ old('kota_id') == $k->id ? 'selected' : '' }}>
                                                                        {{ $k->kota }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('kota_id')
                                                                <span
                                                                    class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tab 2: Kontak & Alamat -->
                                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                                aria-labelledby="custom-tabs-one-profile-tab">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="telepon">Telepon Kantor</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-phone"></i></span>
                                                                </div>
                                                                <input type="text" name="telepon"
                                                                    class="form-control @error('telepon') is-invalid @enderror"
                                                                    value="{{ old('telepon') }}"
                                                                    placeholder="021-xxxxxx">
                                                            </div>
                                                            @error('telepon')
                                                                <span
                                                                    class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email Kantor</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-envelope"></i></span>
                                                                </div>
                                                                <input type="email" name="email"
                                                                    class="form-control @error('email') is-invalid @enderror"
                                                                    value="{{ old('email') }}"
                                                                    placeholder="info@example.com">
                                                            </div>
                                                            @error('email')
                                                                <span
                                                                    class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="website">Website</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-globe"></i></span>
                                                                </div>
                                                                <input type="url" name="website"
                                                                    class="form-control @error('website') is-invalid @enderror"
                                                                    value="{{ old('website') }}"
                                                                    placeholder="https://example.com">
                                                            </div>
                                                            @error('website')
                                                                <span
                                                                    class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="alamat">Alamat Lengkap</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-map-marker-alt"></i></span>
                                                                </div>
                                                                <textarea name="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror"
                                                                    placeholder="Jalan, No. Gedung, RT/RW, dsb">{{ old('alamat') }}</textarea>
                                                            </div>
                                                            @error('alamat')
                                                                <span
                                                                    class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tab 3: Detail Lainnya -->
                                            <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel"
                                                aria-labelledby="custom-tabs-one-messages-tab">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card card-secondary card-outline">
                                                            <div class="card-header">
                                                                <h5 class="card-title"><i
                                                                        class="fas fa-user-friends mr-1"></i> Person In
                                                                    Charge (PIC)</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label for="pic_nama">Nama PIC</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i
                                                                                    class="fas fa-user-tie"></i></span>
                                                                        </div>
                                                                        <input type="text" name="pic_nama"
                                                                            class="form-control @error('pic_nama') is-invalid @enderror"
                                                                            value="{{ old('pic_nama') }}"
                                                                            placeholder="Nama Kontak Person">
                                                                    </div>
                                                                    @error('pic_nama')
                                                                        <span
                                                                            class="text-danger text-sm">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="pic_telepon">Telepon PIC</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i
                                                                                    class="fas fa-mobile-alt"></i></span>
                                                                        </div>
                                                                        <input type="text" name="pic_telepon"
                                                                            class="form-control @error('pic_telepon') is-invalid @enderror"
                                                                            value="{{ old('pic_telepon') }}"
                                                                            placeholder="Nomor HP/WA PIC">
                                                                    </div>
                                                                    @error('pic_telepon')
                                                                        <span
                                                                            class="text-danger text-sm">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="npwp">NPWP</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-id-card"></i></span>
                                                                </div>
                                                                <input type="text" name="npwp"
                                                                    class="form-control @error('npwp') is-invalid @enderror"
                                                                    value="{{ old('npwp') }}"
                                                                    placeholder="XX.XXX.XXX.X-XXX.XXX">
                                                            </div>
                                                            @error('npwp')
                                                                <span
                                                                    class="text-danger text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label for="latitude">Latitude</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i
                                                                                    class="fas fa-map-pin"></i></span>
                                                                        </div>
                                                                        <input type="text" name="latitude"
                                                                            class="form-control @error('latitude') is-invalid @enderror"
                                                                            value="{{ old('latitude') }}"
                                                                            placeholder="-6.2088">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label for="longitude">Longitude</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i
                                                                                    class="fas fa-map-pin"></i></span>
                                                                        </div>
                                                                        <input type="text" name="longitude"
                                                                            class="form-control @error('longitude') is-invalid @enderror"
                                                                            value="{{ old('longitude') }}"
                                                                            placeholder="106.8456">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="keterangan">Keterangan Tambahan</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-sticky-note"></i></span>
                                                                </div>
                                                                <textarea name="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror"
                                                                    placeholder="Catatan khusus...">{{ old('keterangan') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right bg-white">
                                        <a href="{{ route('distributor.index') }}" class="btn btn-secondary mr-2"><i
                                                class="fas fa-arrow-left"></i> Batal</a>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                            Simpan</button>
                                    </div>
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
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            // General Select2 init
            $('.select2bs4').each(function() {
                $(this).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: $(this).data('placeholder') || "Pilih opsi",
                    allowClear: true
                });
            });
        });
    </script>

</body>

</html>
