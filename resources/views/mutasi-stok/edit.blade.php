<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mutasi Stok</title>
    <link rel="icon" type="image/png" href="{{ asset('image/itats-1080.jpg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
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
                        <h1 class="m-0">Edit Mutasi Stok</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit"></i> Form Edit Mutasi Stok</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mutasi-stok.update', $mutasi->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                {{-- Kolom Kiri --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="produk_id">Produk</label>
                                        <select id="produk_id" name="produk_id"
                                                class="form-control select2 @error('produk_id') is-invalid @enderror"
                                                style="width:100%;" required>
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $produk)
                                                <option value="{{ $produk->id }}" {{ ($mutasi->produk_id == $produk->id) ? 'selected' : '' }}>
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
                                               value="{{ old('quantity', $mutasi->quantity) }}" required min="1">
                                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal_mutasi">Tanggal Mutasi</label>
                                        <input type="date" name="tanggal_mutasi" class="form-control @error('tanggal_mutasi') is-invalid @enderror"
                                               value="{{ old('tanggal_mutasi', $mutasi->tanggal_mutasi) }}" required>
                                        @error('tanggal_mutasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <hr>
                                    <h5>Lokasi Asal</h5>
                                    <div class="form-group">
                                        <label>Gudang Asal</label>
                                        <select name="gudang_asal_id" class="form-control @error('gudang_asal_id') is-invalid @enderror">
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach($gudangs as $g)
                                                <option value="{{ $g->id }}" {{ $mutasi->gudang_asal_id == $g->id ? 'selected' : '' }}>
                                                    {{ $g->nama_gudang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('gudang_asal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Area Asal</label>
                                        <select name="area_asal_id" class="form-control @error('area_asal_id') is-invalid @enderror">
                                            <option value="">-- Pilih Area --</option>
                                            @foreach($areas as $a)
                                                <option value="{{ $a->id }}" {{ $mutasi->area_asal_id == $a->id ? 'selected' : '' }}>
                                                    {{ $a->kode_area }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('area_asal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Rak Asal</label>
                                        <select name="rak_asal_id" class="form-control @error('rak_asal_id') is-invalid @enderror">
                                            <option value="">-- Pilih Rak --</option>
                                            @foreach($raks as $r)
                                                <option value="{{ $r->id }}" {{ $mutasi->rak_asal_id == $r->id ? 'selected' : '' }}>
                                                    {{ $r->kode_rak }}
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
                                        <label>Gudang Tujuan</label>
                                        <select name="gudang_tujuan_id" class="form-control @error('gudang_tujuan_id') is-invalid @enderror">
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach($gudangs as $g)
                                                <option value="{{ $g->id }}" {{ $mutasi->gudang_tujuan_id == $g->id ? 'selected' : '' }}>
                                                    {{ $g->nama_gudang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('gudang_tujuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Area Tujuan</label>
                                        <select name="area_tujuan_id" class="form-control @error('area_tujuan_id') is-invalid @enderror">
                                            <option value="">-- Pilih Area --</option>
                                            @foreach($areas as $a)
                                                <option value="{{ $a->id }}" {{ $mutasi->area_tujuan_id == $a->id ? 'selected' : '' }}>
                                                    {{ $a->kode_area }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('area_tujuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Rak Tujuan</label>
                                        <select name="rak_tujuan_id" class="form-control @error('rak_tujuan_id') is-invalid @enderror">
                                            <option value="">-- Pilih Rak --</option>
                                            @foreach($raks as $r)
                                                <option value="{{ $r->id }}" {{ $mutasi->rak_tujuan_id == $r->id ? 'selected' : '' }}>
                                                    {{ $r->kode_rak }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('rak_tujuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan (Opsional)</label>
                                        <textarea name="keterangan" rows="5" class="form-control @error('keterangan') is-invalid @enderror"
                                                  placeholder="Contoh: Mutasi karena penataan ulang stok">{{ old('keterangan', $mutasi->keterangan) }}</textarea>
                                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('mutasi-stok.index') }}" class="btn btn-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-success px-4">
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

@include('services.logoutModal')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#produk_id').select2({
            placeholder: "-- Pilih Produk --",
            allowClear: true,
            width: '100%'     // pastikan penuh
        }).trigger('change');

        // untuk load lokasi asal awal saat halaman dibuka
        const initialProdukId = $('#produk_id').val();
        if (initialProdukId) {
            loadLokasiAwal(initialProdukId);
        }

        $('#produk_id').change(function () {
            let produkId = $(this).val();
            loadLokasiAwal(produkId);
        });

        function loadLokasiAwal(produkId) {
            $('#lokasi-asal-list').html('');
            if (produkId) {
                $.ajax({
                    url: `/lokasi-asal-produk/${produkId}`,
                    method: 'GET',
                    success: function (res) {
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
                    },
                    error: function () {
                        $('#lokasi-asal-list').html('<div class="alert alert-danger">Gagal memuat data lokasi.</div>');
                    }
                });
            }
        }
    });
</script>

</body>
</html>
