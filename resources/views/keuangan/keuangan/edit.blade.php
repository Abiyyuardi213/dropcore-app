<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Edit Transaksi Keuangan</title>

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
                            <h1 class="m-0">Edit Transaksi Keuangan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Transaksi : <strong>{{ $data->no_transaksi }}</strong></h3>
                        </div>

                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Mengubah nominal
                                atau jenis transaksi akan otomatis menyesuaikan kembali saldo akun terkait.
                            </div>

                            <form action="{{ route('keuangan.update', $data->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Transaksi</label>
                                            <select name="jenis_transaksi" id="jenis_transaksi"
                                                class="form-control @error('jenis_transaksi') is-invalid @enderror"
                                                required>
                                                <option value="pemasukkan"
                                                    {{ old('jenis_transaksi', $data->jenis_transaksi) == 'pemasukkan' ? 'selected' : '' }}>
                                                    Pemasukkan (Income)</option>
                                                <option value="pengeluaran"
                                                    {{ old('jenis_transaksi', $data->jenis_transaksi) == 'pengeluaran' ? 'selected' : '' }}>
                                                    Pengeluaran (Expense)</option>
                                            </select>
                                            @error('jenis_transaksi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kategori (COA)</label>
                                            <select name="kategori_keuangan_id"
                                                class="form-control @error('kategori_keuangan_id') is-invalid @enderror"
                                                required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        class="kategori-option {{ $cat->tipe }}"
                                                        {{ old('kategori_keuangan_id', $data->kategori_keuangan_id) == $cat->id ? 'selected' : '' }}>
                                                        [{{ $cat->kode }}] {{ $cat->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kategori_keuangan_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Akun Keuangan</label>
                                            <select name="sumber_id"
                                                class="form-control @error('sumber_id') is-invalid @enderror" required>
                                                <option value="">-- Pilih Akun --</option>
                                                @foreach ($sumberKeuangan as $sumber)
                                                    <option value="{{ $sumber->id }}"
                                                        {{ old('sumber_id', $data->sumber_id) == $sumber->id ? 'selected' : '' }}>
                                                        {{ $sumber->nama_sumber }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('sumber_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Transaksi</label>
                                            <input type="date" name="tanggal_transaksi"
                                                class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                                value="{{ old('tanggal_transaksi', $data->tanggal_transaksi) }}"
                                                required>
                                            @error('tanggal_transaksi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Jumlah Nominal (Rp)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" name="jumlah"
                                            class="form-control @error('jumlah') is-invalid @enderror"
                                            value="{{ old('jumlah', $data->jumlah) }}" required>
                                    </div>
                                    @error('jumlah')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $data->keterangan) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Bukti Transaksi</label>
                                    @if ($data->bukti_transaksi)
                                        <div class="mb-2">
                                            <a href="{{ asset('uploads/keuangan/' . $data->bukti_transaksi) }}"
                                                target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file"></i>
                                                Liha File Saat Ini</a>
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" name="bukti_transaksi" class="custom-file-input"
                                            id="buktiFile">
                                        <label class="custom-file-label" for="buktiFile">Ubah file (kosongkan jika tidak
                                            ingin mengubah)...</label>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-between">
                                    <a href="{{ route('keuangan.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Transaksi
                                    </button>
                                </div>

                            </form>

                            @push('scripts')
                                <script>
                                    $(document).ready(function() {
                                        // Dynamic Category Filtering based on Transaction Type
                                        function filterCategories() {
                                            var type = $('#jenis_transaksi').val();
                                            $('.kategori-option').hide();
                                            if (type) {
                                                $('.kategori-option.' + type).show();
                                            } else {
                                                $('.kategori-option').show();
                                            }
                                        }

                                        $('#jenis_transaksi').change(filterCategories);
                                        filterCategories();

                                        // Custom File Input Label
                                        $(".custom-file-input").on("change", function() {
                                            var fileName = $(this).val().split("\\").pop();
                                            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                                        });
                                    });
                                </script>
                            @endpush
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
