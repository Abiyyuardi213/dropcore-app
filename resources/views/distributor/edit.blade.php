<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Distributor</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <style>
        .select2-container--bootstrap4 .select2-selection--single {
            border: 1px solid #ced4da !important;
            border-radius: .25rem !important;
            height: 40px !important;
            padding: 6px 12px !important;
            display: flex !important;
            align-items: center !important;
            background-color: #fff !important;
            box-shadow: none !important;
        }

        .select2-container--bootstrap4.select2-container--open .select2-selection--single {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 .2rem rgba(0,123,255,.25) !important;
        }

        .select2-container--bootstrap4 .select2-selection__rendered {
            line-height: 28px !important;
            color: #495057 !important;
        }

        .select2-container--bootstrap4 .select2-selection__arrow {
            height: 38px !important;
            top: 1px !important;
            right: 10px !important;
        }
    </style>
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
                        <h1 class="m-0">Edit Distributor</h1>
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
                            <i class="fas fa-truck-loading"></i> Form Edit Distributor
                        </h3>
                    </div>

                    <div class="card-body">

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('distributor.update', $distributor->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nama Distributor -->
                            <div class="form-group">
                                <label for="nama_distributor">Nama Distributor</label>
                                <input type="text" name="nama_distributor"
                                       class="form-control @error('nama_distributor') is-invalid @enderror"
                                       value="{{ old('nama_distributor', $distributor->nama_distributor) }}"
                                       required placeholder="Masukkan nama distributor" autocomplete="off">
                                @error('nama_distributor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pilih Kota -->
                            <div class="form-group">
                                <label for="kota_id">Kota</label>
                                <select name="kota_id"
                                        class="form-control select2bs4 @error('kota_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach($kotas as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('kota_id', $distributor->kota_id) == $k->id ? 'selected' : '' }}>
                                            {{ $k->kota }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kota_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Telepon -->
                            <div class="form-group">
                                <label for="telepon">Telepon</label>
                                <input type="text" name="telepon"
                                       class="form-control @error('telepon') is-invalid @enderror"
                                       value="{{ old('telepon', $distributor->telepon) }}"
                                       placeholder="Masukkan nomor telepon" autocomplete="off">
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $distributor->email) }}"
                                       placeholder="Masukkan email distributor">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat"
                                          class="form-control @error('alamat') is-invalid @enderror"
                                          placeholder="Masukkan alamat lengkap">{{ old('alamat', $distributor->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tombol -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>

                                <a href="{{ route('distributor.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Batal
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

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function () {
        $('.select2bs4').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: "Pilih Kota",
            allowClear: true
        });
    });
</script>

</body>
</html>
