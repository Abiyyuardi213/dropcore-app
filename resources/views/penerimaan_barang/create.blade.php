<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Barang Baru - DropCore</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <!-- AdminLTE & Dependencies -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        .select2-container--bootstrap4 .select2-selection--single {
            display: flex;
            align-items: center;
            height: 38px;
            padding: 0;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            width: 100%;
            padding: 0 2rem 0 .75rem;
            line-height: 1.5;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            top: 0;
            right: .5rem;
            height: 100%;
        }

        .table-input {
            min-width: 150px;
        }

        #table-items {
            table-layout: fixed;
            min-width: 1400px;
            width: 100%;
        }

        #table-items th,
        #table-items td {
            padding: .75rem;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        #table-items .select2-container {
            min-width: 0;
            max-width: 100%;
        }

        #table-items .location-details > .select2-container {
            flex: 1 1 0;
            width: 0 !important;
        }

        #table-items .location-details {
            gap: .25rem;
        }

        #table-items .input-group {
            flex-wrap: nowrap;
        }

        #table-items .qty-input,
        #table-items .price-input,
        #table-items .subtotal-display {
            text-align: right;
            padding-left: .5rem;
            padding-right: .5rem;
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
                            <h1 class="m-0">Transaksi Penerimaan Barang</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        </div>
                    @endif
                    {{-- Global Error Validation Display --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('penerimaan-barang.store') }}" method="POST" id="form-penerimaan">
                        @csrf

                        {{-- HEADER --}}
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-file-alt mr-1"></i> Data Penerimaan</h3>
                                <div class="card-tools">
                                    <span class="badge badge-warning">Draft</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No. Penerimaan</label>
                                            <input type="text" class="form-control" name="no_penerimaan_display"
                                                value="{{ $no_penerimaan }}" readonly
                                                style="background-color: #f4f6f9; font-weight: bold;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Sumber Pengirim <span class="text-danger">*</span></label>
                                            <input type="hidden" name="tipe_pengirim" value="supplier">
                                            <input type="text" class="form-control" value="Supplier" readonly aria-label="Sumber pengirim">
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="supplier-wrapper">
                                        <div class="form-group">
                                            <label>Pilih Supplier <span class="text-danger">*</span></label>
                                            <select name="supplier_id" class="form-control select2" id="supplierSelect" required>
                                                <option value="">-- Pilih Supplier --</option>
                                                @foreach ($suppliers as $s)
                                                    <option value="{{ $s->id }}"
                                                        data-kode="{{ $s->kode_supplier }}"
                                                        data-telp="{{ $s->no_telepon }}"
                                                        data-alamat="{{ \Illuminate\Support\Str::limit($s->alamat, 30) }}"
                                                        {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                                        {{ $s->nama_supplier }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Akun Pembayaran (jika lunas)</label>
                                            <select name="sumber_id" class="form-control select2" id="sumberSelect">
                                                <option value="">-- Pilih Akun Pembayaran --</option>
                                                @foreach ($sumberKeuangan as $sumber)
                                                    <option value="{{ $sumber->id }}"
                                                        data-saldo="{{ number_format($sumber->saldo, 0, ',', '.') }}"
                                                        data-jenis="{{ $sumber->jenis }}"
                                                        data-norek="{{ $sumber->nomor_rekening }}"
                                                        {{ old('sumber_id') == $sumber->id ? 'selected' : '' }}>
                                                        {{ $sumber->nama_sumber }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Terima <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="tanggal_penerimaan"
                                                value="{{ old('tanggal_penerimaan', date('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No. Referensi (SJ/PO)</label>
                                            <input type="text" class="form-control" name="referensi"
                                                value="{{ old('referensi', $auto_referensi ?? '') }}" placeholder="Contoh: RF-A8X9K2">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="payment_status">Status Pembayaran</label>
                                    <select id="payment_status" name="payment_status" class="form-control">
                                        <option value="unpaid" @selected(old('payment_status', 'unpaid') == 'unpaid')>Belum dibayar (Utang supplier)</option>
                                        <option value="paid" @selected(old('payment_status') == 'paid')>Lunas</option>
                                    </select>
                                    <small class="text-muted">Stok diproses terpisah dari pembayaran. Saldo hanya berubah saat lunas. Draft tidak mencatat pembayaran.</small>
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- DETAILS --}}
                        <div class="card card-primary card-outline">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-boxes mr-1"></i> Detail Barang Masuk</h3>
                                <button type="button" class="btn btn-success btn-sm ml-auto" id="btn-add-row"><i
                                        class="fas fa-plus"></i> Tambah Barang</button>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-bordered table-striped" id="table-items">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 25%;">Produk <span class="text-danger">*</span></th>
                                            <th style="width: 10%;">Kondisi <span class="text-danger">*</span></th>
                                            <th style="width: 26%;">Lokasi (Gudang/Area/Rak) <span
                                                    class="text-danger">*</span></th>
                                            <th style="width: 8%;">Qty <span class="text-danger">*</span></th>
                                            <th style="width: 13%;">Harga Beli</th>
                                            <th style="width: 13%;">Subtotal</th>
                                            <th style="width: 5%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Rows added via JS --}}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right text-bold">Subtotal:</td>
                                            <td colspan="2"><span id="subtotal-value" class="text-bold">Rp
                                                    0</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right text-bold">PPN (10%):</td>
                                            <td colspan="2"><span id="ppn-value" class="text-bold">Rp 0</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right text-bold">Total Bayar:</td>
                                            <td colspan="2"><span id="grand-total-value" class="text-bold">Rp
                                                    0</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="empty-state" class="text-center py-5">
                                    <p class="text-muted"><i class="fas fa-inbox fa-3x mb-3"></i><br>Belum ada barang
                                        ditambahkan.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Input for Action --}}
                        <input type="hidden" name="submit_action" id="submitAction" value="process">

                        <div class="row mb-5">
                            <div class="col-12 text-right">
                                <a href="{{ route('penerimaan-barang.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="button" class="btn btn-warning mr-2" onclick="submitForm('draft')">
                                    <i class="fas fa-save"></i> Simpan Draft (Pending)
                                </button>
                                <button type="button" class="btn btn-primary btn-lg"
                                    onclick="submitForm('process')">
                                    <i class="fas fa-check-circle"></i> Proses Stok (Final)
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    @include('services.LogoutModal')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Data injection for JS --}}
    <script>
        const products = @json($products);
        const kondisis = @json($kondisis);
        const gudangs = @json($gudangs);
    </script>

    <script>
        $(document).ready(function() {
            // Global Submit Function
            window.submitForm = function(action) {
                $('#submitAction').val(action);
                $('[name="sumber_id"]').prop('required', action === 'process' && $('#payment_status').val() === 'paid');
                $('#form-penerimaan').submit();
            }

            // Formatters
            function formatSupplier(state) {
                if (!state.id) return state.text;
                var kode = $(state.element).data('kode');
                var telp = $(state.element).data('telp');
                var alamat = $(state.element).data('alamat');

                // Detailed view for dropdown list
                var $state = $(
                    '<div class="d-flex flex-column py-1">' +
                    '<span class="font-weight-bold">' + state.text + ' <small class="text-muted">(' + kode +
                    ')</small></span>' +
                    '<span class="text-muted small"><i class="fas fa-phone-alt mr-1"></i> ' + telp +
                    ' &bull; <i class="fas fa-map-marker-alt mr-1"></i> ' + alamat + '</span>' +
                    '</div>'
                );
                return $state;
            }

            function formatSupplierSelection(state) {
                if (!state.id) return state.text;
                var kode = $(state.element).data('kode');
                return $('<span>' + state.text + ' (' + kode + ')</span>');
            }

            function formatSumber(state) {
                if (!state.id) return state.text;
                var saldo = $(state.element).data('saldo');
                var jenis = $(state.element).data('jenis');
                var norek = $(state.element).data('norek');

                var icon = (String(jenis).toLowerCase().includes('bank')) ? 'fa-landmark' : 'fa-wallet';

                var $state = $(
                    '<div class="d-flex justify-content-between align-items-center w-100 py-1">' +
                    '<div><i class="fas ' + icon + ' mr-2 text-secondary"></i><strong>' + state.text +
                    '</strong><br><small class="text-muted pl-4">' + (norek ? norek : jenis) +
                    '</small></div>' +
                    '<div class="text-right"><span class="badge badge-light border">Rp ' + saldo +
                    '</span></div>' +
                    '</div>'
                );
                return $state;
            }

            function formatSumberSelection(state) {
                if (!state.id) return state.text;
                var saldo = $(state.element).data('saldo');
                return $('<span>' + state.text + ' (Saldo: Rp ' + saldo + ')</span>');
            }

            // Init Generic Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Init Specific Select2 with Templates
            $('#supplierSelect').select2({
                theme: 'bootstrap4',
                width: '100%',
                templateResult: formatSupplier,
                templateSelection: formatSupplierSelection
            });

            $('#sumberSelect').select2({
                theme: 'bootstrap4',
                width: '100%',
                templateResult: formatSumber,
                templateSelection: formatSumberSelection
            });

            let rowIndex = 0;

            function addRow() {
                $('#empty-state').hide();

                // Build Options Strings
                let productOpts = '<option value="">-- Produk --</option>';
                products.forEach(p => productOpts +=
                    `<option value="${p.id}">${p.name} (${p.sku || '-'})</option>`);

                let kondisiOpts = '';
                kondisis.forEach(k => kondisiOpts += `<option value="${k.id}">${k.nama_kondisi}</option>`);

                let gudangOpts = '<option value="">-- Pilih produk dahulu --</option>';

                const tr = `
                <tr id="row-${rowIndex}">
                    <td>
                        <select name="items[${rowIndex}][produk_id]" class="form-control select2-product check-required" required>
                            ${productOpts}
                        </select>
                    </td>
                    <td>
                        <select name="items[${rowIndex}][kondisi_id]" class="form-control select2-sm check-required" required>
                            ${kondisiOpts}
                        </select>
                    </td>
                    <td>
                        <div class="mb-1">
                             <select name="items[${rowIndex}][gudang_id]" class="form-control select2-sm gudang-select check-required" data-index="${rowIndex}" required disabled>
                                 ${gudangOpts}
                            </select>
                        </div>
                        <div class="d-flex location-details">
                            <select name="items[${rowIndex}][area_id]" class="form-control select2-sm area-select mr-1 check-required" data-index="${rowIndex}" required disabled>
                                <option value="">Area</option>
                            </select>
                            <select name="items[${rowIndex}][rak_id]" class="form-control select2-sm rak-select check-required" required disabled>
                                <option value="">Rak</option>
                            </select>
                        </div>
                        <small class="location-hint text-muted d-block mt-1"></small>
                    </td>
                    <td>
                        <input type="number" name="items[${rowIndex}][qty]" class="form-control qty-input" min="1" value="1" required>
                    </td>
                    <td>
                        <div class="input-group">
                           <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                           <input type="number" name="items[${rowIndex}][harga]" class="form-control price-input" min="0" step="0.01" required aria-label="Harga beli satuan">
                        </div>
                        <div class="small text-muted mt-2" aria-live="polite">
                            <div>Harga jual produk: <strong class="product-selling-price">—</strong></div>
                            <div>Harga beli master: <span class="product-purchase-price">—</span></div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control subtotal-display" readonly value="Rp 0">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-remove" data-id="${rowIndex}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;

                $('#table-items tbody').append(tr);

                // Init Select2 for new row
                $(`#row-${rowIndex} .select2-product`).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Pilih Produk'
                });
                $(`#row-${rowIndex} .select2-sm`).select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });

                rowIndex++;
            }

            // Add Row Button
            $('#btn-add-row').click(addRow);

            $(document).on('change', '.select2-product', function() {
                const product = products.find(product => String(product.id) === String($(this).val()));
                const price = product?.purchase_price ?? '';
                const row = $(this).closest('tr');
                const formatReferencePrice = value => value === null || value === undefined || value === ''
                    ? 'Belum diisi'
                    : 'Rp ' + Number(value).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                row.find('.product-selling-price').text(product ? formatReferencePrice(product.price) : '—');
                row.find('.product-purchase-price').text(product ? formatReferencePrice(product.purchase_price) : '—');
                row.find('.price-input').val(price).trigger('input');
                const locations = getProductWarehouses($(this).val());
                row.data('available-warehouses', locations);
                const warehouse = row.find('.gudang-select');
                warehouse.empty().append(new Option('-- Gudang --', ''));
                locations.forEach(location => warehouse.append(new Option(location.nama_gudang, location.id)));
                warehouse.prop('disabled', !product).val('').trigger('change');
                row.find('.location-hint').text(product ? 'Pilih lokasi tujuan penyimpanan barang masuk.' : '');
                if (product && locations.length === 1) warehouse.val(locations[0].id).trigger('change');
            });

            // Remove Row
            $(document).on('click', '.btn-remove', function() {
                $(this).closest('tr').remove();
                calculateTotal();
                if ($('#table-items tbody tr').length === 0) $('#empty-state').show();
            });

            function getProductWarehouses(productId) {
                return productId ? gudangs : [];
            }

            // Dynamic Location Logic
            $(document).on('change', '.gudang-select', function() {
                const index = $(this).data('index');
                const gudangId = $(this).val();
                const $areaSelect = $(`#row-${index} .area-select`);
                const $rakSelect = $(`#row-${index} .rak-select`);

                $areaSelect.empty().append('<option value="">Area</option>').prop('disabled', true);
                $rakSelect.empty().append('<option value="">Rak</option>').prop('disabled', true);

                if (gudangId) {
                    const available = $(this).closest('tr').data('available-warehouses') || [];
                    const selectedGudang = available.find(g => g.id == gudangId);
                    if (selectedGudang && selectedGudang.areas) {
                        $areaSelect.prop('disabled', false);
                        selectedGudang.areas.forEach(area => {
                            // Store raks in data attribute
                            const opt = $(
                                `<option value="${area.id}">${area.kode_area} - ${area.nama_area}</option>`
                            );
                            opt.data('raks', area.raks);
                            $areaSelect.append(opt);
                        });
                        if (selectedGudang.areas.length === 1) {
                            $areaSelect.val(selectedGudang.areas[0].id).trigger('change');
                        }
                    }
                }
            });

            $(document).on('change', '.area-select', function() {
                const index = $(this).data('index');
                // Get selected option data
                const $selectedOption = $(this).find('option:selected');
                const raks = $selectedOption.data('raks');
                const $rakSelect = $(`#row-${index} .rak-select`);

                $rakSelect.empty().append('<option value="">Rak</option>').prop('disabled', true);

                if (raks && raks.length > 0) {
                    $rakSelect.prop('disabled', false);
                    raks.forEach(rak => {
                        $rakSelect.append(new Option(rak.kode_rak, rak.id));
                    });
                    if (raks.length === 1) $rakSelect.val(raks[0].id).trigger('change');
                } else if ($(this).val()) {
                    $rakSelect.append('<option value="">Tidak ada rak</option>');
                }
            });

            // Format Currency Input
            $(document).on('keyup', '.currency-input', function() {
                let value = $(this).val().replace(/[^,\d]/g, '').toString();
                let split = value.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                $(this).val(rupiah);
            });

            // Calculations
            $(document).on('input', '.qty-input, .price-input', function() {
                const row = $(this).closest('tr');
                const qty = parseFloat(row.find('.qty-input').val()) || 0;

                const price = parseFloat(row.find('.price-input').val()) || 0;

                const subtotal = Math.round(qty * price * 100) / 100;

                row.find('.subtotal-display').val('Rp ' + subtotal.toLocaleString('id-ID'));
                calculateTotal();
            });

            function calculateTotal() {
                let subtotal = 0;
                $('#table-items tbody tr').each(function() {
                    const qty = parseFloat($(this).find('.qty-input').val()) || 0;

                    const price = parseFloat($(this).find('.price-input').val()) || 0;

                    subtotal += Math.round(qty * price * 100) / 100;
                });

                const ppn = Math.round(subtotal * 0.10 * 100) / 100;
                const grandTotal = subtotal + ppn;

                $('#subtotal-value').text('Rp ' + subtotal.toLocaleString('id-ID'));
                $('#ppn-value').text('Rp ' + ppn.toLocaleString('id-ID'));
                $('#grand-total-value').text('Rp ' + grandTotal.toLocaleString('id-ID'));
            }

            // Form Submit Validation
            $('#form-penerimaan').on('submit', function(e) {
                if ($('#table-items tbody tr').length === 0) {
                    e.preventDefault();
                    Swal.fire('Error', 'Harap tambahkan minimal satu barang.', 'error');
                    return false;
                }
            });

            // Add initial row
            addRow();
        });
    </script>
</body>

</html>
