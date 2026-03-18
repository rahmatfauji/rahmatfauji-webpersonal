@extends('layouts.app')

@section('title', __('Admin Dashboard'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
    <h1 class="section-title mb-0">{{ __('Admin Dashboard') }}</h1>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 fade-in-up fade-delay-1">
        <div class="clean-card p-3 admin-stat">
            <div class="text-muted">{{ __('Total Profiles') }}</div>
            <div class="display-6 fw-bold">{{ $profileCount }}</div>
        </div>
    </div>
    <div class="col-md-3 fade-in-up fade-delay-2">
        <div class="clean-card p-3 admin-stat">
            <div class="text-muted">{{ __('Total Articles') }}</div>
            <div class="display-6 fw-bold">{{ $blogCount }}</div>
        </div>
    </div>
    <div class="col-md-3 fade-in-up fade-delay-3">
        <div class="clean-card p-3 admin-stat">
            <div class="text-muted">{{ __('Total Portfolio') }}</div>
            <div class="display-6 fw-bold">{{ $portfolioCount }}</div>
        </div>
    </div>
    <div class="col-md-3 fade-in-up fade-delay-3">
        <div class="clean-card p-3 admin-stat">
            <div class="text-muted">{{ __('Total Slides') }}</div>
            <div class="display-6 fw-bold">{{ $slideCount }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 fade-in-up fade-delay-1">
        <div class="clean-card p-3 admin-stat">
            <div class="text-muted">{{ __('Total Blog Views') }}</div>
            <div class="display-6 fw-bold">{{ number_format($totalBlogViews) }}</div>
        </div>
    </div>
    <div class="col-md-6 fade-in-up fade-delay-2">
        <div class="clean-card p-3 admin-stat">
            <div class="text-muted">{{ __('Total Portfolio Views') }}</div>
            <div class="display-6 fw-bold">{{ number_format($totalPortfolioViews) }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="clean-card p-4">
            <h5 class="section-title">{{ __('Latest Articles') }}</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Published') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($latestPosts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->slug }}</td>
                            <td>{{ $post->is_published ? __('Yes') : __('No') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">{{ __('No data yet.') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="soft-panel p-4 h-100">
            <h5 class="section-title">{{ __('Admin Navigation') }}</h5>
            <div class="d-grid gap-2 mt-3">
                <a href="{{ route('admin.profiles.index') }}" class="btn btn-outline-primary">{{ __('Manage Profiles') }}</a>
                <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-primary">{{ __('Manage Blog') }}</a>
                <a href="{{ route('admin.portfolio-items.index') }}" class="btn btn-outline-primary">{{ __('Manage Portfolio') }}</a>
                <a href="{{ route('admin.slides.index') }}" class="btn btn-outline-primary">{{ __('Manage Slideshow') }}</a>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-primary">{{ __('Visitor Activity Logs') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
