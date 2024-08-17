@extends('layouts.guest')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" aria-describedby="nameHelp" name="name"
                value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required autofocus autocomplete="name">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus autocomplete="username">
        </div>
        <div class="mb-4">
            <label for="exampleInputPassword1" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Masukkan kata sandi Anda (minimal 8 karakter)" required
                autocomplete="new-password">
            <small id="passwordHelpBlock" class="form-text text-muted">
                Contoh: P@ssw0rd123
            </small>
        </div>
        <div class="mb-4">
            <label for="exampleInputPassword2" class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="exampleInputPassword2" name="password_confirmation" placeholder="Konfirmasi kata sandi Anda" required
                autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-4 rounded-2">Daftar</button>
        <div class="d-flex align-items-center justify-content-center">
            <p class="fs-5 mb-0 fw-bold">Sudah punya akun?</p>
            <a class="text-primary fw-bold ms-2" href="{{ route('login') }}">Masuk</a>
        </div>
    </form>
@endsection
