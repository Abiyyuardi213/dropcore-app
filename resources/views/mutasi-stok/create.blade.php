<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Mutasi Stok</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
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
                            <h1 class="m-0">Input Mutasi Stok</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <form action="{{ route('mutasi-stok.store') }}" method="POST">
                        @csrf

                        {{-- Top Section: Basic Info & Product --}}
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Data Mutasi</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Jenis Mutasi <span class="text-danger">*</span></label>
                                            <select name="jenis_mutasi" id="jenis_mutasi"
                                                class="form-control select2bs4">
                                                <option value="pindah" selected>Pindah Lokasi (Transfer)</option>
                                                <option value="masuk">Barang Masuk (Inbound)</option>
                                                <option value="keluar">Barang Keluar (Outbound)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal <span class="text-danger">*</span></label>
                                            <input type="date" name="tanggal_mutasi" class="form-control"
                                                value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Referensi (Opsional)</label>
                                            <input type="text" name="referensi" class="form-control"
                                                placeholder="Auto-Generate jika kosong">
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                {{-- Dynamic Product Section --}}
                                <div class="row">
                                    <div class="col-md-9">
                                        {{-- 1. Stock Selector (For Transfer/Outbound) --}}
                                        <div class="form-group" id="stok-selector-group" style="display: none;">
                                            <label>Pilih Stok Tersedia <span class="text-danger">*</span></label>
                                            <select id="stok_id_selector" class="form-control select2bs4"
                                                style="width: 100%;">
                                                <option value="">-- Pilih Stok Asal --</option>
                                                @foreach ($stoks as $stok)
                                                    <option value="{{ $stok->id }}"
                                                        data-produk="{{ $stok->produk_id }}"
                                                        data-kondisi="{{ $stok->kondisi_id }}"
                                                        data-gudang="{{ $stok->gudang_id }}"
                                                        data-area="{{ $stok->area_id }}" data-rak="{{ $stok->rak_id }}"
                                                        data-qty="{{ $stok->quantity }}">
                                                        {{ $stok->produk->name }}
                                                        | {{ $stok->kondisi->nama_kondisi ?? 'Standar' }}
                                                        | {{ $stok->gudang->nama_gudang }}
                                                        {{ $stok->area ? '> ' . $stok->area->kode_area : '' }}
                                                        {{ $stok->rak ? '> ' . $stok->rak->kode_rak : '' }}
                                                        (Qty: {{ $stok->quantity }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Memilih stok akan otomatis mengisi Produk,
                                                Kondisi, Lokasi Asal, dan Max Qty.</small>
                                        </div>

                                        {{-- 2. Manual Product Selection (For Inbound) --}}
                                        <div id="manual-product-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Produk <span class="text-danger">*</span></label>
                                                        <select name="produk_id" id="produk_id"
                                                            class="form-control select2bs4" required>
                                                            <option value="">-- Pilih Produk --</option>
                                                            @foreach ($products as $p)
                                                                <option value="{{ $p->id }}">
                                                                    {{ $p->name }} ({{ $p->sku }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Kondisi <span class="text-danger">*</span></label>
                                                        <select name="kondisi_id" id="kondisi_id"
                                                            class="form-control select2bs4" required>
                                                            @foreach ($kondisis as $k)
                                                                <option value="{{ $k->id }}">
                                                                    {{ $k->nama_kondisi }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 3. Quantity (Always Visible) --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jumlah (Qty) <span class="text-danger">*</span></label>
                                            <input type="number" name="quantity" class="form-control" min="1"
                                                placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                                </div>

                                {{-- Stock Info Card (Dynamic) --}}
                                <div id="stock-info-card" class="alert alert-info mt-3" style="display:none;">
                                    <h5><i class="fas fa-info-circle mr-1"></i> Informasi Stok</h5>
                                    <div class="stocks-list text-sm"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Locations Section: Side by Side --}}
                        <div class="row">
                            {{-- LEFT: Source --}}
                            <div class="col-md-6" id="col-asal">
                                <div class="card card-warning card-outline mb-3">
                                    <div class="card-header">
                                        <h3 class="card-title font-weight-bold text-warning">
                                            <i class="fas fa-dolly mr-1"></i> LOKASI ASAL (SUMBER)
                                        </h3>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="form-group">
                                            <label>Gudang Asal <span class="text-danger">*</span></label>
                                            <select name="gudang_asal_id" id="gudang_asal_id"
                                                class="form-control select2bs4 locations-source">
                                                <option value="">-- Pilih Gudang --</option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Area Asal</label>
                                                    <select name="area_asal_id" id="area_asal_id"
                                                        class="form-control select2bs4 locations-source">
                                                        <option value="">-- Pilih Area --</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Rak Asal</label>
                                                    <select name="rak_asal_id" id="rak_asal_id"
                                                        class="form-control select2bs4 locations-source">
                                                        <option value="">-- Pilih Rak --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: Destination --}}
                            <div class="col-md-6" id="col-tujuan">
                                <div class="card card-success card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title font-weight-bold text-success">
                                            <i class="fas fa-share-square mr-1"></i> LOKASI TUJUAN
                                        </h3>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="form-group">
                                            <label>Gudang Tujuan <span class="text-danger">*</span></label>
                                            <select name="gudang_tujuan_id" id="gudang_tujuan_id"
                                                class="form-control select2bs4 locations-dest">
                                                <option value="">-- Pilih Gudang --</option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Area Tujuan</label>
                                                    <select name="area_tujuan_id" id="area_tujuan_id"
                                                        class="form-control select2bs4 locations-dest">
                                                        <option value="">-- Pilih Area --</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Rak Tujuan</label>
                                                    <select name="rak_tujuan_id" id="rak_tujuan_id"
                                                        class="form-control select2bs4 locations-dest">
                                                        <option value="">-- Pilih Rak --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg btn-block shadow">
                                    <i class="fas fa-save mr-1"></i> SIMPAN MUTASI
                                </button>
                                <a href="{{ route('mutasi-stok.index') }}" class="btn btn-default btn-block mt-2">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Master Data from Controller
        const masterGudang = @json($gudangs);

        $(document).ready(function() {
            // Init Select2
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // --- Logic 1: Visible Sections & Fields based on Type
            $('#jenis_mutasi').change(function() {
                let val = $(this).val();

                // 1. Selector Mode Logic
                if (val === 'pindah' || val === 'keluar') {
                    // Mode: Existing Stock
                    $('#stok-selector-group').show();
                    $('#manual-product-group').hide();

                    $('#produk_id').removeAttr('required');
                    $('#kondisi_id').removeAttr('required');
                    $('#stok_id_selector').attr('required', true);

                    // Show Qty input that was moved out of manual group? 
                    // Wait, I moved Qty input out of manual group in HTML above? 
                    // Ah, I need to make sure I actually DID move it out in the HTML string I'm creating.
                    // Checking the HTML string I am about to write... 
                    // I put Qty inside `manual-product-group` in my previous logic, I need to extract it.
                    // See correction in HTML below.
                } else {
                    // Mode: Inbound (New Stock)
                    $('#stok-selector-group').hide();
                    $('#manual-product-group').show();

                    $('#produk_id').attr('required', true);
                    $('#kondisi_id').attr('required', true);
                    $('#stok_id_selector').removeAttr('required');
                }

                // 2. Location Columns Visibility
                if (val === 'pindah') {
                    $('#col-asal').show();
                    $('#col-tujuan').show();

                    $('#col-asal').removeClass('col-md-12').addClass('col-md-6');
                    $('#col-tujuan').removeClass('col-md-12').addClass('col-md-6');

                    requireFields('source', false); // filled by selector usually
                    requireFields('dest', true);
                } else if (val === 'masuk') {
                    $('#col-asal').hide();
                    $('#col-tujuan').show();

                    $('#col-tujuan').removeClass('col-md-6').addClass(
                        'col-md-12'); // Full width if only one

                    requireFields('source', false);
                    requireFields('dest', true);
                } else if (val === 'keluar') {
                    $('#col-asal').show();
                    $('#col-tujuan').hide();

                    $('#col-asal').removeClass('col-md-6').addClass('col-md-12'); // Full width if only one

                    requireFields('source', false);
                    requireFields('dest', false);
                }
            }).trigger('change');

            function requireFields(group, isRequired) {
                if (group === 'source') {
                    $('#gudang_asal_id').prop('required', isRequired);
                } else {
                    $('#gudang_tujuan_id').prop('required', isRequired);
                }
            }

            // --- Logic: Stock Selector Change
            $('#stok_id_selector').change(function() {
                let $opt = $(this).find(':selected');
                if (!$opt.val()) return;

                let data = $opt.data();

                // Fill Hidden Product/Condition triggers
                // Note: The manual Product/Condition dropdowns are hidden but we should set their values
                // so the backend receives 'produk_id' and 'kondisi_id' correctly if we use the same names.
                // Wait, if I hide the SELECT with name="produk_id", it still submits if not disabled.
                // But I removed 'required'. 
                // So I should set the value of the 'produk_id' SELECT to match the data.produk.

                $('#produk_id').val(data.produk).trigger('change');
                $('#kondisi_id').val(data.kondisi).trigger('change');

                // Since Qty is shared, we set Max
                // Wait, where is the shared Qty input? I need to ensure there is ONE Qty input.
                // In the HTML below, I will place Qty input in a common area.

                $('input[name="quantity"]').attr('max', data.qty).attr('placeholder', 'Max: ' + data.qty);

                // Fill Locations
                $('#gudang_asal_id').val(data.gudang).trigger('change');
                setTimeout(() => {
                    $('#area_asal_id').val(data.area).trigger('change');
                    setTimeout(() => {
                        $('#rak_asal_id').val(data.rak).trigger('change');
                    }, 100);
                }, 100);
            });

            // --- Logic: Cascading Locations
            function initGudangDropdowns() {
                let opts = '<option value="">-- Pilih Gudang --</option>';
                masterGudang.forEach(g => {
                    opts += `<option value="${g.id}">${g.nama_gudang}</option>`;
                });
                $('#gudang_asal_id').html(opts);
                $('#gudang_tujuan_id').html(opts);
            }
            initGudangDropdowns();

            function handleGudangChange(prefix) {
                let gudangId = $(`#gudang_${prefix}_id`).val();
                let areaSelect = $(`#area_${prefix}_id`);
                let rakSelect = $(`#rak_${prefix}_id`);

                areaSelect.html('<option value="">-- Pilih Area --</option>');
                rakSelect.html('<option value="">-- Pilih Rak --</option>');

                if (gudangId) {
                    let selectedGudang = masterGudang.find(g => g.id == gudangId);
                    if (selectedGudang && selectedGudang.areas) {
                        let opts = '<option value="">-- Pilih Area --</option>';
                        selectedGudang.areas.forEach(a => {
                            opts += `<option value="${a.id}">${a.nama_area} (${a.kode_area})</option>`;
                        });
                        areaSelect.html(opts);
                    }
                }
            }

            function handleAreaChange(prefix) {
                let gudangId = $(`#gudang_${prefix}_id`).val();
                let areaId = $(`#area_${prefix}_id`).val();
                let rakSelect = $(`#rak_${prefix}_id`);

                rakSelect.html('<option value="">-- Pilih Rak --</option>');

                if (gudangId && areaId) {
                    let selectedGudang = masterGudang.find(g => g.id == gudangId);
                    if (selectedGudang) {
                        let selectedArea = selectedGudang.areas.find(a => a.id == areaId);
                        if (selectedArea && selectedArea.raks) {
                            let opts = '<option value="">-- Pilih Rak --</option>';
                            selectedArea.raks.forEach(r => {
                                opts += `<option value="${r.id}">${r.kode_rak}</option>`;
                            });
                            rakSelect.html(opts);
                        }
                    }
                }
            }

            $('#gudang_asal_id').change(() => handleGudangChange('asal'));
            $('#area_asal_id').change(() => handleAreaChange('asal'));
            $('#gudang_tujuan_id').change(() => handleGudangChange('tujuan'));
            $('#area_tujuan_id').change(() => handleAreaChange('tujuan'));

            // --- Logic: Product Check (for Inbound)
            $('#produk_id').change(function() {
                // If we are in 'Masuk' mode, we might want to show total stock somewhere?
                // Or just leave it. The previous logic showed stock info.
                // We can keep that.
                let produkId = $(this).val();
                let $infoCard = $('#stock-info-card');
                let $list = $infoCard.find('.stocks-list');

                if (produkId) {
                    // Only fetch if displayed
                    // But wait, if we are in 'pindah' mode, product is auto selected. 
                    // So we can still show info.
                    $list.html('<i class="fas fa-sync fa-spin"></i> Loading...');
                    $infoCard.show();

                    $.ajax({
                        url: '{{ url('admin/lokasi-asal-produk') }}/' + produkId,
                        method: 'GET',
                        success: function(res) {
                            if (res.semua_lokasi && res.semua_lokasi.length > 0) {
                                let html = '<ul class="list-unstyled mb-0">';
                                res.semua_lokasi.forEach(s => {
                                    html +=
                                        `<li><i class="fas fa-map-marker-alt text-danger"></i> ${s.gudang_nama}, ${s.area_kode}, ${s.rak_kode} (Qty Available)</li>`;
                                });
                                html += '</ul>';
                                $list.html(html);
                            } else {
                                $list.html('Belum ada stok.');
                            }
                        }
                    });
                } else {
                    $infoCard.hide();
                }
            });
        });
    </script>
</body>

</html>
