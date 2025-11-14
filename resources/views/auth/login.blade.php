<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | PT Garuda Fiber</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        .login-wrapper {
            max-width: 900px;
            width: 100%;
        }
        .left-side {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .left-side img {
            max-width: 85%;
        }

        @media (max-width: 768px) {
            .left-side {
                display: none;
            }
        }

        .left-side {
            flex: 1;
            background-image: url('{{ asset('image/wh1.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .footer-transparan {
            position: fixed;
            right: 10px;
            bottom: 10px;
            background: rgba(0, 0, 0, 0.35);
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            backdrop-filter: blur(4px);
        }
    </style>
</head>

<body class="hold-transition login-page">

    <div class="login-wrapper shadow-lg bg-white rounded d-flex">

        <div class="left-side"></div>

        <div class="col-md-6 p-4">
            <div class="text-center mb-3">
                <img src="{{ asset('image/garuda-fiber.png') }}" alt="Logo PT Garuda Fiber" style="height: 60px;">
            </div>

            <p class="login-box-msg text-center">Silakan login untuk masuk</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>{{ $errors->first() }}</strong>
                </div>
            @endif

            <form action="{{ route('login.attempt') }}" method="POST">
                @csrf

                <div class="input-group mb-3">
                    <input type="text" name="login" class="form-control" placeholder="Username atau Email" value="{{ old('login') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="footer-transparan">
        © 2025 PT Garuda Fiber
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
