<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Tambah Transaksi Keuangan</title>

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
                        <h1 class="m-0">Tambah Transaksi Keuangan</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Transaksi</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('keuangan.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Jenis Transaksi</label>
                                <select name="jenis_transaksi"
                                        class="form-control @error('jenis_transaksi') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Jenis Transaksi --</option>
                                    <option value="pemasukkan" {{ old('jenis_transaksi') == 'pemasukkan' ? 'selected' : '' }}>Pemasukkan</option>
                                    <option value="pengeluaran" {{ old('jenis_transaksi') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                </select>
                                @error('jenis_transaksi')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" step="0.01" min="0"
                                       name="jumlah"
                                       value="{{ old('jumlah') }}"
                                       class="form-control @error('jumlah') is-invalid @enderror"
                                       placeholder="Masukkan jumlah transaksi"
                                       required>
                                @error('jumlah')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Sumber Keuangan</label>
                                <select name="sumber_id"
                                        class="form-control @error('sumber_id') is-invalid @enderror">
                                    <option value="">-- Pilih Sumber Keuangan --</option>
                                    @foreach($sumberKeuangan as $sumber)
                                        <option value="{{ $sumber->id }}"
                                            {{ old('sumber_id') == $sumber->id ? 'selected' : '' }}>
                                            {{ $sumber->nama_sumber }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sumber_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Tanggal Transaksi</label>
                                <input type="date"
                                       name="tanggal_transaksi"
                                       value="{{ old('tanggal_transaksi') ?? date('Y-m-d') }}"
                                       class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                       required>
                                @error('tanggal_transaksi')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan"
                                          class="form-control @error('keterangan') is-invalid @enderror"
                                          placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('keuangan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('include.footerSistem')
</div>

@include('services.ToastModal')
@include('services.LogoutModal')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
