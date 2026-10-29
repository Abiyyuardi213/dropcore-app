<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Aktivitas Sistem</title>
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
                            <h1 class="m-0">Riwayat Aktivitas Sistem</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Log Aktivitas</h3>
                        </div>
                        <div class="card-body">
                            @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
                            <form method="GET" action="{{ route('riwayat-log.index') }}" class="d-flex flex-wrap align-items-end mb-3" style="gap:.75rem;">
                                <div class="form-group mb-0"><label for="logFrom">Dari Tanggal</label><input type="date" id="logFrom" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}"></div>
                                <div class="form-group mb-0"><label for="logTo">Sampai Tanggal</label><input type="date" id="logTo" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}"></div>
                                <div class="form-group mb-0"><label for="logModule">Modul</label><select id="logModule" name="module" class="form-control form-control-sm" style="max-width:220px;"><option value="">Semua Modul</option>@foreach($modules as $module)<option value="{{ $module }}" @selected(($filters['module'] ?? '') === $module)>{{ ucwords(str_replace('_',' ',$module)) }}</option>@endforeach</select></div>
                                <div class="form-group mb-0"><label for="logAction">Jenis Aksi</label><select id="logAction" name="action" class="form-control form-control-sm" style="max-width:220px;"><option value="">Semua Aksi</option>@foreach($actions as $action)<option value="{{ $action }}" @selected(($filters['action'] ?? '') === $action)>{{ ucwords(str_replace('_',' ',$action)) }}</option>@endforeach</select></div>
                                <div class="form-group mb-0"><label for="logUser">Pengguna</label><select id="logUser" name="user_id" class="form-control form-control-sm" style="max-width:240px;"><option value="">Semua Pengguna</option><option value="__system" @selected(($filters['user_id'] ?? '') === '__system')>System / Guest / Pengguna Tidak Tersedia</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(($filters['user_id'] ?? '') === (string)$user->id)>{{ $user->name }}</option>@endforeach</select></div>
                                <div class="form-group mb-0"><label for="logSearch">Cari Deskripsi</label><input type="search" id="logSearch" name="q" maxlength="255" class="form-control form-control-sm" placeholder="Kata kunci aktivitas..." value="{{ $filters['q'] ?? '' }}"></div>
                                <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button><a href="{{ route('riwayat-log.index') }}" class="btn btn-default btn-sm">Reset</a>
                            </form>
                            <p class="text-muted text-sm">{{ number_format($logs->total(),0,',','.') }} log sesuai filter. Menampilkan {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }}. Rentang tanggal berdasarkan waktu log dicatat.</p>
                            <div class="table-responsive">
                                <table id="logTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Waktu</th>
                                            <th>Module</th>
                                            <th>Action</th>
                                            <th>Deskripsi</th>
                                            <th>User</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $index => $log)
                                            <tr>
                                                <td class="text-center">{{ $logs->firstItem() + $index }}</td>
                                                <td data-order="{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-secondary">{{ ucfirst($log->module) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $badgeClass = 'secondary';
                                                        $icon = 'info-circle';
                                                        if (
                                                            stripos($log->action, 'create') !== false ||
                                                            stripos($log->action, 'store') !== false
                                                        ) {
                                                            $badgeClass = 'success';
                                                            $icon = 'plus-circle';
                                                        } elseif (
                                                            stripos($log->action, 'update') !== false ||
                                                            stripos($log->action, 'edit') !== false
                                                        ) {
                                                            $badgeClass = 'warning';
                                                            $icon = 'edit';
                                                        } elseif (
                                                            stripos($log->action, 'delete') !== false ||
                                                            stripos($log->action, 'destroy') !== false
                                                        ) {
                                                            $badgeClass = 'danger';
                                                            $icon = 'trash';
                                                        } elseif (stripos($log->action, 'login') !== false) {
                                                            $badgeClass = 'primary';
                                                            $icon = 'sign-in-alt';
                                                        } elseif (stripos($log->action, 'logout') !== false) {
                                                            $badgeClass = 'secondary';
                                                            $icon = 'sign-out-alt';
                                                        }
                                                    @endphp
                                                    <span class="badge badge-{{ $badgeClass }}">
                                                        <i class="fas fa-{{ $icon }} mr-1"></i>
                                                        {{ ucfirst($log->action) }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($log->description, 50) }}</td>
                                                <td>
                                                    @if ($log->user)
                                                        {{ $log->user->name }}
                                                    @else
                                                        <span class="text-muted font-italic">System/Guest</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('riwayat-log.show', $log->id) }}"
                                                        class="btn btn-info btn-sm" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Links -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $logs->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#logTable').DataTable({
                paging: false,
                lengthChange: false,
                searching: false,
                ordering: true,
                info: false,
                autoWidth: false,
                responsive: true,
                order: [[1, 'desc']]
            });
        });
    </script>
</body>

</html>
