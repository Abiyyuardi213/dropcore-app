<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>

    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif !important;
        }

        .profile-container {
            text-align: center;
            margin-bottom: 25px;
        }

        .profile-img {
            width: 170px;
            height: 170px;
            border-radius: 12px;
            object-fit: cover;
            border: 4px solid #ffffff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: 0.3s;
        }

        .profile-img:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
        }

        .file-input {
            width: 260px;
            margin: 15px auto 0 auto;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #444;
        }

        .card-custom {
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .form-group label {
            font-weight: 600;
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
                <h1 class="m-0 text-dark">Edit Profil</h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid d-flex justify-content-center">

                <div class="card card-custom shadow w-100" style="max-width: 900px;">

                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-edit mr-2"></i>Perbarui Profil Anda
                        </h3>
                    </div>

                    <form action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">

                            <!-- FOTO PROFIL DI TENGAH -->
                            <div class="profile-container">

                                <img src="{{ Auth::user()->profile_picture
                                    ? asset('uploads/profile/' . Auth::user()->profile_picture)
                                    : asset('image/default-avatar.png') }}"
                                     class="profile-img"
                                     id="previewImage">

                                <div class="file-input">
                                    <input type="file" name="profile_picture" class="form-control mt-3"
                                           onchange="previewPhoto(event)">

                                    <!-- BUTTON LIHAT FOTO -->
                                    <button type="button" class="btn btn-info btn-sm mt-3 w-100"
                                            data-toggle="modal" data-target="#modalFoto">
                                        <i class="fas fa-eye"></i> Lihat Foto
                                    </button>
                                </div>
                            </div>

                            <hr>

                            <div class="form-section-title text-center">Informasi Akun</div>

                            <!-- FORM 2 KOLOM -->
                            <div class="row">

                                <!-- KIRI -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ Auth::user()->name }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control"
                                               value="{{ Auth::user()->username }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ Auth::user()->email }}">
                                    </div>
                                </div>

                                <!-- KANAN -->
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label>No. Telepon</label>
                                        <input type="text" name="no_telepon" class="form-control"
                                               value="{{ Auth::user()->no_telepon }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Password (kosongkan jika tidak diubah)</label>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="********">
                                    </div>

                                    <div class="form-group">
                                        <label>Peran</label>
                                        <input type="text" class="form-control"
                                               value="{{ Auth::user()->role->role_name ?? '-' }}" disabled>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </section>

    </div>

    @include('include.footerSistem')
</div>

<!-- ======================== -->
<!--        MODAL FOTO        -->
<!-- ======================== -->
<div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="modalFotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoLabel">Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <img src="{{ Auth::user()->profile_picture
                    ? asset('uploads/profile/' . Auth::user()->profile_picture)
                    : asset('image/default-avatar.png') }}"
                     id="modalPreviewImage"
                     class="img-fluid rounded"
                     style="max-height: 350px;">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>


<script>
function previewPhoto(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('previewImage').src = reader.result;
        document.getElementById('modalPreviewImage').src = reader.result; // update modal foto
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
