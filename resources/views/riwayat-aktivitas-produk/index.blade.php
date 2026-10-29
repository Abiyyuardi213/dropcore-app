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
                            @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
                            <form method="GET" action="{{ route('riwayat-aktivitas-produk.index') }}" class="d-flex flex-wrap align-items-end mb-3" style="gap:.75rem;">
                                <div class="form-group mb-0"><label for="activityFrom">Dari Tanggal</label><input type="date" id="activityFrom" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}"></div>
                                <div class="form-group mb-0"><label for="activityTo">Sampai Tanggal</label><input type="date" id="activityTo" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}"></div>
                                <div class="form-group mb-0"><label for="activityProduct">Produk / SKU</label><select id="activityProduct" name="produk_id" class="form-control form-control-sm" style="max-width:280px;"><option value="">Semua Produk</option><option value="__deleted" @selected(($filters['produk_id'] ?? '') === '__deleted')>Produk Terhapus / Tidak Tersedia</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(($filters['produk_id'] ?? '') === (string)$product->id)>{{ $product->sku ? $product->sku.' — ' : '' }}{{ $product->name }}</option>@endforeach</select></div>
                                <div class="form-group mb-0"><label for="activityType">Tipe Aktivitas</label><select id="activityType" name="tipe_aktivitas" class="form-control form-control-sm"><option value="">Semua Aktivitas</option>@foreach($activityTypes as $type)<option value="{{ $type }}" @selected(($filters['tipe_aktivitas'] ?? '') === $type)>{{ ucwords(str_replace('_',' ',$type)) }}</option>@endforeach</select></div>
                                <div class="form-group mb-0"><label for="activityUser">Pengguna</label><select id="activityUser" name="user_id" class="form-control form-control-sm" style="max-width:240px;"><option value="">Semua Pengguna</option><option value="__system" @selected(($filters['user_id'] ?? '') === '__system')>System / Pengguna Tidak Tersedia</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(($filters['user_id'] ?? '') === (string)$user->id)>{{ $user->name }}</option>@endforeach</select></div>
                                <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button><a href="{{ route('riwayat-aktivitas-produk.index') }}" class="btn btn-default btn-sm">Reset</a>
                            </form>
                            <p class="text-muted text-sm">{{ $riwayats->count() }} aktivitas sesuai filter. Rentang tanggal berdasarkan waktu aktivitas dicatat.</p>
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
                                                <td data-order="{{ \Carbon\Carbon::parse($r->created_at)->format('Y-m-d H:i:s') }}">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') }}
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
                responsive: true,
                order: [[1, 'desc']]
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
