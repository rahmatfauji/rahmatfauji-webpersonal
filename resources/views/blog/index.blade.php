@extends('layouts.app')

@section('title', __('Blog'))
@section('meta_description', 'Read articles by Rahmat Fauji about data analytics, Power BI reporting, Power Apps development, and digital product strategy.')
@section('meta_type', 'website')
@section('meta_canonical', route('blog.index'))

@section('content')
<section class="clean-card p-4 p-lg-5 mb-4 fade-in-up public-intro-panel section-decor">
    <span class="decor-orb decor-orb-a" data-parallax="-0.04"></span>
    <span class="decor-orb decor-orb-b" data-parallax="0.06"></span>
    <div>
        <div>
            <span class="section-kicker">{{ __('Insights Hub') }}</span>
            <h1 class="section-title mb-1">{{ __('Blog') }}</h1>
            <p class="text-muted mb-0">{{ __('Actionable notes on web strategy, product thinking, and digital growth.') }}</p>
        </div>
    </div>
</section>

<div class="row g-4">
    @forelse($posts as $post)
        <div class="{{ $loop->first ? 'col-12' : 'col-md-6 col-lg-4' }} fade-in-up {{ $loop->first ? '' : 'fade-delay-1' }}" data-fade-in>
            <article class="clean-card h-100 p-3 public-article-card {{ $loop->first ? 'featured-article-card p-lg-4' : '' }}">
                @if($loop->first && $post->featured_image)
                    <div class="featured-article-media mb-3 mb-lg-4 image-skeleton">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="img-fluid js-skeleton-image">
                    </div>
                @endif
                <div class="small text-muted mb-2">{{ optional($post->published_at)->format('d M Y') }}</div>
                <h5>{{ $post->title }}</h5>
                <p>{{ $post->excerpt }}</p>
                <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none">{{ __('Read details') }}</a>
                @include('partials.share-buttons', [
                    'url' => route('blog.show', $post),
                    'title' => $post->title,
                ])
            </article>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-info">{{ __('No articles available yet.') }}</div></div>
    @endforelse
</div>

<div class="mt-4 pagination-shell">{{ $posts->links() }}</div>
@endsection
