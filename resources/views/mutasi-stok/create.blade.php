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
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">

    <style>
        /* Styling agar dropdown Select2 tampil rapi */
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
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('mutasi-stok.store') }}" method="POST">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="callout callout-info">
                                            <h5><i class="fas fa-info"></i> Info Mutasi</h5>
                                            <p>Pilih <strong>Jenis Mutasi</strong> terlebih dahulu untuk menyesuaikan
                                                form input.</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_mutasi">Jenis Mutasi <span
                                                    class="text-danger">*</span></label>
                                            <select name="jenis_mutasi" id="jenis_mutasi" class="form-control select2"
                                                style="width: 100%;" required>
                                                <option value="pindah" selected>Pindah Lokasi (Transfer)</option>
                                                <option value="masuk">Barang Masuk (Inbound / Supplier)</option>
                                                <option value="keluar">Barang Keluar (Outbound / Usage)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Kolom Kiri: Info Barang --}}
                                    <div class="col-md-6">
                                        <div class="card card-secondary card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title">Informasi Barang</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="produk_id">Produk <span
                                                            class="text-danger">*</span></label>
                                                    <select id="produk_id" name="produk_id" class="form-control select2"
                                                        data-placeholder="-- Pilih Produk --" style="width: 100%;"
                                                        required>
                                                        <option value=""></option>
                                                        @foreach ($products as $produk)
                                                            <option value="{{ $produk->id }}"
                                                                {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                                                {{ $produk->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('produk_id')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="kondisi_id">Kondisi Barang <span
                                                            class="text-danger">*</span></label>
                                                    <select id="kondisi_id" name="kondisi_id"
                                                        class="form-control select2"
                                                        data-placeholder="-- Pilih Kondisi --" style="width: 100%;"
                                                        required>
                                                        <option value=""></option>
                                                        @foreach ($kondisis as $kondisi)
                                                            <option value="{{ $kondisi->id }}"
                                                                {{ old('kondisi_id') == $kondisi->id ? 'selected' : '' }}>
                                                                {{ $kondisi->nama_kondisi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('kondisi_id')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="quantity">Jumlah (Qty) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" name="quantity"
                                                        class="form-control @error('quantity') is-invalid @enderror"
                                                        value="{{ old('quantity') }}" required min="1"
                                                        placeholder="0">
                                                    @error('quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="referensi">No. Referensi (Opsional)</label>
                                                    <input type="text" name="referensi"
                                                        class="form-control @error('referensi') is-invalid @enderror"
                                                        value="{{ old('referensi') }}"
                                                        placeholder="Contoh: PO-001, SJ-123">
                                                </div>

                                                <div class="form-group">
                                                    <label for="tanggal_mutasi">Tanggal Mutasi <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" name="tanggal_mutasi"
                                                        class="form-control @error('tanggal_mutasi') is-invalid @enderror"
                                                        value="{{ old('tanggal_mutasi') ?? date('Y-m-d') }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="keterangan">Keterangan</label>
                                                    <textarea name="keterangan" rows="3" class="form-control" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Kolom Kanan: Lokasi --}}
                                    <div class="col-md-6">
                                        {{-- Lokasi Asal --}}
                                        <div id="container_asal" class="card card-warning card-outline mb-3">
                                            <div class="card-header">
                                                <h3 class="card-title"><i class="fas fa-dolly text-warning mr-1"></i>
                                                    Lokasi Asal (Sumber)</h3>
                                            </div>
                                            <div class="card-body">
                                                <div id="lokasi-asal-list" class="mb-2"></div>

                                                <div class="form-group">
                                                    <label>Gudang Asal</label>
                                                    <select name="gudang_asal_id" class="form-control select2"
                                                        style="width: 100%;">
                                                        <option value="">-- Pilih Gudang --</option>
                                                        @foreach ($gudangs as $gudang)
                                                            <option value="{{ $gudang->id }}">
                                                                {{ $gudang->nama_gudang }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('gudang_asal_id')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Area Asal</label>
                                                    <select name="area_asal_id" class="form-control select2"
                                                        style="width: 100%;">
                                                        <option value="">-- Pilih Area --</option>
                                                        @foreach ($gudangs as $g)
                                                            @foreach ($g->areas as $a)
                                                                <option value="{{ $a->id }}"
                                                                    data-gudang="{{ $g->id }}">
                                                                    {{ $g->nama_gudang }} - {{ $a->kode_area }}
                                                                </option>
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Rak Asal</label>
                                                    <select name="rak_asal_id" class="form-control select2"
                                                        style="width: 100%;">
                                                        <option value="">-- Pilih Rak --</option>
                                                        {{-- Simplification: Loading all raks or dependent JS. For now loading all --}}
                                                        @foreach ($raks ?? [] as $rak)
                                                            <option value="{{ $rak->id }}">{{ $rak->kode_rak }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted text-italic">* Pastikan memilih lokasi
                                                        yang memiliki stok.</small>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Lokasi Tujuan --}}
                                        <div id="container_tujuan" class="card card-success card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><i
                                                        class="fas fa-share-square text-success mr-1"></i> Lokasi
                                                    Tujuan</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Gudang Tujuan</label>
                                                    <select name="gudang_tujuan_id" class="form-control select2"
                                                        style="width: 100%;">
                                                        <option value="">-- Pilih Gudang --</option>
                                                        @foreach ($gudangs as $gudang)
                                                            <option value="{{ $gudang->id }}">
                                                                {{ $gudang->nama_gudang }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('gudang_tujuan_id')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Area Tujuan</label>
                                                    <select name="area_tujuan_id" class="form-control select2"
                                                        style="width: 100%;">
                                                        <option value="">-- Pilih Area --</option>
                                                        @foreach ($gudangs as $g)
                                                            @foreach ($g->areas as $a)
                                                                <option value="{{ $a->id }}">
                                                                    {{ $g->nama_gudang }} - {{ $a->kode_area }}
                                                                </option>
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Rak Tujuan</label>
                                                    <select name="rak_tujuan_id" class="form-control select2"
                                                        style="width: 100%;">
                                                        <option value="">-- Pilih Rak --</option>
                                                        {{-- Simplified --}}
                                                        @foreach ($raks ?? [] as $rak)
                                                            <option value="{{ $rak->id }}">{{ $rak->kode_rak }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 mb-5 text-right">
                                    <a href="{{ route('mutasi-stok.index') }}" class="btn btn-secondary mr-2"><i
                                            class="fas fa-times"></i> Batal</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan
                                        Mutasi</button>
                                </div>
                            </form>
                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: $(this).data('placeholder'),
                allowClear: true
            });

            const $jenisMutasi = $('#jenis_mutasi');
            const $containerAsal = $('#container_asal');
            const $containerTujuan = $('#container_tujuan');
            const $produkSelect = $('#produk_id');
            const $kondisiSelect = $('#kondisi_id');
            const $lokasiAsalList = $('#lokasi-asal-list');
            const $gudangAsal = $('select[name="gudang_asal_id"]');
            const $areaAsal = $('select[name="area_asal_id"]');
            const $rakAsal = $('select[name="rak_asal_id"]');

            function updateFormVisibility() {
                const jenis = $jenisMutasi.val();

                if (jenis === 'masuk') {
                    $containerAsal.hide();
                    $containerTujuan.show();
                    // Reset Asal Validation/Values if needed or just hide
                } else if (jenis === 'keluar') {
                    $containerAsal.show();
                    $containerTujuan.hide();
                } else if (jenis === 'pindah') {
                    $containerAsal.show();
                    $containerTujuan.show();
                } else {
                    $containerAsal.hide();
                    $containerTujuan.hide();
                }
            }

            function fetchStock() {
                const produkId = $produkSelect.val();
                const kondisiId = $kondisiSelect.val();
                const jenis = $jenisMutasi.val();

                if (['keluar', 'pindah'].includes(jenis) && produkId && kondisiId) {
                    $lokasiAsalList.html(
                        '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Mencari stok...</div>');

                    // Note: You would likely create a more specific API endpoint for filtering by condition
                    // For now reusing the existing logic pattern but ideally updated to filter by Condition too
                    // Since the previous endpoint didn't take Condition, we might just show all locations and let user pick, 
                    // OR we rely on the user to pick correct Gudang/Rak where that condition exists.
                    // Let's call the existing endpoint and purely display info. 
                    // A better approach: Create a new endpoint `get-stock-locations?produk_id=X&kondisi_id=Y`
                    // But to avoid touching controller too much, let's use the existing `lokasi-asal-produk` if valid,
                    // or just leave it manual selection.

                    $.ajax({
                        url: `/lokasi-asal-produk/${produkId}`, // This endpoint returns all stock locations for a product
                        method: 'GET',
                        success: function(res) {
                            if (res.semua_lokasi.length > 0) {
                                let html =
                                    `<div class="alert alert-info py-2"><h6><i class="fas fa-info-circle"></i> Stok Tersedia (Semua Kondisi):</h6><ul class="list-unstyled mb-0" style="font-size: 0.9em;">`;
                                res.semua_lokasi.forEach(function(item) {
                                    html += `<li>
                                    <i class="fas fa-map-marker-alt"></i> 
                                    <b>${item.gudang_nama}</b> 
                                    ${item.area_kode ? ' > ' + item.area_kode : ''} 
                                    ${item.rak_kode ? ' > Rack ' + item.rak_kode : ''}
                                </li>`;
                                });
                                html += `</ul></div>`;
                                $lokasiAsalList.html(html);
                            } else {
                                $lokasiAsalList.html(
                                    '<div class="alert alert-warning py-2 small">Tidak ada stok tercatat untuk produk ini.</div>'
                                );
                            }
                        },
                        error: function() {
                            $lokasiAsalList.html(''); // Silent fail
                        }
                    });
                } else {
                    $lokasiAsalList.html('');
                }
            }

            $jenisMutasi.on('change', function() {
                updateFormVisibility();
                fetchStock();
            });

            $produkSelect.on('change', fetchStock);
            $kondisiSelect.on('change', fetchStock);

            // Initial check
            updateFormVisibility();
        });
    </script>
</body>

</html>
