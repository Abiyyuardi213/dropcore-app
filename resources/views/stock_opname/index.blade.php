<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Opname | DropCore</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                            <h1 class="m-0">Stock Opname</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Stock Opname</h3>
                            <div class="card-tools">
                                <a href="{{ route('stock-opname.create') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-plus"></i> Mulai Opname Baru
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
                            <form method="GET" action="{{ route('stock-opname.index') }}" class="d-flex flex-wrap align-items-end mb-3" style="gap:.75rem;">
                                <div class="form-group mb-0"><label for="opnameFrom">Dari Tanggal</label><input type="date" id="opnameFrom" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}"></div>
                                <div class="form-group mb-0"><label for="opnameTo">Sampai Tanggal</label><input type="date" id="opnameTo" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}"></div>
                                <div class="form-group mb-0"><label for="opnameWarehouse">Gudang</label><select id="opnameWarehouse" name="gudang_id" class="form-control form-control-sm" style="max-width:240px;"><option value="">Semua Gudang</option>@foreach($gudangs as $gudang)<option value="{{ $gudang->id }}" @selected(($filters['gudang_id'] ?? '') === (string)$gudang->id)>{{ $gudang->nama_gudang }}</option>@endforeach</select></div>
                                <div class="form-group mb-0"><label for="opnameOfficer">Petugas</label><select id="opnameOfficer" name="user_id" class="form-control form-control-sm" style="max-width:240px;"><option value="">Semua Petugas</option>@foreach($petugas as $user)<option value="{{ $user->id }}" @selected(($filters['user_id'] ?? '') === (string)$user->id)>{{ $user->name }}</option>@endforeach</select></div>
                                <div class="form-group mb-0"><label for="opnameStatus">Status</label><select id="opnameStatus" name="status" class="form-control form-control-sm"><option value="">Semua Status</option><option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft / Sedang Berjalan</option><option value="processed" @selected(($filters['status'] ?? '') === 'processed')>Selesai</option></select></div>
                                <div class="form-group mb-0"><label for="opnameResult">Hasil Pemeriksaan</label><select id="opnameResult" name="result" class="form-control form-control-sm"><option value="">Semua Hasil</option><option value="difference" @selected(($filters['result'] ?? '') === 'difference')>Ada Selisih</option><option value="matched" @selected(($filters['result'] ?? '') === 'matched')>Sesuai (Semua Item)</option><option value="incomplete" @selected(($filters['result'] ?? '') === 'incomplete')>Belum Selesai Dihitung</option></select></div>
                                <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button><a href="{{ route('stock-opname.index') }}" class="btn btn-default btn-sm">Reset</a>
                            </form>
                            <p class="text-muted text-sm">{{ $data->count() }} sesi opname sesuai filter. Tanggal mengikuti tanggal opname. “Ada selisih” mencakup sesi dengan item berbeda dari stok sistem; “sesuai” hanya untuk sesi yang seluruh itemnya sudah dihitung dan cocok.</p>
                            <div class="table-responsive">
                            <table id="opnameTable" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Format</th>
                                        <th>Gudang</th>
                                        <th>Petugas</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                            <td>{{ $item->gudang->nama_gudang ?? '-' }}</td>
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                            <td>
                                                @if ($item->status === 'processed')
                                                    <span class="badge badge-success">Selesai (Processed)</span>
                                                @else
                                                    <span class="badge badge-warning">Draft (Sedang Berjalan)</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('stock-opname.show', $item->id) }}"
                                                    class="btn btn-primary btn-xs">
                                                    @if ($item->status === 'draft')
                                                        <i class="fas fa-edit"></i> Input Hasil
                                                    @else
                                                        <i class="fas fa-eye"></i> Lihat Detail
                                                    @endif
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

    @include('services.LogoutModal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#opnameTable").DataTable({
                "ordering": false
            });

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
        });
    </script>
</body>

</html>
