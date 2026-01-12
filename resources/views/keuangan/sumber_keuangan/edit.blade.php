<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Edit Sumber Keuangan</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
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
                            <h1 class="m-0">Edit Sumber Keuangan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Sumber Keuangan</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('sumber-keuangan.update', $sumber->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Akun</label>
                                            <input type="text" name="nama_sumber"
                                                value="{{ old('nama_sumber', $sumber->nama_sumber) }}"
                                                class="form-control @error('nama_sumber') is-invalid @enderror"
                                                placeholder="Contoh: Bank BCA, Kas Kecil" required>
                                            @error('nama_sumber')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Akun</label>
                                            <select name="jenis"
                                                class="form-control @error('jenis') is-invalid @enderror" required>
                                                <option value="bank"
                                                    {{ old('jenis', $sumber->jenis) == 'bank' ? 'selected' : '' }}>Bank
                                                </option>
                                                <option value="tunai"
                                                    {{ old('jenis', $sumber->jenis) == 'tunai' ? 'selected' : '' }}>
                                                    Tunai (Cash)</option>
                                                <option value="e-wallet"
                                                    {{ old('jenis', $sumber->jenis) == 'e-wallet' ? 'selected' : '' }}>
                                                    E-Wallet / Dompet Digital</option>
                                            </select>
                                            @error('jenis')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="text-muted"><i class="fas fa-info-circle"></i> Detail Rekening (Untuk
                                    Bank/E-Wallet)</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nomor Rekening / ID</label>
                                            <input type="text" name="nomor_rekening" class="form-control"
                                                value="{{ old('nomor_rekening', $sumber->nomor_rekening) }}"
                                                placeholder="Contoh: 1234567890">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Atas Nama</label>
                                            <input type="text" name="atas_nama" class="form-control"
                                                value="{{ old('atas_nama', $sumber->atas_nama) }}"
                                                placeholder="Nama pemilik rekening">
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group">
                                    <label>Status Akun</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $sumber->is_active ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="0" {{ !$sumber->is_active ? 'selected' : '' }}>Non-Aktif
                                        </option>
                                    </select>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Saldo saat ini: <strong>Rp
                                        {{ number_format($sumber->saldo, 0, ',', '.') }}</strong>.
                                    <br>Saldo tidak dapat diubah di sini. Gunakan menu
                                    <strong>Pemasukan/Pengeluaran</strong> atau Mutasi Saldo.
                                </div>

                                <div class="mt-4 d-flex justify-content-between">
                                    <a href="{{ route('sumber-keuangan.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update
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
