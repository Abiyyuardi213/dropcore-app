<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #eef2f7;
            font-family: 'Inter', sans-serif;
        }

        .profile-header {
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            padding: 40px 0;
            text-align: center;
            color: white;
            border-radius: 15px;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .profile-container {
            background: white;
            margin-top: -70px;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        label {
            font-weight: 600;
            color: #374151;
        }

        h3, h4 {
            letter-spacing: -0.5px;
        }

        .form-control {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #d8dce3;
        }

        .form-control:focus {
            border-color: #1565c0;
            box-shadow: 0 0 0 0.15rem rgba(21,101,192,.25);
        }

        .btn-primary {
            background: #1565c0;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #0d47a1;
        }
    </style>
</head>

<body>

@include('include.navbar-client')

<div class="container my-5">

    <!-- HEADER -->
    <div class="profile-header">
        @if($user->profile_picture)
            <img src="{{ asset('uploads/profile/' . $user->profile_picture) }}" class="profile-picture" alt="Foto Profil">
        @else
            <img src="{{ asset('images/default.png') }}" class="profile-picture" alt="Default">
        @endif

        <h3 class="mt-3 fw-bold">{{ $user->name }}</h3>
        <p class="mb-0">{{ '@' . $user->username }}</p>
    </div>

    <!-- MAIN FORM SECTION -->
    <div class="profile-container">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h4 class="fw-bold mb-4">Edit Profil</h4>

        <form action="{{ route('customer.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                <div class="col-md-6">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                           value="{{ old('username', $user->username) }}" required>
                </div>

                <div class="col-md-6">
                    <label>Email (opsional)</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}">
                </div>

                <div class="col-md-6">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control"
                           value="{{ old('no_telepon', $user->no_telepon) }}">
                </div>

                <div class="col-md-6">
                    <label>Password Baru (opsional)</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="Isi jika ingin ganti password">
                </div>

                <div class="col-md-6">
                    <label>Foto Profil Baru</label>
                    <input type="file" name="profile_picture" class="form-control">
                </div>

            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

@include('include.footer-client')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
