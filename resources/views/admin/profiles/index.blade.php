@extends('layouts.app')

@section('title', __('Admin - Profiles'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Profile Management') }}</h1>
    @if($profile)
        <a href="{{ route('admin.profiles.edit', $profile) }}" class="btn btn-primary">{{ __('Edit Profile') }}</a>
    @else
        <a href="{{ route('admin.profiles.create') }}" class="btn btn-primary">{{ __('Create Profile') }}</a>
    @endif
</div>

<div class="clean-card p-4">
    @if($profile)
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <span class="badge blue-badge mb-3">{{ __('Singleton Profile') }}</span>
                <h2 class="section-title h4 mb-2">{{ $profile->full_name }}</h2>
                <p class="text-muted mb-3">{{ $profile->title }}</p>
                <p class="mb-3">{{ $profile->bio }}</p>
                <div class="small text-muted mb-1">{{ __('Email') }}: {{ $profile->email ?: '-' }}</div>
                <div class="small text-muted mb-1">{{ __('Phone') }}: {{ $profile->phone ?: '-' }}</div>
                <div class="small text-muted mb-1">{{ __('Location') }}: {{ $profile->location ?: '-' }}</div>
                <div class="small text-muted mb-1">{{ __('LinkedIn') }}: {{ $profile->linkedin_url ?: '-' }}</div>
                <div class="small text-muted">{{ __('GitHub') }}: {{ $profile->github_url ?: '-' }}</div>
            </div>
            <div class="col-lg-5">
                <div class="soft-panel p-3 h-100">
                    <h3 class="section-title h6 mb-3">{{ __('Chart Configuration') }}</h3>
                    <ul class="list-unstyled mb-0 small text-muted">
                        @foreach(($profile->expertise_chart ?? []) as $item)
                            <li class="mb-2">{{ $item['label'] ?? '-' }}: <strong>{{ $item['value'] ?? 0 }}%</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mb-0">{{ __('No profile exists yet. Create one profile to manage your public identity and home chart.') }}</div>
    @endif
</div>
@endsection
