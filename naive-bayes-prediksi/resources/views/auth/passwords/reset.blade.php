@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .reset-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
        background: #ffffff;
        animation: fadeIn 0.8s ease-in-out;
    }
    .reset-header {
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
        <div class="card reset-card">
            <div class="reset-header">  
                <div>{{ __('Reset Your Password') }}</div>
            </div>

            <div class="card-body p-5">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required>

                        @error('email')
                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>

                    <div class="text-center">
                        <a class="btn btn-link text-decoration-none" href="{{ route('login') }}">
                            {{ __('Back to Login') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
