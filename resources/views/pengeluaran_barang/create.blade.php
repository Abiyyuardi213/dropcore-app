<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengeluaran Barang</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
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
                        <h1 class="m-0">Tambah Pengeluaran Barang</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-dolly"></i> Form Tambah Pengeluaran Barang
                        </h3>
                    </div>

                    <div class="card-body">

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('pengeluaran-barang.store') }}" method="POST">
                            @csrf

                            <!-- Nomor Pengeluaran -->
                            <div class="form-group">
                                <label for="no_pengeluaran">Nomor Pengeluaran</label>
                                <input type="text" name="no_pengeluaran" id="no_pengeluaran"
                                       class="form-control" value="{{ $no_pengeluaran }}" readonly>
                            </div>

                            <!-- Tanggal Pengeluaran -->
                            <div class="form-group">
                                <label for="tanggal_pengeluaran">Tanggal Pengeluaran</label>
                                <input type="date" name="tanggal_pengeluaran"
                                       class="form-control @error('tanggal_pengeluaran') is-invalid @enderror"
                                       value="{{ old('tanggal_pengeluaran', date('Y-m-d')) }}" required>
                                @error('tanggal_pengeluaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipe Penerima -->
                            <div class="form-group">
                                <label for="tipe_penerima">Tipe Penerima</label>
                                <select name="tipe_penerima" id="tipe_penerima"
                                        class="form-control @error('tipe_penerima') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tipe Penerima --</option>
                                    <option value="distributor" {{ old('tipe_penerima') == 'distributor' ? 'selected' : '' }}>
                                        Distributor
                                    </option>
                                    <option value="konsumen" {{ old('tipe_penerima') == 'konsumen' ? 'selected' : '' }}>
                                        Konsumen
                                    </option>
                                </select>
                                @error('tipe_penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Distributor Fields -->
                            <div id="distributor-fields" style="display:none;">
                                <div class="form-group">
                                    <label for="distributor_id">Pilih Distributor</label>
                                    <select name="distributor_id" id="distributor_id"
                                            class="form-control @error('distributor_id') is-invalid @enderror">
                                        <option value="">-- Pilih Distributor --</option>

                                        @foreach($distributors as $distributor)
                                            <option value="{{ $distributor->id }}"
                                                {{ old('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                                {{ $distributor->nama_distributor }} ({{ $distributor->kode_distributor }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('distributor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Konsumen Fields -->
                            <div id="konsumen-fields" style="display:none;">

                                <div class="form-group">
                                    <label for="nama_konsumen">Nama Konsumen</label>
                                    <input type="text" name="nama_konsumen" id="nama_konsumen"
                                           class="form-control @error('nama_konsumen') is-invalid @enderror"
                                           value="{{ old('nama_konsumen') }}" placeholder="Nama Konsumen">
                                    @error('nama_konsumen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="telepon_konsumen">Telepon Konsumen</label>
                                    <input type="text" name="telepon_konsumen" id="telepon_konsumen"
                                           class="form-control @error('telepon_konsumen') is-invalid @enderror"
                                           value="{{ old('telepon_konsumen') }}" placeholder="Nomor Telepon">
                                    @error('telepon_konsumen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alamat_konsumen">Alamat Konsumen</label>
                                    <textarea name="alamat_konsumen" id="alamat_konsumen"
                                              class="form-control @error('alamat_konsumen') is-invalid @enderror"
                                              placeholder="Alamat Lengkap">{{ old('alamat_konsumen') }}</textarea>
                                    @error('alamat_konsumen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <!-- Keterangan -->
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan"
                                          class="form-control @error('keterangan') is-invalid @enderror"
                                          placeholder="Tulis keterangan jika ada">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tombol -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Selanjutnya
                                </button>
                                <a href="{{ route('pengeluaran-barang.index') }}" class="btn btn-secondary">
                                    Batal
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    $(document).ready(function () {

        const tipePenerima = $('#tipe_penerima');
        const distributorFields = $('#distributor-fields');
        const konsumenFields = $('#konsumen-fields');

        function toggleReceiverFields() {
            const selectedType = tipePenerima.val();

            distributorFields.hide();
            konsumenFields.hide();

            $('#distributor_id').prop('required', false);
            $('#nama_konsumen').prop('required', false);
            $('#telepon_konsumen').prop('required', false);
            $('#alamat_konsumen').prop('required', false);

            if (selectedType === 'distributor') {
                distributorFields.show();
                $('#distributor_id').prop('required', true);
            }

            if (selectedType === 'konsumen') {
                konsumenFields.show();
                $('#nama_konsumen').prop('required', true);
                $('#telepon_konsumen').prop('required', true);
                $('#alamat_konsumen').prop('required', true);
            }

            @if($errors->has('distributor_id'))
                distributorFields.show();
            @endif

            @if($errors->has('nama_konsumen') || $errors->has('telepon_konsumen') || $errors->has('alamat_konsumen'))
                konsumenFields.show();
            @endif
        }

        toggleReceiverFields();
        tipePenerima.on('change', toggleReceiverFields);

    });
</script>

</body>
</html>
