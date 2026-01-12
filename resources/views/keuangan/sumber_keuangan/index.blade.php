<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Sumber Keuangan</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">

    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('include.navbarSistem')
        @include('include.sidebar')

        <div class="content-wrapper">

            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Sumber Keuangan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title mb-0">Daftar Sumber Keuangan</h3>

                                <a href="{{ route('sumber-keuangan.create') }}"
                                    class="btn btn-primary btn-sm float-right">
                                    <i class="fas fa-plus"></i> Tambah Sumber
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if ($data->isEmpty())
                                <div class="alert alert-info">
                                    Belum ada sumber keuangan. Tambahkan baru untuk memulai.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 50px" class="text-center">No</th>
                                                <th>Nama Akun</th>
                                                <th>Jenis</th>
                                                <th>Info Rekening</th>
                                                <th class="text-right">Saldo Saat Ini</th>
                                                <th style="width: 150px" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($data as $index => $item)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td class="font-weight-bold">
                                                        {{ $item->nama_sumber }}
                                                        @if (!$item->is_active)
                                                            <span class="badge badge-danger ml-1">Non-Aktif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->jenis == 'bank')
                                                            <span class="badge badge-primary">Bank</span>
                                                        @elseif($item->jenis == 'tunai')
                                                            <span class="badge badge-success">Tunai</span>
                                                        @else
                                                            <span class="badge badge-info">E-Wallet</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->nomor_rekening)
                                                            <div>{{ $item->nomor_rekening }}</div>
                                                            <small class="text-muted">{{ $item->atas_nama }}</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right font-weight-bold text-success">
                                                        Rp {{ number_format($item->saldo, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center">

                                                        <!-- Tombol Edit -->
                                                        <a href="{{ route('sumber-keuangan.edit', $item->id) }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <!-- Tombol Hapus -->
                                                        <form action="{{ route('sumber-keuangan.destroy', $item->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
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

    <script>
        $(document).ready(function() {
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
