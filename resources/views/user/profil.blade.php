<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Garuda Fiber</title>
    <link rel="icon" type="image/png" href="{{ asset('image/dropcore-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        .profile-user-img { width: 100px; height: 100px; object-fit: cover; }
        .profile-summary-value { max-width: 65%; overflow-wrap: anywhere; text-align: right; }
        #cropImage { display: block; max-width: 100%; }
        .profile-crop-container { height: 360px; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('include.navbarSistem')
    @include('include.sidebar')
    <div class="content-wrapper">
        <section class="content-header"><div class="container-fluid"><div class="row mb-2">
            <div class="col-sm-6"><h1>Profil Saya</h1></div>
            <div class="col-sm-6"><ol class="breadcrumb float-sm-right"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item active">Profil</li></ol></div>
        </div></div></section>
        <section class="content"><div class="container-fluid">
            @if($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0 pl-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <div id="profilePhotoError" class="alert alert-warning" role="alert" hidden></div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="card card-primary card-outline"><div class="card-body box-profile">
                        <div class="text-center"><img id="previewImage" class="profile-user-img img-fluid img-circle" src="{{ $user->profile_picture ? asset('uploads/profile/'.$user->profile_picture) : asset('image/default-user.png') }}" alt="Foto profil {{ $user->name }}" onerror="this.onerror=null;this.src='{{ asset('image/default-user.png') }}';"></div>
                        <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        <p class="text-muted text-center text-uppercase">{{ $user->role->role_name ?? 'Pengguna' }}</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item d-flex justify-content-between"><b>Username</b><span class="profile-summary-value">{{ $user->username }}</span></li>
                            <li class="list-group-item d-flex justify-content-between"><b>Status Akun</b><span class="badge badge-{{ $user->status_kepegawaian === 'aktif' ? 'success' : 'secondary' }}">{{ $user->status_kepegawaian === 'aktif' ? 'Aktif' : 'Nonaktif' }}</span></li>
                            <li class="list-group-item d-flex justify-content-between"><b>Terdaftar</b><span>{{ $user->created_at?->format('d/m/Y') ?? '-' }}</span></li>
                        </ul>
                        <button id="chooseProfilePhoto" class="btn btn-primary btn-block" type="button"><i class="fas fa-camera mr-1" aria-hidden="true"></i>Ubah Foto Profil</button>
                        <p class="text-muted text-sm text-center mt-2 mb-0">Foto baru disimpan bersama perubahan profil.</p>
                    </div></div>
                    <div class="card card-primary">
                        <div class="card-header"><h3 class="card-title">Kontak Pengguna</h3></div>
                        <div class="card-body">
                            <strong><i class="fas fa-envelope mr-1" aria-hidden="true"></i>Email</strong><p class="text-muted text-break">{{ $user->email ?: 'Belum diisi' }}</p><hr>
                            <strong><i class="fas fa-phone mr-1" aria-hidden="true"></i>Telepon</strong><p class="text-muted">{{ $user->no_telepon ?: 'Belum diisi' }}</p><hr>
                            <strong><i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i>Alamat</strong><p class="text-muted text-break mb-0">{{ $user->alamat ?: 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="card">
                        <div class="card-header p-2"><ul class="nav nav-pills" role="tablist">
                            <li class="nav-item"><a class="nav-link {{ $errors->any() ? '' : 'active' }}" id="profile-info-tab" href="#profile-info" data-toggle="tab" role="tab" aria-controls="profile-info" aria-selected="{{ $errors->any() ? 'false' : 'true' }}">Informasi Akun</a></li>
                            <li class="nav-item"><a class="nav-link {{ $errors->any() ? 'active' : '' }}" id="profile-edit-tab" href="#profile-edit" data-toggle="tab" role="tab" aria-controls="profile-edit" aria-selected="{{ $errors->any() ? 'true' : 'false' }}">Edit Profil</a></li>
                        </ul></div>
                        <div class="card-body"><div class="tab-content">
                            <div class="tab-pane {{ $errors->any() ? '' : 'active' }}" id="profile-info" role="tabpanel" aria-labelledby="profile-info-tab">
                                <h5 class="mb-3">Informasi Pengguna</h5>
                                <dl class="row">
                                    <dt class="col-sm-3">Nama Lengkap</dt><dd class="col-sm-9 text-break">{{ $user->name }}</dd>
                                    <dt class="col-sm-3">Username</dt><dd class="col-sm-9 text-break">{{ $user->username }}</dd>
                                    <dt class="col-sm-3">Peran Sistem</dt><dd class="col-sm-9">{{ $user->role->role_name ?? '-' }}</dd>
                                    <dt class="col-sm-3">Jenis Kelamin</dt><dd class="col-sm-9">{{ ['L'=>'Laki-laki', 'P'=>'Perempuan'][$user->jenis_kelamin] ?? 'Belum diisi' }}</dd>
                                    <dt class="col-sm-3">Terdaftar Pada</dt><dd class="col-sm-9">{{ $user->created_at?->format('d/m/Y H:i') ?? '-' }}</dd>
                                    <dt class="col-sm-3">Terakhir Diperbarui</dt><dd class="col-sm-9">{{ $user->updated_at?->format('d/m/Y H:i') ?? '-' }}</dd>
                                </dl>
                            </div>
                            <div class="tab-pane {{ $errors->any() ? 'active' : '' }}" id="profile-edit" role="tabpanel" aria-labelledby="profile-edit-tab">
                                <form id="profileForm" class="form-horizontal" action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" class="sr-only" name="profile_picture" id="profileInput" accept="image/jpeg,image/png,image/webp" aria-label="Pilih foto profil">
                                    @foreach(['name'=>'Nama Lengkap','username'=>'Username','email'=>'Email','no_telepon'=>'No. Telepon / WA'] as $field=>$label)
                                        <div class="form-group row"><label for="{{ $field }}" class="col-sm-3 col-form-label">{{ $label }} @if(in_array($field,['name','username']))<span class="text-danger">*</span>@endif</label><div class="col-sm-9">
                                            <input type="{{ $field === 'email' ? 'email' : 'text' }}" name="{{ $field }}" id="{{ $field }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field, $user->$field) }}" maxlength="{{ $field === 'no_telepon' ? 20 : 255 }}" @required(in_array($field,['name','username']))>
                                            @error($field)<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div></div>
                                    @endforeach
                                    <div class="form-group row"><label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label><div class="col-sm-9"><select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" id="jenis_kelamin"><option value="">Pilih Jenis Kelamin</option><option value="L" @selected(old('jenis_kelamin',$user->jenis_kelamin)==='L')>Laki-laki</option><option value="P" @selected(old('jenis_kelamin',$user->jenis_kelamin)==='P')>Perempuan</option></select>@error('jenis_kelamin')<span class="invalid-feedback">{{ $message }}</span>@enderror</div></div>
                                    <div class="form-group row"><label for="alamat" class="col-sm-3 col-form-label">Alamat</label><div class="col-sm-9"><textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat',$user->alamat) }}</textarea>@error('alamat')<span class="invalid-feedback">{{ $message }}</span>@enderror</div></div>
                                    <div class="form-group row"><label for="password" class="col-sm-3 col-form-label">Password Baru</label><div class="col-sm-9"><input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" minlength="6" placeholder="Kosongkan jika tidak diubah">@error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror<small class="form-text text-muted">Minimal 6 karakter. Kosongkan untuk mempertahankan password saat ini.</small></div></div>
                                    <div class="form-group row mb-0"><div class="offset-sm-3 col-sm-9"><p id="profilePhotoStatus" class="text-muted text-sm" role="status"></p><button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1" aria-hidden="true"></i>Simpan Perubahan</button></div></div>
                                </form>
                            </div>
                        </div></div>
                    </div>
                </div>
            </div>
        </div></section>
    </div>
    @include('include.footerSistem')
</div>
<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="cropModalTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title" id="cropModalTitle">Sesuaikan Foto Profil</h5><button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button></div>
        <div class="modal-body"><div class="profile-crop-container"><img id="cropImage" alt="Pratinjau pemotongan foto"></div></div>
        <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="button" class="btn btn-primary" id="cropBtn">Gunakan Foto</button></div>
    </div></div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@include('services.ToastModal')
@include('services.LogoutModal')
<script src="{{ asset('resources/js/AdminProfile.js') }}" defer></script>
</body></html>
