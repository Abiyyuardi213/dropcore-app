<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropcore - Kecamatan</title>
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
                            <h1 class="m-0">Manajemen Kecamatan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Kecamatan</h3>

                            <div class="card-tools">
                                <form action="{{ route('kecamatan.index') }}" method="GET" class="form-inline">
                                    {{-- Preserve filter params when searching --}}
                                    @if (request('provinsi_id'))
                                        <input type="hidden" name="provinsi_id" value="{{ request('provinsi_id') }}">
                                    @endif
                                    @if (request('kota_id'))
                                        <input type="hidden" name="kota_id" value="{{ request('kota_id') }}">
                                    @endif

                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="q" class="form-control float-right"
                                            placeholder="Cari Kecamatan..." value="{{ request('q') }}">
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
                            <form action="{{ route('kecamatan.index') }}" method="GET" id="filterForm">
                                <input type="hidden" name="q" value="{{ request('q') }}">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label for="provinsi_id" class="sr-only">Provinsi</label>
                                            <select name="provinsi_id" id="provinsi_id" class="form-control"
                                                onchange="document.getElementById('kota_id').value=''; this.form.submit()">
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
                                                onchange="this.form.submit()">
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
                                    <div class="col-md-2">
                                        @if (request('provinsi_id') || request('kota_id') || request('q'))
                                            <a href="{{ route('kecamatan.index') }}"
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
                                            <th>Nama Kecamatan</th>
                                            <th>Nama Kota</th>
                                            <th>Provinsi</th>
                                            <th>Wilayah/Negara</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kecamatans as $index => $kecamatan)
                                            <tr>
                                                <td>{{ $kecamatans->firstItem() + $index }}</td>
                                                <td>{{ $kecamatan->name }}</td>
                                                <td>{{ $kecamatan->kota->name ?? '-' }}</td>
                                                <td>{{ $kecamatan->kota->provinsi->name ?? '-' }}</td>
                                                <td>{{ $kecamatan->kota->provinsi->wilayah->name ?? '-' }}</td>
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
                                {{ $kecamatans->links('pagination::bootstrap-4') }}
                            </div>
                            <div class="float-left text-muted">
                                Menampilkan {{ $kecamatans->firstItem() ?? 0 }} sampai
                                {{ $kecamatans->lastItem() ?? 0 }} dari {{ $kecamatans->total() }} data
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
</body>

</html>
