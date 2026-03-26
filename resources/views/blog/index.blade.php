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

<section class="clean-card p-4 mb-4 fade-in-up blog-filter-shell">
    <form method="GET" action="{{ route('blog.index') }}" class="row g-3 align-items-end">
        <div class="col-lg-5">
            <label class="form-label">{{ __('Search Articles') }}</label>
            <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="{{ __('Search by title, excerpt, or content') }}">
        </div>
        <div class="col-md-4 col-lg-3">
            <label class="form-label">{{ __('Category') }}</label>
            <select name="category" class="form-select">
                <option value="">{{ __('All Categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ $activeCategory === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-lg-2">
            <label class="form-label">{{ __('Tag') }}</label>
            <input type="text" name="tag" value="{{ $activeTag }}" class="form-control" placeholder="{{ __('Tag') }}">
        </div>
        <div class="col-md-4 col-lg-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary flex-fill">{{ __('Apply') }}</button>
            <a href="{{ route('blog.index') }}" class="btn btn-sm btn-outline-secondary flex-fill">{{ __('Reset') }}</a>
        </div>
    </form>

    @if($popularTags->isNotEmpty())
        <div class="d-flex flex-wrap gap-2 mt-3">
            @foreach($popularTags as $tagItem)
                <a href="{{ route('blog.index', ['tag' => $tagItem['name']]) }}" class="blog-tag-chip {{ $activeTag === $tagItem['name'] ? 'is-active' : '' }}">
                    #{{ $tagItem['name'] }}
                    <span>{{ $tagItem['count'] }}</span>
                </a>
            @endforeach
        </div>
    @endif
</section>

<div class="row g-4">
    @forelse($posts as $post)
        <div class="col-md-6 col-lg-4 fade-in-up fade-delay-1" data-fade-in>
            <article class="clean-card h-100 p-3 public-article-card">
                @if($post->featured_image)
                    <div class="mb-3 image-skeleton" style="height: 180px; overflow: hidden; border-radius: 0.5rem;">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="img-fluid js-skeleton-image" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif
                <div class="small text-muted mb-2">{{ optional($post->published_at)->format('d M Y') }}</div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @if($post->category)
                        <span class="badge blue-badge">{{ $post->category }}</span>
                    @endif
                    @foreach(($post->tags ?? []) as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag]) }}" class="blog-inline-tag">#{{ $tag }}</a>
                    @endforeach
                </div>
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

<div class="mt-4 pagination-shell">{{ $posts->withQueryString()->links() }}</div>
@endsection
