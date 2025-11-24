<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jabatan</title>
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
                <h1 class="m-0">Edit Jabatan</h1>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i> Form Edit Jabatan
                        </h3>
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <form action="{{ route('jabatan.update', $jabatan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- Kode Jabatan -->
                            <div class="form-group">
                                <label>Kode Jabatan</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $jabatan->kode_jabatan }}"
                                       readonly>
                            </div>
                            <!-- Nama Jabatan -->
                            <div class="form-group">
                                <label for="name">Nama Jabatan</label>
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $jabatan->name) }}"
                                       placeholder="Masukkan nama jabatan"
                                       required autocomplete="off">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Divisi -->
                            <div class="form-group">
                                <label for="divisi_id">Divisi</label>
                                <select name="divisi_id"
                                        class="form-control @error('divisi_id') is-invalid @enderror"
                                        required>
                                    <option value="">Pilih Divisi</option>
                                    @foreach($divisis as $divisi)
                                        <option value="{{ $divisi->id }}"
                                            {{ old('divisi_id', $jabatan->divisi_id) == $divisi->id ? 'selected' : '' }}>
                                            {{ $divisi->kode }} | {{ $divisi->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('divisi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Deskripsi -->
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi"
                                          class="form-control @error('deskripsi') is-invalid @enderror"
                                          placeholder="Masukkan deskripsi jabatan">{{ old('deskripsi', $jabatan->deskripsi) }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Status -->
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status"
                                        class="form-control @error('status') is-invalid @enderror"
                                        required>
                                    <option value="1" {{ old('status', $jabatan->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status', $jabatan->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('jabatan.index') }}" class="btn btn-secondary">
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
