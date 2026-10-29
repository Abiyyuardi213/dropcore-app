<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Supplier</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .supplier-actions { display: flex; justify-content: center; flex-wrap: wrap; gap: .4rem; }
        .supplier-actions .btn { border-radius: .2rem; }
        .supplier-filters { display: flex; flex-wrap: wrap; align-items: end; gap: .6rem; }
        .supplier-filters .form-group { margin-bottom: 0; }
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
                            <h1 class="m-0">Manajemen Supplier</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Supplier</h3>
                            <a href="{{ route('supplier.create') }}" class="btn btn-primary btn-sm ml-auto">
                                <i class="fas fa-plus"></i> Tambah Supplier
                            </a>
                        </div>
                        <div class="card-body">
                            @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
                            <form id="supplierFilters" method="GET" action="{{ route('supplier.index') }}" class="supplier-filters mb-3">
                                <div class="form-group"><label for="filterSupplierStatus">Status</label><select id="filterSupplierStatus" class="form-control form-control-sm" name="status">
                                    <option value="">Semua Status</option><option value="1" @selected((string)($filters['status'] ?? '') === '1')>Aktif</option><option value="0" @selected((string)($filters['status'] ?? '') === '0')>Nonaktif</option>
                                </select></div>
                                <div class="form-group"><label for="filterSupplierOrigin">Asal Supplier</label><select id="filterSupplierOrigin" class="form-control form-control-sm" name="asal_supplier">
                                    <option value="">Semua Asal</option><option value="dalam_negeri" @selected(($filters['asal_supplier'] ?? '') === 'dalam_negeri')>Dalam Negeri</option><option value="luar_negeri" @selected(($filters['asal_supplier'] ?? '') === 'luar_negeri')>Luar Negeri</option>
                                </select></div>
                                <div class="form-group"><label for="filterSupplierType">Tipe Supplier</label><select id="filterSupplierType" class="form-control form-control-sm" name="tipe_supplier" style="max-width:220px">
                                    <option value="">Semua Tipe</option>@foreach($supplierTypes as $type)<option value="{{ $type }}" @selected(($filters['tipe_supplier'] ?? '') === $type)>{{ $type }}</option>@endforeach
                                </select></div>
                                <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button><a class="btn btn-default btn-sm" href="{{ route('supplier.index') }}">Reset</a>
                            </form>
                            <div class="table-responsive">
                                <table id="supplierTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Supplier</th>
                                            <th>Contact Person</th>
                                            <th>Kota</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($suppliers as $index => $supplier)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $supplier->kode_supplier }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($supplier->logo_url)
                                                            <img src="{{ $supplier->logo_url }}" alt="Logo"
                                                                class="img-circle mr-2"
                                                                style="width: 30px; height: 30px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-secondary rounded-circle mr-2 d-flex justify-content-center align-items-center"
                                                                style="width: 30px; height: 30px;">
                                                                <i class="fas fa-truck text-white"
                                                                    style="font-size: 12px;"></i>
                                                            </div>
                                                        @endif
                                                        {{ $supplier->nama_supplier }}
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $supplier->penanggung_jawab ?? '-' }}
                                                    @if ($supplier->no_telepon)
                                                        <br><small class="text-muted"><i class="fas fa-phone-alt"></i>
                                                            {{ $supplier->no_telepon }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (($supplier->asal_supplier ?? 'dalam_negeri') == 'luar_negeri')
                                                        <span class="badge badge-info"><i class="fas fa-globe mr-1"></i> Luar Negeri</span>
                                                    @else
                                                        {{ $supplier->kota->name ?? '-' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($supplier->status)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-secondary">Non-Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="supplier-actions">
                                                        <a href="{{ route('supplier.show', $supplier->id) }}"
                                                            class="btn btn-info btn-sm" title="Detail" aria-label="Detail {{ $supplier->nama_supplier }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('supplier.edit', $supplier->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit" aria-label="Edit {{ $supplier->nama_supplier }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-danger btn-sm delete-supplier-btn"
                                                            type="button" data-supplier-id="{{ $supplier->id }}" title="Hapus" aria-label="Hapus {{ $supplier->nama_supplier }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="tablePagination"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>



    @include('services.ToastModal')
    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/ToastScript.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#supplierTable").DataTable({
                "dom": "<'row align-items-end mb-3'<'col-lg-9 supplier-filter-slot'><'col-lg-3'f>>rt<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
            $('#supplierFilters').appendTo('.supplier-filter-slot').removeClass('mb-3');

            // Delete Confirmation with SweetAlert
            $(document).on('click', '.delete-supplier-btn', function() {
                let supplierId = $(this).data('supplier-id');
                let deleteUrl = "{{ route('supplier.index') }}/" + supplierId;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data supplier ini akan dihapus permanen!",
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

            // SweetAlert Flash Messages
            @if (session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: {!! json_encode(session('success')) !!}
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                });
            @endif
        });
    </script>
</body>

</html>
