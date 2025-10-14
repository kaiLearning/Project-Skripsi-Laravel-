@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
        background: #ffffff;
        animation: fadeIn 0.8s ease-in-out;
    }

    .login-header {
        background: #f4f6f9;
        padding: 30px;
        font-size: 26px;
        font-weight: 600;
        text-align: center;
        color: #333;
        border-bottom: 1px solid #ddd;
    }

    .form-control {
        border-radius: 12px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(116, 235, 213, 0.4);
        border-color: #74ebd5;
    }

    .btn-primary {
        background: linear-gradient(to right, #74ebd5, #ACB6E5);
        border: none;
        border-radius: 25px;
        padding: 10px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        color: #fff;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #60d9c9, #9fb4e1);
    }

    .form-check-input:checked {
        background-color: #74ebd5;
        border-color: #74ebd5;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="col-md-6">
        <div class="card login-card">
            <div class="login-header">
                <div>{{ __('Toko Sembako Kaka') }}</div>
            </div>

            <div class="card-body p-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autofocus>

                        @error('email')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">{{ __('Kata Sandi') }}</label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required>

                        @error('password')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                   <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Masuk') }}
                        </button>
                    </div>

                    <div class="d-grid mb-3">
                        <a class="btn btn-primary" href="{{ route('register') }}">
                            {{ __('Daftar') }}
                        </a>
                    </div>

                   
                </form>
            </div>
        </div>
    </div>
</div>
@endsection