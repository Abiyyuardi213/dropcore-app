<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Garuda Fiber - Mutasi Stok</title>
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
                            <h1 class="m-0">Manajemen Mutasi Stok</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Mutasi Stok</h3>
                            <a href="{{ route('mutasi-stok.create') }}" class="btn btn-primary btn-sm ml-auto">
                                <i class="fas fa-plus"></i> Tambah Mutasi
                            </a>
                        </div>
                        <div class="card-body">
                            @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
                            <form method="GET" action="{{ route('mutasi-stok.index') }}" class="d-flex flex-wrap align-items-end mb-3" style="gap:.75rem;">
                                <div class="form-group mb-0"><label for="mutationFrom">Dari Tanggal</label><input type="date" id="mutationFrom" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}"></div>
                                <div class="form-group mb-0"><label for="mutationTo">Sampai Tanggal</label><input type="date" id="mutationTo" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}"></div>
                                <div class="form-group mb-0"><label for="mutationType">Jenis Mutasi</label><select id="mutationType" name="jenis_mutasi" class="form-control form-control-sm"><option value="">Semua Jenis</option>@foreach(['masuk'=>'Masuk','keluar'=>'Keluar','pindah'=>'Pindah'] as $value=>$label)<option value="{{ $value }}" @selected(($filters['jenis_mutasi'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></div>
                                <div class="form-group mb-0"><label for="mutationProduct">Produk / SKU</label><select id="mutationProduct" name="produk_id" class="form-control form-control-sm" style="max-width:280px;"><option value="">Semua Produk</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(($filters['produk_id'] ?? '') === (string)$product->id)>{{ $product->sku ? $product->sku.' — ' : '' }}{{ $product->name }}</option>@endforeach</select></div>
                                @foreach(['gudang_asal_id'=>'Gudang Asal','gudang_tujuan_id'=>'Gudang Tujuan'] as $field=>$label)
                                    <div class="form-group mb-0"><label for="mutation_{{ $field }}">{{ $label }}</label><select id="mutation_{{ $field }}" name="{{ $field }}" class="form-control form-control-sm" style="max-width:230px;"><option value="">Semua Gudang</option>@foreach($gudangs as $gudang)<option value="{{ $gudang->id }}" @selected(($filters[$field] ?? '') === (string)$gudang->id)>{{ $gudang->nama_gudang }}</option>@endforeach</select></div>
                                @endforeach
                                <div class="form-group mb-0"><label for="mutationCondition">Kondisi Barang</label><select id="mutationCondition" name="kondisi_id" class="form-control form-control-sm"><option value="">Semua Kondisi</option>@foreach($kondisis as $kondisi)<option value="{{ $kondisi->id }}" @selected(($filters['kondisi_id'] ?? '') === (string)$kondisi->id)>{{ $kondisi->nama_kondisi }}</option>@endforeach</select></div>
                                <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button><a href="{{ route('mutasi-stok.index') }}" class="btn btn-default btn-sm">Reset</a>
                            </form>
                            <p class="text-muted text-sm">{{ $mutasi->count() }} mutasi sesuai filter. Rentang tanggal berdasarkan tanggal mutasi; gudang asal dan tujuan dapat difilter bersamaan untuk menelusuri perpindahan.</p>
                            <div class="table-responsive">
                                <table id="mutasiStokTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th>Tanggal</th>
                                            <th class="text-center">Jenis</th>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Lokasi Asal</th>
                                            <th>Lokasi Tujuan</th>
                                            <th>User</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mutasi as $index => $m)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td data-order="{{ \Carbon\Carbon::parse($m->tanggal_mutasi)->format('Y-m-d H:i:s') }}">{{ \Carbon\Carbon::parse($m->tanggal_mutasi)->format('d M Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($m->jenis_mutasi == 'masuk')
                                                        <span class="badge badge-success">Masuk</span>
                                                    @elseif($m->jenis_mutasi == 'keluar')
                                                        <span class="badge badge-danger">Keluar</span>
                                                    @else
                                                        <span class="badge badge-info">Pindah</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $m->produk->name ?? '-' }}</strong><br>
                                                    <small class="text-muted">{{ $m->referensi ?? '' }}</small>
                                                </td>
                                                <td>{{ $m->quantity }}</td>
                                                <td>
                                                    @if ($m->jenis_mutasi == 'masuk')
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        {{ $m->gudangAsal->nama_gudang ?? '-' }} <br>
                                                        <small
                                                            class="text-muted">{{ $m->rakAsal->kode_rak ?? '' }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($m->jenis_mutasi == 'keluar')
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        {{ $m->gudangTujuan->nama_gudang ?? '-' }} <br>
                                                        <small
                                                            class="text-muted">{{ $m->rakTujuan->kode_rak ?? '' }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $m->user->name ?? 'System' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('mutasi-stok.show', $m->id) }}"
                                                        class="btn btn-sm btn-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('mutasi-stok.print', $m->id) }}" target="_blank"
                                                        class="btn btn-sm btn-default" title="Print Invoice"><i
                                                            class="fas fa-print"></i></a>
                                                    {{-- Edit/Delete disabled for integrity/safety unless strictly allowed --}}
                                                    <form action="{{ route('mutasi-stok.destroy', $m->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Hapus"><i class="fas fa-trash"></i></button>
                                                    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#mutasiStokTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [[1, 'desc']]
            });

            // SweetAlert for Session Messages
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
                    title: 'Gagal',
                    text: '{{ session('error') }}'
                });
            @endif

            // Delete Confirmation
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Apakah Anda yakin ingin menghapus data mutasi ini? Stok tidak akan dikembalikan otomatis (Manual Adjustment mungkin diperlukan).",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
