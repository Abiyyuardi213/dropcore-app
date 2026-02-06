<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Kelurahan</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <h1 class="m-0">Manajemen Kelurahan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Kelurahan</h3>

                            <div class="card-tools d-flex align-items-center">
                                <form action="{{ route('kelurahan.sync') }}" method="POST" class="mr-2">
                                    @csrf
                                    <button type="button" class="btn btn-success btn-sm sync-btn-kelurahan">
                                        <i class="fas fa-sync"></i> Sync API
                                    </button>
                                </form>
                                <form action="{{ route('kelurahan.index') }}" method="GET" class="form-inline">
                                    {{-- Preserve filter params when searching --}}
                                    @foreach (['provinsi_id', 'kota_id', 'kecamatan_id'] as $param)
                                        @if (request($param))
                                            <input type="hidden" name="{{ $param }}"
                                                value="{{ request($param) }}">
                                        @endif
                                    @endforeach

                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="q" class="form-control float-right"
                                            placeholder="Cari Kelurahan..." value="{{ request('q') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Filters --}}
                            <form action="{{ route('kelurahan.index') }}" method="GET" id="filterForm">
                                <input type="hidden" name="q" value="{{ request('q') }}">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="provinsi_id" class="sr-only">Provinsi</label>
                                            <select name="provinsi_id" id="provinsi_id" class="form-control"
                                                onchange="document.getElementById('kota_id').value=''; document.getElementById('kecamatan_id').value=''; this.form.submit()">
                                                <option value="">-- Filter Provinsi --</option>
                                                @foreach ($provinsis as $p)
                                                    <option value="{{ $p->id }}"
                                                        {{ request('provinsi_id') == $p->id ? 'selected' : '' }}>
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="kota_id" class="sr-only">Kota</label>
                                            <select name="kota_id" id="kota_id" class="form-control"
                                                onchange="document.getElementById('kecamatan_id').value=''; this.form.submit()">
                                                <option value="">-- Filter Kota --</option>
                                                @foreach ($kotas as $k)
                                                    <option value="{{ $k->id }}"
                                                        {{ request('kota_id') == $k->id ? 'selected' : '' }}>
                                                        {{ $k->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="kecamatan_id" class="sr-only">Kecamatan</label>
                                            <select name="kecamatan_id" id="kecamatan_id" class="form-control"
                                                onchange="this.form.submit()">
                                                <option value="">-- Filter Kecamatan --</option>
                                                @foreach ($kecamatans as $kec)
                                                    <option value="{{ $kec->id }}"
                                                        {{ request('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                                        {{ $kec->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        @if (request('provinsi_id') || request('kota_id') || request('kecamatan_id') || request('q'))
                                            <a href="{{ route('kelurahan.index') }}"
                                                class="btn btn-secondary btn-block">
                                                <i class="fas fa-undo"></i> Reset
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Kelurahan</th>
                                            <th>Kecamatan</th>
                                            <th>Kota</th>
                                            <th>Provinsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kelurahans as $index => $kelurahan)
                                            <tr>
                                                <td>{{ $kelurahans->firstItem() + $index }}</td>
                                                <td>{{ $kelurahan->name }}</td>
                                                <td>{{ $kelurahan->kecamatan->name ?? '-' }}</td>
                                                <td>{{ $kelurahan->kecamatan->kota->name ?? '-' }}</td>
                                                <td>{{ $kelurahan->kecamatan->kota->provinsi->name ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <i class="fas fa-search mb-2" style="font-size: 2rem;"></i>
                                                    <p class="mb-0">Data tidak ditemukan</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <div class="float-right">
                                {{ $kelurahans->links('pagination::bootstrap-4') }}
                            </div>
                            <div class="float-left text-muted">
                                Menampilkan {{ $kelurahans->firstItem() ?? 0 }} sampai
                                {{ $kelurahans->lastItem() ?? 0 }} dari {{ $kelurahans->total() }} data
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
    <script src="{{ asset('js/ToastScript.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.sync-btn-kelurahan').click(function(e) {
                e.preventDefault();
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'CRITICAL WARNING',
                    text: "Proses ini SANGAT SANGAT LAMA (bisa >10 menit) karena puluhan ribu data. Browser mungkin akan hang sementara. Yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Saya Paham!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            html: 'Mohon tunggu, sinkronisasi data kelurahan sedang berjalan.<br><strong>JANGAN REFRESH ATAU TUTUP HALAMAN INI.</strong>',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
