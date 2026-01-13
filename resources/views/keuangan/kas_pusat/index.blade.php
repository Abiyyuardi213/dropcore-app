<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Kas Pusat</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">

    <style>
        .content-header h1 {
            font-weight: 700;
            color: #343a40;
        }

        .info-box-number {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .card-info {
            font-size: 14px;
            line-height: 1.7;
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
                            <h1 class="m-0"><i class="fas fa-coins mr-2"></i>Kas Pusat Perusahaan</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="{{ route('sumber-keuangan.create', ['type' => 'bank']) }}"
                                class="btn btn-default mr-2">
                                <i class="fas fa-plus mr-1"></i> Kelola Akun Bank
                            </a>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#transferModal">
                                <i class="fas fa-exchange-alt mr-1"></i> Transfer Kas ke Saldo Bank
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    {{-- Error Alert --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card card-secondary card-outline shadow">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title text-white font-weight-bold">
                                <i class="fas fa-info-circle mr-1"></i> Status Keuangan Utama
                            </h3>
                        </div>

                        <div class="card-body">
                            <!-- Summary Cards -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box shadow-sm">
                                        <span class="info-box-icon bg-info elevation-1"><i
                                                class="fas fa-wallet"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Likuiditas</span>
                                            <span class="info-box-number">Rp
                                                {{ number_format($totalLiquidity, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box shadow-sm">
                                        <span class="info-box-icon bg-primary elevation-1"><i
                                                class="fas fa-university"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Saldo Bank</span>
                                            <span class="info-box-number">Rp
                                                {{ number_format($bankTotal, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box shadow-sm">
                                        <span class="info-box-icon bg-success elevation-1"><i
                                                class="fas fa-money-bill-wave"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Kas Tunai</span>
                                            <span class="info-box-number">Rp
                                                {{ number_format($cashTotal, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box shadow-sm">
                                        <span class="info-box-icon bg-purple elevation-1"><i
                                                class="fas fa-mobile-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">E-Wallet</span>
                                            <span class="info-box-number">Rp
                                                {{ number_format($ewalletTotal, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="text-secondary font-weight-bold mb-3"><i class="fas fa-history mr-2"></i>5
                                Transaksi Terakhir</h5>

                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>No. Transaksi</th>
                                            <th>Keterangan</th>
                                            <th>Akun</th>
                                            <th class="text-right">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentTransactions as $trx)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}
                                                </td>
                                                <td class="text-muted small">{{ $trx->no_transaksi }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-light border">{{ $trx->kategori->nama ?? '-' }}</span><br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($trx->keterangan, 30) }}</small>
                                                </td>
                                                <td>{{ $trx->sumber->nama_sumber ?? '-' }}</td>
                                                <td
                                                    class="text-right font-weight-bold {{ $trx->jenis_transaksi == 'pemasukkan' ? 'text-success' : 'text-danger' }}">
                                                    {{ $trx->jenis_transaksi == 'pemasukkan' ? '+' : '-' }} Rp
                                                    {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Belum ada transaksi
                                                    terbaru.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3 text-right">
                                <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary btn-sm">Lihat
                                    Semua Transaksi <i class="fas fa-arrow-right ml-1"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    {{-- Modal Transfer --}}
    <div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transferModalLabel">Transfer Kas ke Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kas-pusat.transfer') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Dari Akun Kas (Tunai) <span class="text-danger">*</span></label>
                            <select name="sumber_asal_id" class="form-control" required>
                                <option value="">-- Pilih Kas Tunai --</option>
                                @foreach ($listKas as $kas)
                                    <option value="{{ $kas->id }}">
                                        {{ $kas->nama_sumber }} (Saldo: Rp
                                        {{ number_format($kas->saldo, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ke Akun Bank <span class="text-danger">*</span></label>
                            <select name="sumber_tujuan_id" class="form-control" required>
                                <option value="">-- Pilih Bank Tujuan --</option>
                                @foreach ($listBank as $bank)
                                    <option value="{{ $bank->id }}">
                                        {{ $bank->nama_sumber }} ({{ $bank->nomor_rekening }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Transfer (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control" min="1"
                                placeholder="0" required>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Setor tunai harian"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>
                            Transfer</button>
                    </div>
                </form>
            </div>
        </div>
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
