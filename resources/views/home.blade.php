@extends('layouts.app')

@section('title', __('Home'))
@section('meta_description', 'Rahmat Fauji personal website featuring Data Analytics, Power BI dashboards, Power Apps solutions, and practical project insights.')
@section('meta_type', 'website')
@section('meta_canonical', route('home'))

@section('content')
@if($slides->isNotEmpty())
    <div id="heroCarousel" class="carousel slide hero-shell mb-5 fade-in-up" data-bs-ride="carousel">
        <div class="carousel-indicators hero-indicators">
            @foreach($slides as $slide)
                <button
                    type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide-to="{{ $loop->index }}"
                    class="{{ $loop->first ? 'active' : '' }}"
                    aria-current="{{ $loop->first ? 'true' : 'false' }}"
                    aria-label="{{ __('Slide') }} {{ $loop->iteration }}"
                ></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($slides as $slide)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="hero-slide d-flex align-items-center p-4 p-lg-5 section-decor" style="background-image: url('{{ $slide->image_url }}');">
                        <span class="decor-orb decor-orb-a" data-parallax="0.05"></span>
                        <span class="decor-orb decor-orb-c" data-parallax="-0.03"></span>
                        <div class="hero-content text-white" data-parallax="-0.08">
                            <h1 class="display-5 fw-bold">{{ $slide->title }}</h1>
                            @if($slide->subtitle)
                                <p class="lead">{{ $slide->subtitle }}</p>
                            @endif
                            @if($slide->button_text && $slide->button_url)
                                <a href="{{ $slide->button_url }}" class="btn btn-light mt-2">{{ $slide->button_text }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<section class="insight-ribbon clean-card p-3 p-lg-4 mb-4 fade-in-up fade-delay-1 section-decor">
    <span class="decor-orb decor-orb-b" data-parallax="0.03"></span>
    <div class="d-flex flex-wrap align-items-center gap-2 gap-lg-3">
        <span class="insight-label">{{ __('Data-Driven Personal Brand') }}</span>
        <a href="{{ route('blog.index') }}" class="insight-pill insight-link">{{ __('Featured Articles') }}: <strong>{{ $posts->count() }}</strong></a>
        <a href="{{ route('portfolio.index') }}" class="insight-pill insight-link">{{ __('Featured Projects') }}: <strong>{{ $portfolioItems->count() }}</strong></a>
        <a href="{{ $slides->isNotEmpty() ? '#heroCarousel' : route('home') }}" class="insight-pill insight-link">{{ __('Active Slides') }}: <strong>{{ $slides->count() }}</strong></a>
    </div>
</section>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="clean-card p-4 h-100 fade-in-up fade-delay-2">
            <span class="badge blue-badge mb-3">{{ __('About Me') }}</span>
            <h2 class="section-title">{{ optional($profile)->full_name ?? __('Your Name') }}</h2>
            <p class="text-muted mb-2">{{ optional($profile)->title ?? __('Add your profile details from the admin panel.') }}</p>
            <p class="mb-0">{{ optional($profile)->bio ?? __('Your bio will appear here once profile data is saved.') }}</p>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="soft-panel p-4 h-100 fade-in-up fade-delay-3">
            <h5 class="section-title">{{ __('Quick Menu') }}</h5>
            <div class="d-grid gap-2 mt-3">
                <a href="{{ route('profile') }}" class="btn btn-outline-primary">{{ __('View Profile') }}</a>
                <a href="{{ route('blog.index') }}" class="btn btn-outline-primary">{{ __('Read Blog') }}</a>
                <a href="{{ route('portfolio.index') }}" class="btn btn-outline-primary">{{ __('View Portfolio') }}</a>
            </div>
        </div>
    </div>
</div>

<section class="mb-5 fade-in-up fade-delay-2 section-decor">
    <span class="decor-orb decor-orb-c" data-parallax="0.04"></span>
    <div class="d-flex justify-content-between align-items-end mb-3 section-header-group">
        <div>
            <span class="section-kicker">{{ __('Knowledge Base') }}</span>
            <h3 class="section-title mb-0">{{ __('Latest Articles') }}</h3>
        </div>
        <a href="{{ route('blog.index') }}" class="btn btn-sm btn-primary">{{ __('All Articles') }}</a>
    </div>
    <div class="row g-4">
        @forelse($posts as $post)
            <div class="col-md-4">
                <article class="clean-card h-100 p-3 public-article-card">
                    <div class="small text-muted mb-2">{{ optional($post->published_at)->format('d M Y') }}</div>
                    <h5>{{ $post->title }}</h5>
                    <p class="mb-2">{{ $post->excerpt }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-primary text-decoration-none">{{ __('Read more') }}</a>
                    @include('partials.share-buttons', [
                        'url' => route('blog.show', $post),
                        'title' => $post->title,
                    ])
                </article>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info mb-0">{{ __('No articles available yet.') }}</div></div>
        @endforelse
    </div>
</section>

<section class="fade-in-up fade-delay-3 section-decor">
    <span class="decor-orb decor-orb-a" data-parallax="-0.05"></span>
    <div class="d-flex justify-content-between align-items-end mb-3 section-header-group">
        <div>
            <span class="section-kicker">{{ __('Selected Work') }}</span>
            <h3 class="section-title mb-0">{{ __('Featured Portfolio') }}</h3>
        </div>
        <a href="{{ route('portfolio.index') }}" class="btn btn-sm btn-primary">{{ __('All Projects') }}</a>
    </div>
    <div class="row g-4">
        @forelse($portfolioItems as $item)
            <div class="col-md-6 col-lg-4">
                <article class="clean-card h-100 p-3 public-portfolio-card">
                    <span class="badge blue-badge mb-2">{{ $item->category }}</span>
                    <h5>{{ $item->title }}</h5>
                    <p class="mb-2">{{ $item->summary }}</p>
                    @if($item->project_url)
                        <a class="text-primary text-decoration-none" href="{{ $item->project_url }}" target="_blank" rel="noreferrer">{{ __('Visit project') }}</a>
                    @endif
                    @include('partials.share-buttons', [
                        'url' => $item->project_url ?: route('portfolio.index'),
                        'title' => $item->title,
                    ])
                </article>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info mb-0">{{ __('No portfolio items available yet.') }}</div></div>
        @endforelse
    </div>
</section>
@endsection
