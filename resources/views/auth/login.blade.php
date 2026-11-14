@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Login</h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Masuk</button>
                    </form>
                    <p class="mt-3 mb-0 small">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
                </div>
            </div>
            <p class="text-muted small mt-3">Admin: admin@example.com pw: <code>password</code></p>
            <p class="text-muted small mt-3">User: customer@example.com pw: <code>password</code></p>
        </div>
    </div>
@endsection
