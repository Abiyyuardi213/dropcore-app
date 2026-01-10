<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mutasi Stok</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
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
                            <h1 class="m-0">Edit Mutasi Stok</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit"></i> Form Perubahan Mutasi</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Mengubah data
                                mutasi tidak otomatis mengembalikan stok lama secara cerdas jika logika bisnis kompleks.
                                Pastikan perubahan ini valid secara fisik.
                            </div>

                            <form action="{{ route('mutasi-stok.update', $mutasi->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jenis_mutasi">Jenis Mutasi <span
                                                    class="text-danger">*</span></label>
                                            {{-- Jenis Mutasi tidak boleh diubah sembarangan jika sudah terjadi, tapi jika user mau maksa ubah, kita biarkan formnya ada --}}
                                            <select name="jenis_mutasi" id="jenis_mutasi" class="form-control select2"
                                                style="width: 100%;" required>
                                                <option value="pindah"
                                                    {{ old('jenis_mutasi', $mutasi->jenis_mutasi) == 'pindah' ? 'selected' : '' }}>
                                                    Pindah Lokasi (Transfer)</option>
                                                <option value="masuk"
                                                    {{ old('jenis_mutasi', $mutasi->jenis_mutasi) == 'masuk' ? 'selected' : '' }}>
                                                    Barang Masuk (Inbound / Supplier)</option>
                                                <option value="keluar"
                                                    {{ old('jenis_mutasi', $mutasi->jenis_mutasi) == 'keluar' ? 'selected' : '' }}>
                                                    Barang Keluar (Outbound / Usage)</option>
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
                                                                {{ old('produk_id', $mutasi->produk_id) == $produk->id ? 'selected' : '' }}>
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
                                                                {{ old('kondisi_id', $mutasi->kondisi_id) == $kondisi->id ? 'selected' : '' }}>
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
                                                        value="{{ old('quantity', $mutasi->quantity) }}" required
                                                        min="1" placeholder="0">
                                                    @error('quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="referensi">No. Referensi (Opsional)</label>
                                                    <input type="text" name="referensi"
                                                        class="form-control @error('referensi') is-invalid @enderror"
                                                        value="{{ old('referensi', $mutasi->referensi) }}"
                                                        placeholder="Contoh: PO-001, SJ-123">
                                                </div>

                                                <div class="form-group">
                                                    <label for="tanggal_mutasi">Tanggal Mutasi <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" name="tanggal_mutasi"
                                                        class="form-control @error('tanggal_mutasi') is-invalid @enderror"
                                                        value="{{ old('tanggal_mutasi', $mutasi->tanggal_mutasi) }}"
                                                        required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="keterangan">Keterangan</label>
                                                    <textarea name="keterangan" rows="3" class="form-control" placeholder="Catatan tambahan...">{{ old('keterangan', $mutasi->keterangan) }}</textarea>
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
                                                            <option value="{{ $gudang->id }}"
                                                                {{ old('gudang_asal_id', $mutasi->gudang_asal_id) == $gudang->id ? 'selected' : '' }}>
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
                                                                    {{ old('area_asal_id', $mutasi->area_asal_id) == $a->id ? 'selected' : '' }}>
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
                                                        {{-- Note: Ideally filter raks by area. For simplicity loading all available in view or fetch via JS. --}}
                                                        {{-- To do specific pre-selected, we iterate all and select matching --}}
                                                        @foreach ($gudangs as $g)
                                                            @foreach ($g->areas as $a)
                                                                @foreach ($a->raks as $r)
                                                                    <option value="{{ $r->id }}"
                                                                        {{ old('rak_asal_id', $mutasi->rak_asal_id) == $r->id ? 'selected' : '' }}>
                                                                        {{ $r->kode_rak }}</option>
                                                                @endforeach
                                                            @endforeach
                                                        @endforeach
                                                    </select>
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
                                                            <option value="{{ $gudang->id }}"
                                                                {{ old('gudang_tujuan_id', $mutasi->gudang_tujuan_id) == $gudang->id ? 'selected' : '' }}>
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
                                                                <option value="{{ $a->id }}"
                                                                    {{ old('area_tujuan_id', $mutasi->area_tujuan_id) == $a->id ? 'selected' : '' }}>
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
                                                        @foreach ($gudangs as $g)
                                                            @foreach ($g->areas as $a)
                                                                @foreach ($a->raks as $r)
                                                                    <option value="{{ $r->id }}"
                                                                        {{ old('rak_tujuan_id', $mutasi->rak_tujuan_id) == $r->id ? 'selected' : '' }}>
                                                                        {{ $r->kode_rak }}</option>
                                                                @endforeach
                                                            @endforeach
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
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        Perbarui Mutasi</button>
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

            function updateFormVisibility() {
                const jenis = $jenisMutasi.val();

                if (jenis === 'masuk') {
                    $containerAsal.hide();
                    $containerTujuan.show();
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
                const jenis = $jenisMutasi.val();
                // Don't auto-fetch locations on edit to avoid overwriting user selection or confusing layout, 
                // unless explicit user action? Or maybe just show info.
                // For now, let's show info but NOT reset selections.

                if (['keluar', 'pindah'].includes(jenis) && produkId) {
                    // ... (AJAX logic similar to create, but don't reset values)
                    $.ajax({
                        url: `/lokasi-asal-produk/${produkId}`,
                        method: 'GET',
                        success: function(res) {
                            if (res.semua_lokasi.length > 0) {
                                let html =
                                    `<div class="alert alert-info py-2"><h6><i class="fas fa-info-circle"></i> Stok Tersedia:</h6><ul class="list-unstyled mb-0" style="font-size: 0.9em;">`;
                                res.semua_lokasi.forEach(function(item) {
                                    html +=
                                        `<li><i class="fas fa-map-marker-alt"></i> <b>${item.gudang_nama}</b> ${item.area_kode ? '> ' + item.area_kode : ''} ${item.rak_kode ? '> ' + item.rak_kode : ''}</li>`;
                                });
                                html += `</ul></div>`;
                                $lokasiAsalList.html(html);
                            } else {
                                // Handle logic
                            }
                        }
                    });
                }
            }

            $jenisMutasi.on('change', updateFormVisibility);
            // Initial Check
            updateFormVisibility();
            // Optional: fetchStock();
        });
    </script>
</body>

</html>
