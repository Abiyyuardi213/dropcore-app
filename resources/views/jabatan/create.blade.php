<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jabatan - DropCore</title>
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
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Tambah Jabatan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('jabatan.index') }}">Jabatan</a></li>
                                <li class="breadcrumb-item active">Tambah</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Tambah Jabatan</h3>
                        </div>
                        <form action="{{ route('jabatan.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group pb-0 mb-0">
                                    <label>Kode Jabatan</label>
                                    <input type="text" name="kode_jabatan"
                                        class="form-control @error('kode_jabatan') is-invalid @enderror"
                                        value="{{ old('kode_jabatan') }}" placeholder="Otomatis digenerate sistem"
                                        readonly>
                                    <small class="text-muted">Kode akan otomatis digenerate berdasarkan Nama
                                        Jabatan.</small>
                                    @error('kode_jabatan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mt-3">
                                    <label>Nama Jabatan</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="Contoh: Manager Operasional" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Divisi</label>
                                    <select name="divisi_id"
                                        class="form-control @error('divisi_id') is-invalid @enderror">
                                        <option value="">-- Pilih Divisi --</option>
                                        @foreach ($divisis as $div)
                                            <option value="{{ $div->id }}"
                                                {{ old('divisi_id') == $div->id ? 'selected' : '' }}>
                                                {{ $div->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('divisi_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gaji Pokok</label>
                                            <input type="number" name="gaji_pokok" class="form-control"
                                                value="{{ old('gaji_pokok') }}" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tunjangan</label>
                                            <input type="number" name="tunjangan" class="form-control"
                                                value="{{ old('tunjangan') }}" placeholder="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Tanggung Jawab</label>
                                    <textarea name="tanggung_jawab" class="form-control" rows="3" placeholder="Rincian tanggung jawab...">{{ old('tanggung_jawab') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Kualifikasi</label>
                                    <textarea name="kualifikasi" class="form-control" rows="3" placeholder="Kualifikasi yang dibutuhkan...">{{ old('kualifikasi') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi Tambahan</label>
                                    <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi') }}</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('jabatan.index') }}" class="btn btn-default">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script>
        $(document).ready(function() {
            $('input[name="name"]').on('input keyup', function() {
                var name = $(this).val();
                if (name) {
                    var acronym = '';
                    var cleanName = name.replace(/[^a-zA-Z0-9\s]/g, '');
                    var words = cleanName.trim().split(/\s+/);

                    if (words.length > 1) {
                        acronym = words.slice(0, 3).map(function(word) {
                            return word.charAt(0);
                        }).join('');
                    } else {
                        acronym = cleanName.substring(0, 3);
                    }
                    var code = 'JAB-' + acronym.toUpperCase();
                    $('input[name="kode_jabatan"]').val(code);
                } else {
                    $('input[name="kode_jabatan"]').val('');
                }
            });
        });
    </script>
</body>

</html>
