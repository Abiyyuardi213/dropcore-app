<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Distributor</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
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
                            <h1 class="m-0">Manajemen Distributor</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-truck mr-1"></i>
                                Daftar Distributor
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('distributor.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Distributor
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="distributorTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 20%">Distributor</th>
                                        <th style="width: 10%">Status</th>
                                        <th style="width: 15%">PIC</th>
                                        <th style="width: 15%">Kontak</th>
                                        <th>Lokasi</th>
                                        <th style="width: 10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($distributors as $index => $dist)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $dist->nama_distributor }}</strong><br>
                                                <small class="text-muted">{{ $dist->kode_distributor }}</small>
                                                <br>
                                                @php
                                                    $tipeClass = match ($dist->tipe_distributor) {
                                                        'Principal' => 'badge-primary',
                                                        'Distributor' => 'badge-info',
                                                        'Reseller' => 'badge-secondary',
                                                        default => 'badge-light',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge {{ $tipeClass }}">{{ $dist->tipe_distributor ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($dist->status) {
                                                        'active' => 'badge-success',
                                                        'inactive' => 'badge-danger',
                                                        'blacklisted' => 'badge-dark',
                                                        default => 'badge-secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge {{ $statusClass }}">{{ ucfirst($dist->status) }}</span>
                                            </td>
                                            <td>
                                                @if ($dist->pic_nama)
                                                    <i class="fas fa-user text-muted mr-1"></i>
                                                    {{ $dist->pic_nama }}<br>
                                                    @if ($dist->pic_telepon)
                                                        <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i>
                                                            {{ $dist->pic_telepon }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dist->telepon)
                                                    <div><i class="fas fa-phone-alt text-secondary mr-1"></i>
                                                        {{ $dist->telepon }}</div>
                                                @endif
                                                @if ($dist->email)
                                                    <div><i class="fas fa-envelope text-secondary mr-1"></i>
                                                        {{ $dist->email }}</div>
                                                @endif
                                                @if ($dist->website)
                                                    <div><a href="{{ $dist->website }}" target="_blank"><i
                                                                class="fas fa-globe text-secondary mr-1"></i> Web</a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dist->kota)
                                                    <strong><i class="fas fa-map-marker-alt text-primary mr-1"></i>
                                                        {{ $dist->kota->kota }}</strong><br>
                                                @endif
                                                @if ($dist->alamat)
                                                    <small
                                                        class="text-muted">{{ Str::limit($dist->alamat, 50) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('distributor.show', $dist->id) }}"
                                                        class="btn btn-info btn-sm" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('distributor.edit', $dist->id) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm delete-distributor-btn"
                                                        data-id="{{ $dist->id }}" title="Hapus">
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

    @include('services.logoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $("#distributorTable").DataTable({
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
            $(document).on('click', '.delete-distributor-btn', function() {
                let id = $(this).data('id');
                let deleteUrl = "{{ url('distributor') }}/" + id;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data distributor ini akan dihapus permanen!",
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
        });
    </script>
</body>

</html>
