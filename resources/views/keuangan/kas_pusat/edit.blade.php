<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kas Pusat</title>
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
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Edit Kas Pusat</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">
                                <i class="fas fa-coins"></i> Form Edit Kas Pusat
                            </h3>
                        </div>

                        <form action="{{ route('kas-pusat.update', $kas->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">

                                <div class="form-group">
                                    <label for="saldo_awal">Saldo Awal</label>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('saldo_awal') is-invalid @enderror"
                                           name="saldo_awal"
                                           id="saldo_awal"
                                           value="{{ old('saldo_awal', $kas->saldo_awal) }}"
                                           required>
                                    @error('saldo_awal')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="saldo_saat_ini">Saldo Saat Ini</label>
                                    <input type="number" class="form-control" value="{{ $kas->saldo_saat_ini }}"
                                           readonly>
                                    <small class="text-muted">
                                        *Saldo saat ini dihitung otomatis dari transaksi pemasukan & pengeluaran.
                                    </small>
                                </div>

                            </div>

                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ url('kas-pusat') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @include('services.ToastModal')
    @include('services.LogoutModal')

</body>
</html>
