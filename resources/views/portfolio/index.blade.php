@extends('layouts.app')

@section('title', __('Portfolio'))
@section('meta_description', 'Explore portfolio projects by Rahmat Fauji: Power BI dashboards, analytics automation, and Power Apps business solutions.')
@section('meta_type', 'website')
@section('meta_canonical', route('portfolio.index'))

@section('content')
<section class="clean-card p-4 p-lg-5 mb-4 fade-in-up public-intro-panel section-decor">
    <span class="decor-orb decor-orb-b" data-parallax="0.05"></span>
    <span class="decor-orb decor-orb-c" data-parallax="-0.04"></span>
    <div>
        <div>
            <span class="section-kicker">{{ __('Data + Product Design') }}</span>
            <h1 class="section-title mb-1">{{ __('Portfolio') }}</h1>
            <p class="text-muted mb-0">{{ __('A curated collection of digital products, campaigns, and dashboard experiences.') }}</p>
        </div>
    </div>
</section>

<div class="row g-4">
    @forelse($items as $item)
        <div class="col-md-6 col-lg-4 fade-in-up fade-delay-1" data-fade-in>
            <article class="clean-card h-100 public-portfolio-card">
                @if($item->image_url)
                    <div class="portfolio-media-wrap image-skeleton" style="height: 180px;">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="img-fluid rounded-top js-skeleton-image" style="height: 180px; width: 100%; object-fit: cover;">
                    </div>
                @endif
                <div class="p-3">
                    <span class="badge blue-badge mb-2">{{ $item->category }}</span>
                    <h5>{{ $item->title }}</h5>
                    <p>{{ $item->summary }}</p>
                    @if($item->project_url)
                        <a href="{{ route('portfolio.visit', $item) }}" class="text-decoration-none" target="_blank" rel="noreferrer">{{ __('View project') }}</a>
                    @endif
                    @include('partials.share-buttons', [
                        'url' => $item->project_url ?: route('portfolio.index'),
                        'title' => $item->title,
                    ])
                </div>
            </article>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-info">{{ __('No portfolio items available yet.') }}</div></div>
    @endforelse
</div>

<div class="mt-4 pagination-shell">{{ $items->links() }}</div>
@endsection
