<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kantor</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
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
                        <h1 class="m-0">Edit Kantor</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-building"></i> Form Edit Kantor
                        </h3>
                    </div>

                    <div class="card-body">

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('kantor.update', $kantor->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nama Kantor -->
                            <div class="form-group">
                                <label for="nama_kantor">Nama Kantor</label>
                                <input type="text" name="nama_kantor"
                                       class="form-control @error('nama_kantor') is-invalid @enderror"
                                       value="{{ old('nama_kantor', $kantor->nama_kantor) }}" required
                                       placeholder="Masukkan nama kantor" autocomplete="off">
                                @error('nama_kantor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kota -->
                            <div class="form-group">
                                <label for="kota_id">Kota</label>
                                <select name="kota_id"
                                        class="form-control @error('kota_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach($kotas as $kota)
                                        <option value="{{ $kota->id }}"
                                            {{ old('kota_id', $kantor->kota_id) == $kota->id ? 'selected' : '' }}>
                                            {{ $kota->kota }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kota_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat"
                                          class="form-control @error('alamat') is-invalid @enderror"
                                          placeholder="Masukkan alamat kantor">{{ old('alamat', $kantor->alamat) }}</textarea>
                                @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Telepon -->
                            <div class="form-group">
                                <label for="telepon">Telepon</label>
                                <input type="text" name="telepon"
                                       class="form-control @error('telepon') is-invalid @enderror"
                                       value="{{ old('telepon', $kantor->telepon) }}"
                                       placeholder="Masukkan nomor telepon">
                                @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Kantor -->
                            <div class="form-group">
                                <label for="jenis_kantor">Jenis Kantor</label>
                                <select name="jenis_kantor"
                                        class="form-control @error('jenis_kantor') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="1" {{ old('jenis_kantor', $kantor->jenis_kantor) == 1 ? 'selected' : '' }}>Pusat</option>
                                    <option value="2" {{ old('jenis_kantor', $kantor->jenis_kantor) == 2 ? 'selected' : '' }}>Cabang</option>
                                    <option value="3" {{ old('jenis_kantor', $kantor->jenis_kantor) == 3 ? 'selected' : '' }}>Gudang</option>
                                </select>
                                @error('jenis_kantor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status"
                                        class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="1" {{ old('status', $kantor->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status', $kantor->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tombol -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Perbarui
                                </button>

                                <a href="{{ route('kantor.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
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

</body>
</html>
