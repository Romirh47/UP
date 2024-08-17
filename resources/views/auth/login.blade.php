@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus autocomplete="username">
        </div>
        <div class="mb-4">
            <label for="exampleInputPassword1" class="form-label">Kata Sandi</label>
            <div class="input-group">
                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Masukkan kata sandi Anda" required autocomplete="current-password">
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Masuk</button>
        <div class="d-flex align-items-center justify-content-center">
            <p class="fs-4 mb-0 fw-bold">Belum punya akun?</p>
            <a class="text-primary fw-bold ms-2" href="{{ route('register') }}">Buat akun</a>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('exampleInputPassword1');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>
@endsection