@extends('layouts.app')

@section('title', __('Admin Login'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-8">
        <div class="clean-card p-4 p-lg-5 auth-card fade-in-up">
            <div class="text-center mb-4">
                <span class="badge blue-badge mb-2">{{ __('Protected Area') }}</span>
                <h1 class="section-title h3 mb-1">{{ __('Admin Login') }}</h1>
                <p class="text-muted mb-0">{{ __('Sign in to access the admin dashboard.') }}</p>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" class="row g-3">
                @csrf

                <div class="col-12">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('Password') }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                        <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                    </div>
                </div>

                <div class="col-12 d-grid">
                    <button class="btn btn-primary btn-lg">{{ __('Sign In') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
