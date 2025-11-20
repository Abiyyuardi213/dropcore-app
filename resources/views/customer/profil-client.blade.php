<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <style>
        body {
            background: #f1f4f9;
            font-family: 'Inter', sans-serif;
        }
        .profile-header {
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            padding: 60px 0 120px 0; /* Padding lebih besar */
            text-align: center;
            color: white;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .profile-wrapper {
            margin-top: -80px; /* Tarik ke atas header */
        }
        .profile-container {
            background: white;
            padding: 40px 35px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
        }
        .avatar-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,.05);
            text-align: center;
        }
        .profile-picture {
            width: 180px; /* Ukuran lebih besar */
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid #e9ecef; /* Border lebih soft */
            box-shadow: 0 4px 15px rgba(0,0,0,.15);
            background: #e5e7eb;
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            color: #374151;
        }
        .form-control {
            padding: 12px;
            border-radius: 12px;
            border: 1.4px solid #d7dbe2;
        }
        .form-control:focus {
            border-color: #1565c0;
            box-shadow: 0 0 0 .2rem rgba(21,101,192,.25);
        }
        .btn-primary {
            background: #1565c0;
            border-radius: 12px;
            padding: 12px 35px;
            font-weight: 600;
            border: none;
        }
        .btn-primary:hover {
            background: #0d47a1;
        }
        .btn-secondary-outline {
            color: #6c757d;
            border: 1.5px solid #d7dbe2;
            background-color: white;
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 500;
        }
        .btn-secondary-outline:hover {
            background-color: #f1f4f9;
        }
        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #1f2937;
            border-bottom: 3px solid #f1f4f9;
            padding-bottom: 10px;
        }
        .modal-xl-custom {
            --bs-modal-width: 380px;
        }
    </style>
</head>
<body>
@include('include.navbar-client')

<div class="profile-header">
    <h1 class="fw-bold">Pengaturan Profil</h1>
    <p class="mt-2 opacity-75">Kelola informasi dan foto profil akun Anda</p>
</div>

<div class="container mb-5">
    <div class="row g-4 profile-wrapper">
        <div class="col-lg-4">
            <div class="avatar-card h-100">
                <h5 class="fw-bold mb-4">Foto Profil Saat Ini</h5>
                @if($user->profile_picture)
                    <img id="profilePreview" src="{{ asset('uploads/profile/' . $user->profile_picture) }}"
                         class="profile-picture" alt="Foto Profil">
                @else
                    <img id="profilePreview" src="{{ asset('images/default.png') }}"
                         class="profile-picture" alt="Default">
                @endif

                <p class="text-muted mt-3 mb-4 small">Gambar harus dalam format JPG, PNG, atau GIF dan berukuran kurang dari 5MB.</p>

                <button type="button" class="btn btn-secondary-outline w-100 mb-2" data-bs-toggle="modal"
                        data-bs-target="#viewPhotoModal">
                    <i class="bi bi-eye me-2"></i>Lihat Foto Penuh
                </button>
                <button type="button" class="btn btn-primary w-100" onclick="document.getElementById('profileInputFile').click()">
                    <i class="bi bi-cloud-arrow-up me-2"></i>Ganti Foto Profil
                </button>
                <input type="file" id="profileInputFile" name="profile_picture_hidden" style="display: none;" accept="image/*">
            </div>
        </div>

        <div class="col-lg-8">
            <div class="profile-container h-100">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <h4 class="section-title"><i class="bi bi-person-circle me-2"></i>Informasi Akun</h4>
                <form action="{{ route('customer.profil.update') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control"
                                   value="{{ old('username', $user->username) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email (opsional)</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telepon" class="form-control"
                                   value="{{ old('no_telepon', $user->no_telepon) }}">
                        </div>
                        <div class="col-12">
                            <h4 class="section-title mt-4"><i class="bi bi-key me-2"></i>Ganti Password</h4>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Baru (opsional)</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Isi jika ingin mengganti password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <input type="hidden" name="cropped_image" id="croppedImage">
                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl-custom">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Crop Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <img id="cropImage" style="max-width: 100%;">
                </div>
                <div class="mt-3">
                    <p class="text-muted small mb-0">Sesuaikan area crop untuk mendapatkan foto profil terbaik (rasio 1:1).</p>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary-outline" data-bs-dismiss="modal">Batal</button>
                <button id="cropBtn" class="btn btn-primary">Crop & Simpan</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="viewPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Foto Profil Lengkap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fullProfileView" src="{{ $user->profile_picture ? asset('uploads/profile/' . $user->profile_picture) : asset('images/default.png') }}"
                     style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,.1);" alt="Foto Profil Penuh">
            </div>
        </div>
    </div>
</div>


@include('include.footer-client')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let cropper;

document.getElementById('profileInputFile').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (e) {
        document.getElementById('cropImage').src = e.target.result;

        const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
        cropModal.show();

        document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
            if (cropper) cropper.destroy();

            cropper = new Cropper(document.getElementById('cropImage'), {
                aspectRatio: 1,
                viewMode: 1,
                movable: true,
                zoomable: true,
                responsive: true
            });
        }, { once: true });
    };

    reader.readAsDataURL(file);
});

document.getElementById('cropBtn').addEventListener('click', function () {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({ width: 450, height: 450 });
    document.getElementById('profilePreview').src = canvas.toDataURL();
    document.getElementById('fullProfileView').src = canvas.toDataURL();
    document.getElementById('croppedImage').value = canvas.toDataURL();
    document.getElementById('profileInputFile').value = '';

    bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
});

document.getElementById('viewPhotoModal').addEventListener('show.bs.modal', function () {
    const previewSrc = document.getElementById('profilePreview').src;
    document.getElementById('fullProfileView').src = previewSrc;
});
</script>
</body>
</html>
