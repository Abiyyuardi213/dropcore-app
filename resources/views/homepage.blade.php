<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Playfair Display', serif;
            background: #ffffff;
            margin: 0;
            overflow-x: hidden;
        }

        .logo img {
            max-width: 250px;
        }

        .triangle-menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }

        .triangle {
            width: 0;
            height: 0;
            border-left: 80px solid transparent;
            border-right: 80px solid transparent;
            border-bottom: 140px solid rgba(0, 128, 170, 0.6);
            position: relative;
            transition: all 0.3s ease;
        }

        .triangle.inverted {
            transform: rotate(180deg);
        }

        .triangle:hover {
            transform: scale(1.05);
        }

        .triangle-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #1a1a3d;
            font-size: 18px;
            text-align: center;
            width: 110px;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            z-index: -1;
            background: url('/images/network-pattern.svg') center/cover no-repeat;
            opacity: 0.2;
        }
    </style>
</head>
<body>

    <div class="background"></div>

    <div class="container text-center mt-5">
        <div class="logo">
            <img src="{{ asset('images/logo-garuda.png') }}" alt="Garuda Fiber">
        </div>

        <div class="triangle-menu mt-5">
            <a href="#tentang">
                <div class="triangle">
                    <div class="triangle-text">Tentang<br>Kami</div>
                </div>
            </a>
            <a href="#produk">
                <div class="triangle">
                    <div class="triangle-text">Produk</div>
                </div>
            </a>
            <a href="#berita">
                <div class="triangle">
                    <div class="triangle-text">Berita</div>
                </div>
            </a>
            <a href="#temukan">
                <div class="triangle">
                    <div class="triangle-text">Temukan<br>Kami</div>
                </div>
            </a>
            <a href="#gabung">
                <div class="triangle inverted">
                    <div class="triangle-text">Gabung<br>Distributor</div>
                </div>
            </a>
        </div>
    </div>

    <!-- JS Resources -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
