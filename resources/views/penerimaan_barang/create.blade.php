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
        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 24px !important;
            padding-top: 5px;
        }

        .table-input {
            min-width: 150px;
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
                                            <div class="d-flex align-items-center mt-2">
                                                <div class="custom-control custom-radio mr-3">
                                                    <input class="custom-control-input" type="radio"
                                                        id="sourceSupplier" name="tipe_pengirim" value="supplier"
                                                        checked>
                                                    <label for="sourceSupplier"
                                                        class="custom-control-label">Supplier</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio"
                                                        id="sourceDistributor" name="tipe_pengirim" value="distributor">
                                                    <label for="sourceDistributor"
                                                        class="custom-control-label">Distributor</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="supplier-wrapper">
                                        <div class="form-group">
                                            <label>Pilih Supplier <span class="text-danger">*</span></label>
                                            <select name="supplier_id" class="form-control select2" id="supplierSelect">
                                                <option value="">-- Pilih Supplier --</option>
                                                @foreach ($suppliers as $s)
                                                    <option value="{{ $s->id }}"
                                                        {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                                        {{ $s->nama_supplier }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="distributor-wrapper" style="display: none;">
                                        <div class="form-group">
                                            <label>Pilih Distributor <span class="text-danger">*</span></label>
                                            <select name="distributor_id" class="form-control select2"
                                                id="distributorSelect" disabled>
                                                <option value="">-- Pilih Distributor --</option>
                                                @foreach ($distributors as $d)
                                                    <option value="{{ $d->id }}"
                                                        {{ old('distributor_id') == $d->id ? 'selected' : '' }}>
                                                        {{ $d->nama_distributor }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Sumber Dana (Pembayaran) <span class="text-danger">*</span></label>
                                            <select name="sumber_id" class="form-control select2" required>
                                                <option value="">-- Pilih Akun Pembayaran --</option>
                                                @foreach ($sumberKeuangan as $sumber)
                                                    <option value="{{ $sumber->id }}"
                                                        {{ old('sumber_id') == $sumber->id ? 'selected' : '' }}>
                                                        {{ $sumber->nama_sumber }}
                                                        ({{ number_format($sumber->saldo, 0, ',', '.') }})
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
                                                value="{{ old('referensi') }}" placeholder="Contoh: SJ-001/PT-ABC">
                                        </div>
                                    </div>
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
                                <button type="button" class="btn btn-success btn-sm" id="btn-add-row"><i
                                        class="fas fa-plus"></i> Tambah Barang</button>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-bordered table-striped" id="table-items">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 20%;">Produk <span class="text-danger">*</span></th>
                                            <th style="width: 15%;">Kondisi <span class="text-danger">*</span></th>
                                            <th style="width: 15%;">Lokasi (Gudang/Area/Rak) <span
                                                    class="text-danger">*</span></th>
                                            <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
                                            <th style="width: 15%;">Harga Satuan (Opsional)</th>
                                            <th style="width: 15%;">Subtotal</th>
                                            <th style="width: 5%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Rows added via JS --}}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right text-bold">Total Estimasi Nilai:</td>
                                            <td colspan="2"><span id="total-value" class="text-bold">Rp 0</span>
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

    @include('services.logoutModal')

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
            // Toggle Source Logic
            $('input[name="tipe_pengirim"]').change(function() {
                if ($(this).val() === 'supplier') {
                    $('#supplier-wrapper').show();
                    $('#distributor-wrapper').hide();
                    $('#supplierSelect').prop('disabled', false).prop('required', true);
                    $('#distributorSelect').prop('disabled', true).prop('required', false);
                } else {
                    $('#supplier-wrapper').hide();
                    $('#distributor-wrapper').show();
                    $('#supplierSelect').prop('disabled', true).prop('required', false);
                    $('#distributorSelect').prop('disabled', false).prop('required', true);
                }
            });

            // Trigger change initially
            $('input[name="tipe_pengirim"]:checked').trigger('change');

            // Global Submit Function
            window.submitForm = function(action) {
                $('#submitAction').val(action);
                $('#form-penerimaan').submit();
            }

            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            let rowIndex = 0;

            function addRow() {
                $('#empty-state').hide();

                // Build Options Strings
                let productOpts = '<option value="">-- Produk --</option>';
                products.forEach(p => productOpts +=
                    `<option value="${p.id}">${p.name} (${p.code || '-'})</option>`);

                let kondisiOpts = '';
                kondisis.forEach(k => kondisiOpts += `<option value="${k.id}">${k.nama_kondisi}</option>`);

                let gudangOpts = '<option value="">-- Gudang --</option>';
                gudangs.forEach(g => gudangOpts += `<option value="${g.id}">${g.nama_gudang}</option>`);

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
                             <select name="items[${rowIndex}][gudang_id]" class="form-control select2-sm gudang-select check-required" data-index="${rowIndex}" required>
                                ${gudangOpts}
                            </select>
                        </div>
                        <div class="d-flex">
                            <select name="items[${rowIndex}][area_id]" class="form-control select2-sm area-select mr-1 check-required" data-index="${rowIndex}" required disabled>
                                <option value="">Area</option>
                            </select>
                            <select name="items[${rowIndex}][rak_id]" class="form-control select2-sm rak-select check-required" required disabled>
                                <option value="">Rak</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <input type="number" name="items[${rowIndex}][qty]" class="form-control qty-input" min="1" value="1" required>
                    </td>
                    <td>
                        <div class="input-group">
                           <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                           <input type="number" name="items[${rowIndex}][harga]" class="form-control price-input" min="0" placeholder="0">
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

            // Remove Row
            $(document).on('click', '.btn-remove', function() {
                $(this).closest('tr').remove();
                calculateTotal();
                if ($('#table-items tbody tr').length === 0) $('#empty-state').show();
            });

            // Dynamic Location Logic
            $(document).on('change', '.gudang-select', function() {
                const index = $(this).data('index');
                const gudangId = $(this).val();
                const $areaSelect = $(`#row-${index} .area-select`);
                const $rakSelect = $(`#row-${index} .rak-select`);

                $areaSelect.empty().append('<option value="">Area</option>').prop('disabled', true);
                $rakSelect.empty().append('<option value="">Rak</option>').prop('disabled', true);

                if (gudangId) {
                    const selectedGudang = gudangs.find(g => g.id == gudangId);
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
                        $rakSelect.append(`<option value="${rak.id}">${rak.kode_rak}</option>`);
                    });
                } else if ($(this).val()) {
                    $rakSelect.append('<option value="">Tidak ada rak</option>');
                }
            });

            // Calculations
            $(document).on('input', '.qty-input, .price-input', function() {
                const row = $(this).closest('tr');
                const qty = parseFloat(row.find('.qty-input').val()) || 0;
                const price = parseFloat(row.find('.price-input').val()) || 0;
                const subtotal = qty * price;

                row.find('.subtotal-display').val('Rp ' + subtotal.toLocaleString('id-ID'));
                calculateTotal();
            });

            function calculateTotal() {
                let total = 0;
                $('#table-items tbody tr').each(function() {
                    const qty = parseFloat($(this).find('.qty-input').val()) || 0;
                    const price = parseFloat($(this).find('.price-input').val()) || 0;
                    total += (qty * price);
                });
                $('#total-value').text('Rp ' + total.toLocaleString('id-ID'));
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
