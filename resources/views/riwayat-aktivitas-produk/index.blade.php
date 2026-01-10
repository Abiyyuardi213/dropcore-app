<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Garuda Fiber - Riwayat Aktivitas Produk</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
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
                            <h1 class="m-0">Riwayat Aktivitas Produk</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Riwayat Aktivitas</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="riwayatTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Waktu</th>
                                            <th>Produk</th>
                                            <th>Tipe Aktivitas</th>
                                            <th>Deskripsi</th>
                                            <th>User</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayats as $index => $r)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') }}
                                                </td>
                                                <td>
                                                    @if ($r->produk)
                                                        <strong>{{ $r->produk->name }}</strong>
                                                    @else
                                                        <span class="text-muted text-sm">Produk Terhapus</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $badgeClass = 'secondary';
                                                        $icon = 'info-circle';
                                                        if (stripos($r->tipe_aktivitas, 'create') !== false) {
                                                            $badgeClass = 'success';
                                                            $icon = 'plus-circle';
                                                        } elseif (stripos($r->tipe_aktivitas, 'update') !== false) {
                                                            $badgeClass = 'warning';
                                                            $icon = 'edit';
                                                        } elseif (stripos($r->tipe_aktivitas, 'delete') !== false) {
                                                            $badgeClass = 'danger';
                                                            $icon = 'trash';
                                                        } elseif (stripos($r->tipe_aktivitas, 'stok') !== false) {
                                                            $badgeClass = 'info';
                                                            $icon = 'box';
                                                        }
                                                    @endphp
                                                    <span class="badge badge-{{ $badgeClass }}">
                                                        <i class="fas fa-{{ $icon }} mr-1"></i>
                                                        {{ ucfirst($r->tipe_aktivitas) }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($r->deskripsi ?? '-', 50) }}</td>
                                                <td>
                                                    @if ($r->user)
                                                        {{ $r->user->name }}
                                                    @else
                                                        <span class="text-muted font-italic">System</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('riwayat-aktivitas-produk.show', $r->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
            $('#riwayatTable').DataTable({
                paging: true,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true
            });
            @if (session('success') || session('error'))
                $('#toastNotification').toast({
                    delay: 3000,
                    autohide: true
                }).toast('show');
            @endif
        });
    </script>
</body>

</html>
