@extends('layouts.kasir')
@section('title', 'Ganti Password Kasir | Garuda Fiber')
@section('heading', 'Ganti Password')

@section('content')
<div class="card card-outline card-primary shadow-sm" style="border-radius:0">
    <div class="card-header"><h2 class="card-title font-weight-bold"><i class="fas fa-lock mr-2" aria-hidden="true"></i>Ganti Password Akun Kasir</h2></div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0 pl-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <div class="row"><div class="col-lg-6">
            <p class="text-muted">Perbarui password untuk akun <strong>{{ auth()->user()->username }}</strong>.</p>
            <form method="POST" action="{{ route('kasir.password.update') }}">
                @csrf @method('PUT')
                <div class="form-group"><label for="currentPassword">Password Saat Ini</label><input id="currentPassword" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password" required></div>
                <div class="form-group"><label for="newPassword">Password Baru</label><input id="newPassword" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" minlength="8" maxlength="128" required><small class="form-text text-muted">Minimal 8 karakter dan berbeda dari password saat ini.</small></div>
                <div class="form-group"><label for="confirmPassword">Konfirmasi Password Baru</label><input id="confirmPassword" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" minlength="8" maxlength="128" required></div>
                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save mr-1" aria-hidden="true"></i>Simpan Password</button><a href="{{ route('kasir.index') }}" class="btn btn-default">Kembali</a>
            </form>
        </div></div>
    </div>
</div>
@endsection
