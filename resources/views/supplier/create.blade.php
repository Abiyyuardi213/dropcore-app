<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Supplier</title>
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
                            <h1 class="m-0">Tambah Supplier Baru</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-plus-circle"></i> Form Tambah Supplier</h3>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Kiri -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Nama Supplier</label>
                                            <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" name="nama_supplier" value="{{ old('nama_supplier') }}" required>
                                            @error('nama_supplier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email Pengguna</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="no_telepon">Nomor Telepon</label>
                                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" name="no_telepon" value="{{ old('no_telepon') }}" required>
                                            @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Tengah -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="wilayah_id">Wilayah Jangkau Supplier</label>
                                            <select name="wilayah_id" id="wilayah_id" class="form-control @error('wilayah_id') is-invalid @enderror">
                                                <option value="">-- Pilih Wilayah --</option>
                                                @foreach($wilayahs as $wilayah)
                                                    <option value="{{ $wilayah->id }}" {{ old('wilayah_id') == $wilayah->id ? 'selected' : '' }}>{{ $wilayah->negara }}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="provinsi_id">Provinsi Jangkau Supplier</label>
                                            <select name="provinsi_id" id="provinsi_id" class="form-control @error('provinsi_id') is-invalid @enderror">
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach($provinsis as $provinsi)
                                                    <option value="{{ $provinsi->id }}" {{ old('provinsi_id') == $provinsi->id ? 'selected' : '' }}>{{ $provinsi->provinsi }}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="kota_id">Kota Jangkau Supplier</label>
                                            <select name="kota_id" id="kota_id" class="form-control @error('kota_id') is-invalid @enderror">
                                                <option value="">-- Pilih Kota --</option>
                                                @foreach($kotas as $kota)
                                                    <option value="{{ $kota->id }}" {{ old('kota_id') == $kota->id ? 'selected' : '' }}>{{ $kota->kota }}</option>
                                                @endforeach
                                            </select>
                                            @error('kota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Kanan: Foto -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kecamatan_id">Kecamatan Asal Supplier</label>
                                            <select name="kecamatan_id" id="kecamatan_id" class="form-control @error('kecamatan_id') is-invalid @enderror">
                                                <option value="">-- Pilih Kecamatan --</option>
                                                @foreach($kecamatans as $kecamatan)
                                                    <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>{{ $kecamatan->kecamatan }}</option>
                                                @endforeach
                                            </select>
                                            @error('kecamatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="kelurahan_id">Kelurahan Asal Supplier</label>
                                            <select name="kelurahan_id" id="kelurahan_id" class="form-control @error('kelurahan_id') is-invalid @enderror">
                                                <option value="">-- Pilih Kelurahan --</option>
                                                @foreach($kelurahans as $kelurahan)
                                                    <option value="{{ $kelurahan->id }}" {{ old('kelurahan_id') == $kelurahan->id ? 'selected' : '' }}>{{ $kelurahan->kelurahan }}</option>
                                                @endforeach
                                            </select>
                                            @error('kelurahan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="form-group mt-3">
                                    <label for="alamat">Alamat Lengkap Supplier</label>
                                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat') }}</textarea>
                                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Tombol -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
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

    @include('services.logoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-widget="treeview"]').Treeview('init');
        });
    </script>
</body>
</html>
