<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Baru | DropCore</title>
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
                            <h1 class="m-0">Buat Laporan Baru</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
                                <li class="breadcrumb-item active">Buat Baru</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Formulir Laporan Lapangan</h3>
                        </div>

                        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Laporan <span class="text-danger">*</span></label>
                                            <input type="date" name="tanggal_laporan" class="form-control"
                                                value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kategori <span class="text-danger">*</span></label>
                                            <select name="kategori" class="form-control" required>
                                                <option value="operasional">Operasional Harian</option>
                                                <option value="insiden" class="text-danger">Insiden / Masalah</option>
                                                <option value="lain_lain">Lain-lain</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Judul Laporan <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" class="form-control"
                                        placeholder="Contoh: Pengecekan Jalur Distribusi Wilayah A" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lokasi (Opsional)</label>
                                            <input type="text" name="lokasi" class="form-control"
                                                placeholder="Contoh: Gudang B, Area Site X">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kondisi Cuaca (Opsional)</label>
                                            <input type="text" name="kondisi_cuaca" class="form-control"
                                                placeholder="Contoh: Cerah, Hujan Deras">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi / Isi Laporan <span class="text-danger">*</span></label>
                                    <textarea name="deskripsi" class="form-control" rows="5" placeholder="Jelaskan detail aktivitas atau kejadian..."
                                        required></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Lampiran Foto (Opsional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="foto" class="custom-file-input"
                                                id="fotoInput" accept="image/*">
                                            <label class="custom-file-label" for="fotoInput">Pilih file</label>
                                        </div>
                                    </div>
                                    <small class="text-muted">Maksimal 2MB (JPG, PNG)</small>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan
                                    Laporan</button>
                                <a href="{{ route('laporan.index') }}" class="btn btn-default float-right">Batal</a>
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
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>
