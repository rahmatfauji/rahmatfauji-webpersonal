@extends('layouts.app')

@section('title', __('Admin - Change Password'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Change Password') }}</h1>
</div>

<div class="clean-card p-4" data-fade-in>
    <form method="POST" action="{{ route('admin.password.update') }}" class="row g-3">
        @csrf
        @method('PUT')

        <div class="col-12">
            <label class="form-label">{{ __('Current Password') }}</label>
            <input
                type="password"
                name="current_password"
                class="form-control @error('current_password') is-invalid @enderror"
                required
                autocomplete="current-password"
            >
            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-lg-6">
            <label class="form-label">{{ __('New Password') }}</label>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                minlength="8"
                autocomplete="new-password"
            >
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-lg-6">
            <label class="form-label">{{ __('Confirm New Password') }}</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control"
                required
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary">{{ __('Update Password') }}</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">{{ __('Back to Dashboard') }}</a>
        </div>
    </form>
</div>
@endsection
