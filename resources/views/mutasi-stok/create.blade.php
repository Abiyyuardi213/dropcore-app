<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mutasi Stok</title>
    <link rel="icon" type="image/png" href="{{ asset('image/itats-1080.jpg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <h1 class="m-0">Tambah Mutasi Stok</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exchange-alt"></i> Form Mutasi Stok</h3>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('mutasi-stok.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                {{-- Kolom Kiri --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="produk_id">Produk</label>
                                        <select id="produk_id" name="produk_id" class="form-control @error('produk_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $produk)
                                                <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                                    {{ $produk->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div id="lokasi-asal-list" class="mb-3"></div>

                                    <div class="form-group">
                                        <label for="quantity">Jumlah (Qty)</label>
                                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                            value="{{ old('quantity') }}" required min="1">
                                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal_mutasi">Tanggal Mutasi</label>
                                        <input type="date" name="tanggal_mutasi" class="form-control @error('tanggal_mutasi') is-invalid @enderror"
                                            value="{{ old('tanggal_mutasi') ?? date('Y-m-d') }}" required>
                                        @error('tanggal_mutasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <hr>
                                    <h5>Lokasi Asal</h5>
                                    <div class="form-group">
                                        <label for="gudang_asal_id">Gudang Asal</label>
                                        <select name="gudang_asal_id" class="form-control @error('gudang_asal_id') is-invalid @enderror">
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}" {{ old('gudang_asal_id') == $gudang->id ? 'selected' : '' }}>
                                                    {{ $gudang->nama_gudang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('gudang_asal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="area_asal_id">Area Asal</label>
                                        <select name="area_asal_id" class="form-control @error('area_asal_id') is-invalid @enderror">
                                            <option value="">-- Pilih Area --</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area->id }}" {{ old('area_asal_id') == $area->id ? 'selected' : '' }}>
                                                    {{ $area->kode_area }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('area_asal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="rak_asal_id">Rak Asal</label>
                                        <select name="rak_asal_id" class="form-control @error('rak_asal_id') is-invalid @enderror">
                                            <option value="">-- Pilih Rak --</option>
                                            @foreach($raks as $rak)
                                                <option value="{{ $rak->id }}" {{ old('rak_asal_id') == $rak->id ? 'selected' : '' }}>
                                                    {{ $rak->kode_rak }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('rak_asal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Kolom Kanan --}}
                                <div class="col-md-6">
                                    <h5 class="mt-4 mt-md-0">Lokasi Tujuan</h5>
                                    <div class="form-group">
                                        <label for="gudang_tujuan_id">Gudang Tujuan</label>
                                        <select name="gudang_tujuan_id" class="form-control @error('gudang_tujuan_id') is-invalid @enderror">
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}" {{ old('gudang_tujuan_id') == $gudang->id ? 'selected' : '' }}>
                                                    {{ $gudang->nama_gudang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('gudang_tujuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="area_tujuan_id">Area Tujuan</label>
                                        <select name="area_tujuan_id" class="form-control @error('area_tujuan_id') is-invalid @enderror">
                                            <option value="">-- Pilih Area --</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area->id }}" {{ old('area_tujuan_id') == $area->id ? 'selected' : '' }}>
                                                    {{ $area->kode_area }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('area_tujuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="rak_tujuan_id">Rak Tujuan</label>
                                        <select name="rak_tujuan_id" class="form-control @error('rak_tujuan_id') is-invalid @enderror">
                                            <option value="">-- Pilih Rak --</option>
                                            @foreach($raks as $rak)
                                                <option value="{{ $rak->id }}" {{ old('rak_tujuan_id') == $rak->id ? 'selected' : '' }}>
                                                    {{ $rak->kode_rak }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('rak_tujuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Keterangan (Opsional)</label>
                                        <textarea name="keterangan" rows="5" class="form-control @error('keterangan') is-invalid @enderror"
                                            placeholder="Contoh: Mutasi karena penataan ulang stok">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('mutasi-stok.index') }}" class="btn btn-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
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

@include('services.logoutModal')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#produk_id').select2({
            placeholder: "-- Pilih Produk --",
            allowClear: true
        });
    });

    $(document).ready(function () {
        $('#produk_id').change(function () {
            const produkId = $(this).val();
            $('#lokasi-asal-list').html('');

            if (produkId) {
                $.ajax({
                    url: `/lokasi-asal-produk/${produkId}`,
                    method: 'GET',
                    success: function (res) {
                        // Tampilkan semua lokasi
                        if (res.semua_lokasi.length > 0) {
                            let html = `<h6>Lokasi Asal Tersedia untuk Produk Ini:</h6><ul class="list-group">`;
                            res.semua_lokasi.forEach(function (item) {
                                html += `<li class="list-group-item">
                                    <strong>Gudang:</strong> ${item.gudang_nama},
                                    <strong>Area:</strong> ${item.area_kode},
                                    <strong>Rak:</strong> ${item.rak_kode}
                                </li>`;
                            });
                            html += `</ul>`;
                            $('#lokasi-asal-list').html(html);
                        } else {
                            $('#lokasi-asal-list').html('<div class="alert alert-warning">Tidak ada stok asal untuk produk ini.</div>');
                        }

                        // Isi otomatis lokasi utama
                        if (res.lokasi_utama) {
                            $('select[name="gudang_asal_id"]').val(res.lokasi_utama.gudang_id).trigger('change');
                            $('select[name="area_asal_id"]').val(res.lokasi_utama.area_id).trigger('change');
                            $('select[name="rak_asal_id"]').val(res.lokasi_utama.rak_id).trigger('change');
                        } else {
                            // Reset jika kosong
                            $('select[name="gudang_asal_id"]').val('');
                            $('select[name="area_asal_id"]').val('');
                            $('select[name="rak_asal_id"]').val('');
                        }
                    },
                    error: function () {
                        $('#lokasi-asal-list').html('<div class="alert alert-danger">Gagal memuat data lokasi.</div>');
                    }
                });
            }
        });
    });
</script>

</body>
</html>
