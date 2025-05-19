<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: url('{{ asset('image/wh1.jpg') }}') no-repeat center center fixed; /* Menambahkan gambar latar */
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.8); /* Background transparan agar teks lebih terbaca */
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container img {
            width: 200px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            color: #666;
        }

        a.button {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background-color: #0056b3;
        }

        .emoji {
            font-size: 60px;
        }
        .emoji img {
            width: 150px; /* Ukuran lebar gambar */
            height: auto; /* Menjaga rasio aspek gambar */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">
            <img src="{{ asset('image/dropcore.png') }}" alt="Not Found">
        </div>
        <h1>404 - Halaman Tidak Ditemukan</h1>
        <p>Ups! Kami tidak dapat menemukan halaman yang Anda cari.<br> Mungkin sudah dihapus atau alamatnya salah.</p>
        <a href="{{ url('/dashboard') }}" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
