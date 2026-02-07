<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stok</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        .badge-large {
            font-size: 90%;
            padding: 5px 10px;
        }

        table.dataTable td,
        table.dataTable th {
            vertical-align: middle;
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
                            <h1 class="m-0">Inventaris Stok Gudang</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    {{-- Filter Section --}}
                    <div class="card card-outline card-secondary collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data Stok</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('stok.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Produk</label>
                                            <select name="produk_id" class="form-control select2bs4">
                                                <option value="">-- Semua Produk --</option>
                                                @foreach ($produks as $produk)
                                                    <option value="{{ $produk->id }}"
                                                        {{ request('produk_id') == $produk->id ? 'selected' : '' }}>
                                                        {{ $produk->name }} ({{ $produk->sku }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Gudang</label>
                                            <select name="gudang_id" class="form-control select2bs4">
                                                <option value="">-- Semua Gudang --</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}"
                                                        {{ request('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                                        {{ $gudang->nama_gudang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kondisi</label>
                                            <select name="kondisi_id" class="form-control">
                                                <option value="">-- Semua Kondisi --</option>
                                                @foreach ($kondisis as $kondisi)
                                                    <option value="{{ $kondisi->id }}"
                                                        {{ request('kondisi_id') == $kondisi->id ? 'selected' : '' }}>
                                                        {{ $kondisi->nama_kondisi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <a href="{{ route('stok.index') }}" class="btn btn-default mr-1">
                                            <i class="fas fa-sync-alt"></i> Reset
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Tampilkan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-boxes mr-1"></i>
                                Daftar Stok Tersedia
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('stok.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Stok
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="stokTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Produk</th>
                                        <th>Lokasi Penyimpanan</th>
                                        <th style="width: 15%">Jumlah Stok</th>
                                        <th style="width: 15%">Kondisi</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stoks as $index => $stok)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $stok->produk->name ?? '-' }}</strong><br>
                                                <small class="text-muted">SKU: {{ $stok->produk->sku ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <i class="fas fa-warehouse text-secondary mr-1"></i>
                                                {{ $stok->gudang->nama_gudang ?? '-' }}<br>
                                                <i class="fas fa-map-marker-alt text-secondary mr-1"></i>
                                                {{ $stok->area->kode_area ?? '-' }} /
                                                <i class="fas fa-dolly text-secondary mr-1"></i>
                                                {{ $stok->rak->kode_rak ?? '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $minStock = $stok->produk->min_stock ?? 0;
                                                    $qty = $stok->quantity;
                                                    $badgeClass = 'badge-success';
                                                    if ($minStock > 0 && $qty <= $minStock) {
                                                        $badgeClass = 'badge-danger';
                                                    } elseif ($minStock > 0 && $qty <= $minStock * 1.5) {
                                                        $badgeClass = 'badge-warning';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge {{ $badgeClass }} badge-large">{{ number_format($qty, 0, ',', '.') }}
                                                    {{ $stok->produk->uom->name ?? 'Unit' }}</span>
                                                @if ($badgeClass == 'badge-danger')
                                                    <br><small class="text-danger"><i
                                                            class="fas fa-exclamation-circle"></i> Low Stock!</small>
                                                @endif
                                            </td>
                                            <td>
                                                <select
                                                    class="form-control form-control-sm ubah-kondisi-select border-0 bg-light"
                                                    data-stok-id="{{ $stok->id }}" style="cursor: pointer;">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($kondisis as $k)
                                                        <option value="{{ $k->id }}"
                                                            {{ $stok->kondisi_id == $k->id ? 'selected' : '' }}>
                                                            {{ $k->nama_kondisi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('stok.show', $stok->id) }}"
                                                        class="btn btn-info btn-sm" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('stok.edit', $stok->id) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm delete-stok-btn"
                                                        data-id="{{ $stok->id }}" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            // Initialize DataTable
            $("#stokTable").DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "search": "Cari:",
                },
                "responsive": true,
                "autoWidth": false,
            });

            // Flash Messages via SweetAlert2
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                });
            @endif

            // Delete Confirmation
            $(document).on('click', '.delete-stok-btn', function() {
                let stokId = $(this).data('id');
                let deleteUrl = "{{ route('stok.index') }}/" + stokId;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data stok ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.createElement('form');
                        form.action = deleteUrl;
                        form.method = 'POST';
                        form.innerHTML = `
                            @csrf
                            @method('DELETE')
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            // AJAX Update Kondisi
            $('.ubah-kondisi-select').change(function() {
                let stokId = $(this).data('stok-id');
                let kondisiId = $(this).val();

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                let url = "{{ route('stok.update-kondisi', ':id') }}";
                url = url.replace(':id', stokId);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kondisi_id: kondisiId
                    },
                    success: function(res) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Kondisi stok berhasil diperbarui'
                        });
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal mengubah kondisi'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
