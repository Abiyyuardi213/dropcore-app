<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Kerja Stock Opname | DropCore</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper">
        @include('include.navbarSistem')
        @include('include.sidebar')

        <div class="content-wrapper">
            <!-- Header Info -->
            <div class="content-header shadow-sm bg-white mb-3">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="m-0 text-dark">
                                Lembar Kerja Stock Opname
                                <span
                                    class="badge {{ $opname->status == 'processed' ? 'badge-success' : 'badge-warning' }} ml-2">{{ ucfirst($opname->status) }}</span>
                            </h4>
                            <small class="text-muted">
                                Gudang: <b>{{ $opname->gudang->nama_gudang }}</b> |
                                Tanggal: {{ \Carbon\Carbon::parse($opname->tanggal)->format('d M Y') }} |
                                Petugas: {{ $opname->user->name }}
                            </small>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="{{ route('stock-opname.index') }}" class="btn btn-default btn-sm"><i
                                    class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <form action="{{ route('stock-opname.update', $opname->id) }}" method="POST" id="opnameForm">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 70vh;">
                                    <table class="table table-head-fixed table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">No</th>
                                                <th style="width: 30%">Produk</th>
                                                <th style="width: 20%">Lokasi (Area/Rak)</th>
                                                <th style="width: 10%">Kondisi</th>
                                                <th style="width: 10%">Qty Sistem</th>
                                                <th style="width: 10%" class="bg-light">Qty Fisik (Input)</th>
                                                <th style="width: 15%">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($opname->details as $index => $detail)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <b>{{ $detail->produk->name }}</b><br>
                                                        <small class="text-muted">{{ $detail->produk->sku }}</small>
                                                    </td>
                                                    <td>
                                                        {{ $detail->area->nama_area }} / {{ $detail->rak->nama_rak }}
                                                    </td>
                                                    <td>{{ $detail->kondisi->nama_kondisi }}</td>
                                                    <td class="text-center font-weight-bold">{{ $detail->qty_sistem }}
                                                    </td>

                                                    @if ($opname->status === 'draft')
                                                        <td class="bg-light">
                                                            <input type="number"
                                                                name="details[{{ $detail->id }}][qty_fisik]"
                                                                class="form-control form-control-sm border-info font-weight-bold text-center qty-input"
                                                                value="{{ $detail->qty_fisik ?? 0 }}" min="0">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="details[{{ $detail->id }}][catatan]"
                                                                class="form-control form-control-sm"
                                                                value="{{ $detail->catatan }}" placeholder="Notes...">
                                                        </td>
                                                    @else
                                                        <!-- Read Only View -->
                                                        <td
                                                            class="text-center font-weight-bold 
                                                        {{ $detail->qty_fisik != $detail->qty_sistem ? 'text-danger bg-warning' : 'text-success bg-light' }}">
                                                            {{ $detail->qty_fisik }}
                                                            @if ($detail->qty_fisik != $detail->qty_sistem)
                                                                <br><small>Selisih: {{ $detail->selisih }}</small>
                                                            @endif
                                                        </td>
                                                        <td>{{ $detail->catatan }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if ($opname->status === 'draft')
                                <div class="card-footer fixed-bottom bg-white border-top shadow-lg"
                                    style="margin-left: 250px;"> <!-- Adjust margin for sidebar -->
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Pastikan semua data sudah terisi dengan benar
                                                sebelum Finalisasi.</small>
                                        </div>
                                        <div class="col-6 text-right">
                                            <button type="submit" name="action" value="save_draft"
                                                class="btn btn-default mr-2">
                                                <i class="fas fa-save"></i> Simpan Draft
                                            </button>
                                            <button type="button" id="btnFinalize" class="btn btn-primary">
                                                <i class="fas fa-check-double"></i> Finalisasi & Update Stok
                                            </button>
                                            <input type="hidden" name="action" id="formAction" value="save_draft">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </section>
        </div>

        @include('include.footerSistem')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 2000
                });
            @endif

            $('#btnFinalize').click(function() {
                Swal.fire({
                    title: 'Konfirmasi Finalisasi?',
                    text: "Stok Aktual di sistem akan DIUBAH mengikuti Qty Fisik yang Anda input. Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Update Stok!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#formAction').val('finalize');
                        $('#opnameForm').submit();
                    }
                })
            });
        });
    </script>
</body>

</html>
