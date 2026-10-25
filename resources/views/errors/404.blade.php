<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body.error-screen {
            min-height: 100vh;
            margin: 0;
            padding: 24px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Source Sans Pro', sans-serif;
            background: url('{{ asset('image/wh2.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }
        .error-card {
            width: 100%;
            max-width: 760px;
            margin: 0;
            background: rgba(255, 255, 255, .94);
        }
        .error-logo { width: 160px; max-width: 100%; height: auto; }
        .error-card .error-page { width: 100%; margin: 0; }
        .error-card .headline { line-height: 1.1; }
        .error-card .error-content h1 { font-size: 26px; font-weight: 600; }
        .error-actions { display: flex; flex-wrap: wrap; gap: .75rem; }
        @media (max-width: 767.98px) {
            .error-card .error-page > .headline { float: none; text-align: center; }
            .error-card .error-page > .error-content { margin-left: 0; text-align: center; }
            .error-actions { justify-content: center; }
        }
    </style>
</head>
<body class="hold-transition error-screen">
    <main class="card card-outline card-primary shadow-lg error-card" aria-labelledby="error-title">
        <div class="card-header text-center py-4 border-bottom-0">
            <img src="{{ asset('image/garuda-fiber.png') }}" class="error-logo" alt="Logo Garuda Fiber">
        </div>
        <div class="card-body px-4 pb-4">
            <div class="error-page">
                <div class="headline text-warning font-weight-bold" aria-hidden="true">404</div>
                <div class="error-content">
                    <h1 id="error-title"><i class="fas fa-exclamation-triangle text-warning mr-1" aria-hidden="true"></i> Halaman tidak ditemukan</h1>
                    <p class="text-muted mb-4">Halaman yang Anda cari tidak tersedia. Periksa kembali alamatnya, atau kembali ke beranda untuk melanjutkan.</p>
                    <div class="error-actions">
                        <a href="{{ route('homepage') }}" class="btn btn-primary"><i class="fas fa-home mr-2" aria-hidden="true"></i>Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center text-muted small">Garuda Fiber &middot; Sistem Manajemen Gudang</div>
    </main>
</body>
</html>
