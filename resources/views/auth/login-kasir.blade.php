<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Kasir | Garuda Fiber</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Source Sans Pro', sans-serif; background: url('{{ asset('image/wh2.jpg') }}') no-repeat center center fixed; background-size: cover; padding: 24px 16px; }
        .login-box { width: 400px; max-width: 100%; }
        .login-logo-image { width: 170px; max-width: 100%; height: auto; }
    </style>
</head>
<body class="hold-transition login-page">
    <main class="login-box">
        <div class="card card-outline card-primary shadow-lg mb-0">
            <div class="card-header text-center py-4"><img src="{{ asset('image/garuda-fiber.png') }}" class="login-logo-image" alt="Logo Garuda Fiber"></div>
            <div class="card-body login-card-body">
                <h1 class="h4 text-center font-weight-bold">Login Kasir</h1>
                <p class="login-box-msg">Masuk dengan akun kasir Anda.</p>
                @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
                @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
                <form method="POST" action="{{ route('kasir.login.attempt') }}">
                    @csrf
                    <label for="login">Username atau Email</label>
                    <div class="input-group mb-3">
                        <input id="login" name="login" type="text" class="form-control" value="{{ old('login') }}" placeholder="Username atau email" autocomplete="username" required autofocus>
                        <div class="input-group-append"><span class="input-group-text"><i class="fas fa-user" aria-hidden="true"></i></span></div>
                    </div>
                    <label for="password">Password</label>
                    <div class="input-group mb-4">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Password" autocomplete="current-password" required>
                        <div class="input-group-append"><span class="input-group-text"><i class="fas fa-lock" aria-hidden="true"></i></span></div>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit"><i class="fas fa-sign-in-alt mr-2" aria-hidden="true"></i>Masuk ke Kasir</button>
                </form>
            </div>
            <div class="card-footer text-center text-muted small">Portal khusus kasir Garuda Fiber</div>
        </div>
    </main>
</body>
</html>
