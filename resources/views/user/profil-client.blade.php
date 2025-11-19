<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
        }
        .profile-picture {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #0d47a1;
        }
    </style>
</head>
<body>
    @include('include.navbar-client')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="profile-card shadow">
                    <h3 class="fw-bold text-center mb-4">Profil Saya</h3>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="text-center mb-4">
                        @if($user->profile_picture)
                            <img src="{{ asset('uploads/profile/' . $user->profile_picture) }}"
                                 class="profile-picture" alt="Foto Profil">
                        @else
                            <img src="{{ asset('images/default.png') }}"
                                 class="profile-picture" alt="Default">
                        @endif
                    </div>

                    <form action="{{ route('user.updateProfil') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email (opsional)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $user->no_telepon) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru (opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Isi jika ingin ganti password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Profil Baru</label>
                            <input type="file" name="profile_picture" class="form-control">
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('include.footer-client')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
