<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran Barang Baru - DropCore</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
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
                            <h1 class="m-0">Transaksi Pengeluaran Barang</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pengeluaran-barang.store') }}" method="POST" id="form-pengeluaran">
                        @csrf

                        {{-- HEADER --}}
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-file-invoice mr-1"></i> Data Pengeluaran</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No. Pengeluaran</label>
                                            <input type="text" class="form-control" name="no_pengeluaran_display"
                                                value="{{ $no_pengeluaran }}" readonly
                                                style="background-color: #fcf8e3; font-weight: bold;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tipe Penerima <span class="text-danger">*</span></label>
                                            <select name="tipe_penerima" id="tipe_penerima" class="form-control"
                                                required>
                                                <option value="distributor"
                                                    {{ old('tipe_penerima') == 'distributor' ? 'selected' : '' }}>
                                                    Distributor / Retur</option>
                                                <option value="konsumen"
                                                    {{ old('tipe_penerima') == 'konsumen' ? 'selected' : '' }}>Konsumen
                                                    / Pelanggan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Keluar <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="tanggal_pengeluaran"
                                                value="{{ old('tanggal_pengeluaran', date('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No. Referensi</label>
                                            <input type="text" class="form-control" name="referensi"
                                                value="{{ old('referensi') }}" placeholder="No. Invoice / PO">
                                        </div>
                                    </div>
                                </div>

                                {{-- Dynamic Section based on Type --}}
                                <div id="section-distributor" class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pilih Distributor <span class="text-danger">*</span></label>
                                            <select name="distributor_id" class="form-control select2">
                                                <option value="">-- Pilih Distributor --</option>
                                                @foreach ($distributors as $d)
                                                    <option value="{{ $d->id }}">{{ $d->nama_distributor }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="section-konsumen" class="row" style="display:none;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nama Konsumen <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_konsumen" class="form-control"
                                                placeholder="Nama Lengkap">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" name="telepon_konsumen" class="form-control"
                                                placeholder="08...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <input type="text" name="alamat_konsumen" class="form-control"
                                                placeholder="Alamat pengiriman">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Akun Penerimaan (Masuk ke Saldo) <span
                                                    class="text-danger">*</span></label>
                                            <select name="sumber_id" class="form-control select2" required>
                                                <option value="">-- Pilih Akun Penerimaan --</option>
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
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="1" placeholder="Catatan...">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- DETAILS --}}
                        <div class="card card-warning card-outline">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-boxes mr-1"></i> Item Barang Keluar</h3>
                                <button type="button" class="btn btn-success btn-sm" id="btn-add-row"><i
                                        class="fas fa-plus"></i> Tambah Item</button>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-bordered table-striped" id="table-items">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 25%;">Produk <span class="text-danger">*</span></th>
                                            <th style="width: 30%;">Pilih Stok (Batch) <span
                                                    class="text-danger">*</span></th>
                                            <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
                                            <th style="width: 15%;">Harga Jual (Opsional)</th>
                                            <th style="width: 15%;">Subtotal</th>
                                            <th style="width: 5%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right text-bold">Total Nilai:</td>
                                            <td colspan="2"><span id="total-value" class="text-bold">Rp 0</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="empty-state" class="text-center py-5">
                                    <p class="text-muted">Klik "Tambah Item" untuk memilih barang.</p>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="submit_action" id="submitAction" value="process">
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-end align-items-center">
                                <a href="{{ route('pengeluaran-barang.index') }}" class="btn btn-secondary mr-2"
                                    style="min-width: 120px;">
                                    <i class="fas fa-times mr-1"></i> Batal
                                </a>
                                <button type="button" class="btn btn-warning mr-2" onclick="submitForm('draft')"
                                    style="min-width: 180px;">
                                    <i class="fas fa-save mr-1"></i> Simpan Draft
                                </button>
                                <button type="button" class="btn btn-success" onclick="submitForm('process')"
                                    style="min-width: 180px;">
                                    <i class="fas fa-check-circle mr-1"></i> Proses & Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        @include('include.footerSistem')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const products = @json($products);

        // Global Submit Handler
        window.submitForm = function(action) {
            $('#submitAction').val(action);
            $('#form-pengeluaran').submit();
        }

        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Toggle Type
            $('#tipe_penerima').change(function() {
                if ($(this).val() === 'distributor') {
                    $('#section-distributor').show();
                    $('#section-konsumen').hide();
                } else {
                    $('#section-distributor').hide();
                    $('#section-konsumen').show();
                }
            }).trigger('change');

            let rowIndex = 0;

            // Add Row
            $('#btn-add-row').click(function() {
                $('#empty-state').hide();

                let productOpts = '<option value="">-- Pilih Produk --</option>';
                products.forEach(p => productOpts +=
                    `<option value="${p.id}">${p.name} (${p.code || '-'})</option>`);

                const tr = `
                <tr id="row-${rowIndex}">
                    <td>
                        <select class="form-control select2-product" data-index="${rowIndex}">
                            ${productOpts}
                        </select>
                        <input type="hidden" name="items[${rowIndex}][produk_id]" class="input-produk-id">
                    </td>
                    <td>
                        <select class="form-control select2-stok" disabled data-index="${rowIndex}">
                            <option value="">Pilih Produk Dulu</option>
                        </select>
                        {{-- Hidden fields to store chosen stock location --}}
                        <input type="hidden" name="items[${rowIndex}][gudang_id]" class="input-gudang">
                        <input type="hidden" name="items[${rowIndex}][area_id]" class="input-area">
                        <input type="hidden" name="items[${rowIndex}][rak_id]" class="input-rak">
                        <input type="hidden" name="items[${rowIndex}][kondisi_id]" class="input-kondisi">
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" name="items[${rowIndex}][qty]" class="form-control input-qty" min="1" disabled placeholder="0">
                            <div class="input-group-append">
                                <span class="input-group-text text-xs">Max: <span class="max-qty">0</span></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                           <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                           <input type="number" name="items[${rowIndex}][harga]" class="form-control input-price" min="0" placeholder="0">
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control input-subtotal" readonly value="Rp 0" style="background-color: #f8f9fa;">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
                $('#table-items tbody').append(tr);
                $(`#row-${rowIndex} .select2-product`).select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
                rowIndex++;
            });

            // Remove Row
            $(document).on('click', '.btn-remove', function() {
                $(this).closest('tr').remove();
                calculateTotal();
                if ($('#table-items tbody').children().length === 0) $('#empty-state').show();
            });

            // On Product Change -> Fetch Stock
            $(document).on('change', '.select2-product', function() {
                const index = $(this).data('index');
                const produkId = $(this).val();
                const row = $(`#row-${index}`);

                row.find('.input-produk-id').val(produkId);
                const stockSelect = row.find('.select2-stok');

                stockSelect.empty().append('<option value="">Loading...</option>').prop('disabled', true);
                row.find('.input-qty').prop('disabled', true).val('');
                row.find('.max-qty').text('0');

                if (!produkId) return;

                // CORRECTION: Admin Prefix added
                $.get(`/admin/stok/by-produk/${produkId}`, function(data) {
                    stockSelect.empty().append('<option value="">-- Pilih Batch Stok --</option>');
                    if (data.length > 0) {
                        stockSelect.prop('disabled', false);
                        data.forEach(stok => {
                            const namaGudang = stok.gudang ? stok.gudang.nama_gudang :
                                'Unknown';
                            const area = stok.area ? stok.area.nama_area : '-';
                            const rak = stok.rak ? stok.rak.kode_rak : '-';
                            const kondisi = stok.kondisi ? stok.kondisi.nama_kondisi : '-';

                            const label =
                                `${namaGudang} [${area}-${rak}] (${kondisi}) : Sisa ${stok.quantity}`;

                            const option = $(
                                `<option value="${stok.id}">${label}</option>`);
                            option.data('stok', stok);
                            stockSelect.append(option);
                        });
                    } else {
                        stockSelect.append('<option value="">Stok Habis / Tidak Tersedia</option>');
                    }
                }).fail(function() {
                    stockSelect.empty().append('<option value="">Gagal memuat stok</option>');
                    console.error('Failed to fetch stock');
                });
            });

            // On Stock Batch Change
            $(document).on('change', '.select2-stok', function() {
                const index = $(this).data('index');
                const row = $(`#row-${index}`);
                const selectedOption = $(this).find('option:selected');
                const stokData = selectedOption.data('stok');

                if (stokData) {
                    row.find('.input-gudang').val(stokData.gudang_id);
                    row.find('.input-area').val(stokData.area_id);
                    row.find('.input-rak').val(stokData.rak_id);
                    row.find('.input-kondisi').val(stokData.kondisi_id || (stokData.kondisi ? stokData
                        .kondisi.id : ''));

                    const qtyInput = row.find('.input-qty');
                    qtyInput.prop('disabled', false);
                    qtyInput.attr('max', stokData.quantity);
                    row.find('.max-qty').text(stokData.quantity);
                } else {
                    row.find('.input-qty').prop('disabled', true);
                    row.find('.max-qty').text('0');
                }
            });

            // Calc
            $(document).on('input', '.input-qty, .input-price', function() {
                const row = $(this).closest('tr');
                const qty = parseFloat(row.find('.input-qty').val()) || 0;
                const max = parseFloat(row.find('.input-qty').attr('max')) || 0;

                if (qty > max && max > 0) {
                    row.find('.input-qty').addClass('is-invalid');
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: `Stok hanya tersedia ${max}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    row.find('.input-qty').val(max);
                } else {
                    row.find('.input-qty').removeClass('is-invalid');
                }

                const price = parseFloat(row.find('.input-price').val()) || 0;
                row.find('.input-subtotal').val('Rp ' + (qty * price).toLocaleString('id-ID'));
                calculateTotal();
            });

            function calculateTotal() {
                let total = 0;
                $('#table-items tbody tr').each(function() {
                    const row = $(this);
                    const qty = parseFloat(row.find('.input-qty').val()) || 0;
                    const price = parseFloat(row.find('.input-price').val()) || 0;
                    total += (qty * price);
                });
                $('#total-value').text('Rp ' + total.toLocaleString('id-ID'));
            }

            // Prevent submit empty
            $('#form-pengeluaran').on('submit', function(e) {
                if ($('#table-items tbody tr').length === 0) {
                    e.preventDefault();
                    Swal.fire('Error', 'Minimal satu item barang harus dipilih.', 'error');
                    return;
                }
                let valid = true;
                $('.input-qty').each(function() {
                    if ($(this).hasClass('is-invalid')) valid = false;
                });

                if (!valid) {
                    e.preventDefault();
                    Swal.fire('Error', 'Input quantity melebihi stok yang tersedia.', 'error');
                }
            });
        });
    </script>
</body>

</html>
