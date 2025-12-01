<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengeluaran Barang</title>
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
                <h1 class="m-0">Tambah Detail Pengeluaran Barang</h1>
                <p class="text-muted">No. Pengeluaran:
                    <strong>{{ $pengeluaran->no_pengeluaran }}</strong>
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">

                <!-- FORM TAMBAH BARANG -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-box-open"></i> Tambah Barang Keluar</h3>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('detail-pengeluaran.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="pengeluaran_id" value="{{ $pengeluaran->id }}">

                            <!-- 1) Pilih Produk -->
                            <div class="form-group">
                                <label for="produkSelect">Produk</label>
                                <select name="produk_id"
                                        class="form-control @error('produk_id') is-invalid @enderror"
                                        id="produkSelect"
                                        required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($produk as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('produk_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('produk_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- 2) Pilih STOK (baris pada tabel stok) -->
                            <div class="form-group">
                                <label for="stokSelect">Pilih Stok (Gudang / Area / Rak)</label>
                                <select id="stokSelect"
                                        name="stok_id"
                                        class="form-control @error('stok_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Produk Terlebih Dahulu --</option>
                                    {{-- opsi akan diisi via JS --}}
                                </select>
                                @error('stok_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="form-text text-muted">Pilih baris stok yang tersedia — lokasi akan terisi otomatis.</small>
                            </div>

                            <!-- Hidden snapshot fields (diisi otomatis saat stok dipilih) -->
                            <input type="hidden" name="gudang_id" id="gudang_id" value="{{ old('gudang_id') }}">
                            <input type="hidden" name="area_id" id="area_id" value="{{ old('area_id') }}">
                            <input type="hidden" name="rak_id" id="rak_id" value="{{ old('rak_id') }}">

                            <!-- Visible read-only shows (info lokasi) -->
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Gudang Asal</label>
                                    <input type="text" id="gudangAsal" class="form-control" readonly value="{{ old('gudang_name') }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Area Asal</label>
                                    <input type="text" id="areaAsal" class="form-control" readonly value="{{ old('area_name') }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Rak Asal</label>
                                    <input type="text" id="rakAsal" class="form-control" readonly value="{{ old('rak_name') }}">
                                </div>
                            </div>

                            <!-- Stok tersedia (diisi otomatis) -->
                            <div class="form-group">
                                <label for="stokTersedia">Stok Tersedia</label>
                                <input type="number" id="stokTersedia" class="form-control" readonly value="{{ old('stok_available', '') }}">
                            </div>

                            <!-- Qty keluar -->
                            <div class="form-group">
                                <label for="qtyKeluar">Qty Keluar</label>
                                <input type="number"
                                       name="qty"
                                       id="qtyKeluar"
                                       class="form-control @error('qty') is-invalid @enderror"
                                       min="1"
                                       value="{{ old('qty', 1) }}"
                                       required>
                                @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- (Opsional) Harga & Kondisi -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="harga">Harga Satuan</label>
                                    <input type="number" step="0.01" name="harga" id="harga"
                                           class="form-control @error('harga') is-invalid @enderror"
                                           value="{{ old('harga', '') }}" required>
                                    @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="kondisi_id">Kondisi</label>
                                    <select name="kondisi_id" id="kondisi_id" class="form-control">
                                        <option value="">(Pilih kondisi)</option>
                                        {{-- Jika Anda punya daftar kondisi, loop di sini --}}
                                    </select>
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Tambahkan
                                </button>
                                <a href="{{ route('pengeluaran-barang.index') }}" class="btn btn-secondary">Selesai</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- LIST DETAIL -->
                <div class="card mt-4">
                    <div class="card-header bg-dark">
                        <h3 class="card-title"><i class="fas fa-list"></i> Daftar Barang Dikeluarkan</h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Gudang</th>
                                    <th>Area</th>
                                    <th>Rak</th>
                                    <th>Qty</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($details as $d)
                                    <tr>
                                        <td>{{ $d->produk->name }}</td>
                                        <td>{{ optional($d->gudang)->nama_gudang }}</td>
                                        <td>{{ optional($d->area)->nama_area }}</td>
                                        <td>{{ optional($d->rak)->kode_rak }}</td>
                                        <td>{{ $d->qty }}</td>
                                        <td>
                                            <form action="{{ route('detail-pengeluaran.destroy', $d->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                @if($details->count() === 0)
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada barang dikeluarkan</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
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

<script>
/*
  Flow:
  1) User pilih produk -> load daftar stok (baris stok) via /stok/by-produk/{id}
  2) User pilih salah satu stok -> auto isi stok_id (select name), gudang_id, area_id, rak_id, dan tampilkan nama lokasi + stok tersisa
  3) qty input max di-set sesuai stok tersisa
*/

// helper untuk set nilai lokasi & stok
function setStokDetail(option) {
    if (!option) {
        $('#gudang_id').val('');
        $('#area_id').val('');
        $('#rak_id').val('');
        $('#gudangAsal').val('');
        $('#areaAsal').val('');
        $('#rakAsal').val('');
        $('#stokTersedia').val('');
        $('#qtyKeluar').attr('max', 999999);
        return;
    }

    const stokId = option.value;
    const gudangId = option.dataset.gudangId;
    const areaId = option.dataset.areaId;
    const rakId = option.dataset.rakId;
    const gudangName = option.dataset.gudangName ?? '';
    const areaName = option.dataset.areaName ?? '';
    const rakName = option.dataset.rakName ?? '';
    const qty = option.dataset.qty ?? 0;

    $('#gudang_id').val(gudangId);
    $('#area_id').val(areaId);
    $('#rak_id').val(rakId);

    $('#gudangAsal').val(gudangName);
    $('#areaAsal').val(areaName);
    $('#rakAsal').val(rakName);
    $('#stokTersedia').val(qty);

    // set max qty
    $('#qtyKeluar').attr('max', parseInt(qty) || 1);
}

$(document).ready(function () {

    const produkSelect = $('#produkSelect');
    const stokSelect = $('#stokSelect');

    // Jika ada old('produk_id') sudah ter-set, trigger load stok otomatis
    function loadStokForProduk(produkId, preselectStokId = null) {
        stokSelect.html('<option value="">Memuat stok...</option>');

        if (!produkId) {
            stokSelect.html('<option value="">-- Pilih Produk Terlebih Dahulu --</option>');
            setStokDetail(null);
            return;
        }

        fetch(`/stok/by-produk/${produkId}`)
            .then(res => res.json())
            .then(data => {
                // data: array stok dengan relasi gudang, area, rak
                let html = '<option value="">-- Pilih Stok Produk --</option>';
                data.forEach(s => {
                    // tampilkan label ringkas: Gudang | Area | Rak | Qty
                    const label = `Gudang: ${s.gudang?.nama_gudang ?? '-'} | Area: ${s.area?.nama_area ?? '-'} | Rak: ${s.rak?.kode_rak ?? '-'} | Stok: ${s.quantity}`;
                    html += `<option value="${s.id}"
                                   data-gudang-id="${s.gudang_id}"
                                   data-area-id="${s.area_id}"
                                   data-rak-id="${s.rak_id}"
                                   data-gudang-name="${s.gudang?.nama_gudang ?? ''}"
                                   data-area-name="${s.area?.nama_area ?? ''}"
                                   data-rak-name="${s.rak?.kode_rak ?? ''}"
                                   data-qty="${s.quantity}">
                                ${label}
                             </option>`;
                });
                stokSelect.html(html);

                // jika ada old stok_id (kembali dari validasi), preselect
                @if(old('stok_id'))
                    const oldStok = "{{ old('stok_id') }}";
                    stokSelect.val(oldStok);
                    const opt = stokSelect.find(':selected')[0];
                    setStokDetail(opt);
                @else
                    // jika ada preselectStokId yang dikirim fungsi, pilih itu
                    if (preselectStokId) {
                        stokSelect.val(preselectStokId);
                        const opt = stokSelect.find(':selected')[0];
                        setStokDetail(opt);
                    }
                @endif
            })
            .catch(err => {
                console.error('Gagal memuat stok:', err);
                stokSelect.html('<option value="">Gagal memuat stok</option>');
            });
    }

    // Event: saat produk berubah
    produkSelect.on('change', function () {
        const produkId = $(this).val();
        loadStokForProduk(produkId);
    });

    // Event: saat stok dipilih
    stokSelect.on('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) {
            setStokDetail(null);
            return;
        }
        setStokDetail(opt);
    });

    // Jika halaman di-reload dan ada old('produk_id'), otomatis load stok
    @if(old('produk_id'))
        loadStokForProduk("{{ old('produk_id') }}", "{{ old('stok_id') ?? '' }}");
    @endif
});
</script>

</body>
</html>
