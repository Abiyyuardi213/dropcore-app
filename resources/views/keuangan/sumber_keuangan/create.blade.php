<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Tambah Sumber Keuangan</title>

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
                            <h1 class="m-0">Tambah Sumber Keuangan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-primary card-outline shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title text-bold"><i class="fas fa-plus-circle mr-2"></i>Form Tambah
                                        Sumber Keuangan Baru</h3>
                                </div>

                                <form action="{{ route('sumber-keuangan.store') }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        {{-- Section 1: Identitas Akun --}}
                                        <div class="alert alert-light border">
                                            <h5 class="text-primary"><i class="fas fa-id-card mr-2"></i>Identitas Akun
                                            </h5>
                                            <hr class="mt-1 mb-3">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Jenis Akun <span class="text-danger">*</span></label>
                                                        <select name="jenis"
                                                            class="form-control select2bs4 @error('jenis') is-invalid @enderror"
                                                            required style="width: 100%;">
                                                            <option value="bank"
                                                                {{ (old('jenis') ?? request('type')) == 'bank' ? 'selected' : '' }}>
                                                                Bank
                                                            </option>
                                                            <option value="tunai"
                                                                {{ (old('jenis') ?? request('type')) == 'tunai' ? 'selected' : '' }}>
                                                                Tunai (Cash)
                                                            </option>
                                                            <option value="e-wallet"
                                                                {{ (old('jenis') ?? request('type')) == 'e-wallet' ? 'selected' : '' }}>
                                                                E-Wallet / Dompet Digital
                                                            </option>
                                                        </select>
                                                        @error('jenis')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label>Nama Akun / Bank <span
                                                                class="text-danger">*</span></label>

                                                        <!-- Hidden Input for Submission -->
                                                        <input type="hidden" name="nama_sumber" id="real_nama_sumber" value="{{ old('nama_sumber') }}">

                                                        <!-- Input for Non-Bank -->
                                                        <div id="manual_input_container">
                                                            <input type="text" id="manual_nama_sumber"
                                                                class="form-control @error('nama_sumber') is-invalid @enderror"
                                                                placeholder="Contoh: Kas Kecil, Dana, OVO"
                                                                value="{{ old('nama_sumber') }}">
                                                        </div>

                                                        <!-- Daftar lokal dari publikasi OJK -->
                                                        <div id="bank_select_container" style="display: none;">
                                                            <select id="bank_select" class="form-control select2bs4"
                                                                style="width: 100%;">
                                                                <option value="">-- Pilih Bank --</option>
                                                                @foreach($bankDirectory['banks'] as $bank)
                                                                    <option value="{{ $bank['name'] }}" @selected(old('nama_sumber') === $bank['name'])>{{ $bank['name'] }}{{ isset($bank['alias']) ? ' / '.$bank['alias'] : '' }} ({{ $bank['code'] }})</option>
                                                                @endforeach
                                                                <option value="__manual__">Bank lainnya / BPR — isi nama manual</option>
                                                            </select>
                                                            <small class="text-muted mt-1 d-block">
                                                                {{ count($bankDirectory['banks']) }} bank umum dan syariah dari
                                                                <a href="{{ $bankDirectory['source_url'] }}" target="_blank" rel="noopener">publikasi OJK {{ $bankDirectory['period'] }}</a>.
                                                                Daftar tersimpan lokal, bukan API Bank Indonesia. Jika belum tercantum, pilih Bank lainnya / BPR.
                                                            </small>
                                                        </div>

                                                        @error('nama_sumber')
                                                            <span
                                                                class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Section 2: Detail Harta --}}
                                        <div class="alert alert-light border mt-4">
                                            <h5 class="text-secondary"><i class="fas fa-info-circle mr-2"></i>Detail
                                                Informasi & Saldo</h5>
                                            <hr class="mt-1 mb-3">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Nomor Rekening / ID</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-credit-card"></i></span>
                                                            </div>
                                                            <input type="text" name="nomor_rekening"
                                                                class="form-control" value="{{ old('nomor_rekening') }}"
                                                                placeholder="Contoh: 1234567890">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Atas Nama</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-user"></i></span>
                                                            </div>
                                                            <input type="text" name="atas_nama" class="form-control"
                                                                value="{{ old('atas_nama') }}"
                                                                placeholder="Nama pemilik rekening">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="text-success">Saldo Awal</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span
                                                                    class="input-group-text font-weight-bold">Rp</span>
                                                            </div>
                                                            <input type="number" name="saldo"
                                                                class="form-control font-weight-bold text-success"
                                                                value="{{ old('saldo', 0) }}" min="0"
                                                                step="100">
                                                        </div>
                                                        <small class="text-muted">Saldo saat pembukaan akun.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-footer bg-white text-right">
                                        <a href="{{ route('sumber-keuangan.index') }}"
                                            class="btn btn-secondary mr-2">
                                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Simpan Sumber Keuangan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        @include('include.footerSistem')
    </div>

    @include('services.ToastModal')
    @include('services.LogoutModal')

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('resources/js/BankAccountForm.js') }}" defer></script>
</body>

</html>
