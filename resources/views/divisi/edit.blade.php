<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Divisi - DropCore</title>
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
                            <h1 class="m-0">Edit Divisi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('divisi.index') }}">Divisi</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Divisi</h3>
                        </div>
                        <form action="{{ route('divisi.update', $divisi->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group pb-0 mb-0">
                                    <label>Kode Divisi</label>
                                    <input type="text" name="kode"
                                        class="form-control @error('kode') is-invalid @enderror"
                                        value="{{ old('kode', $divisi->kode) }}"
                                        placeholder="Otomatis digenerate sistem" readonly>
                                    <small class="text-muted">Kode akan otomatis digenerate berdasarkan Nama
                                        Divisi.</small>
                                    @error('kode')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mt-3">
                                    <label>Nama Divisi</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $divisi->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Kepala Divisi</label>
                                    <input type="text" name="kepala_divisi"
                                        class="form-control @error('kepala_divisi') is-invalid @enderror"
                                        value="{{ old('kepala_divisi', $divisi->kepala_divisi) }}">
                                    @error('kepala_divisi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Lokasi</label>
                                    <input type="text" name="lokasi"
                                        class="form-control @error('lokasi') is-invalid @enderror"
                                        value="{{ old('lokasi', $divisi->lokasi) }}">
                                    @error('lokasi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Email Divisi</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $divisi->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $divisi->deskripsi) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ $divisi->status ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ !$divisi->status ? 'selected' : '' }}>Non-Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning">Update</button>
                                <a href="{{ route('divisi.index') }}" class="btn btn-default">Batal</a>
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
                    // Remove special chars, keep letters/numbers and spaces
                    var cleanName = name.replace(/[^a-zA-Z0-9\s]/g, '');
                    var words = cleanName.trim().split(/\s+/);

                    if (words.length > 1) {
                        // Takes first letter of first 3 words
                        acronym = words.slice(0, 3).map(function(word) {
                            return word.charAt(0);
                        }).join('');
                    } else {
                        // Takes first 3 letters of single word
                        acronym = cleanName.substring(0, 3);
                    }
                    var code = 'DIV-' + acronym.toUpperCase();
                    $('input[name="kode"]').val(code);
                } else {
                    $('input[name="kode"]').val('');
                }
            });
        });
    </script>
</body>

</html>
