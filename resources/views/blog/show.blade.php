@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->excerpt ?: 'Article by Rahmat Fauji on data analytics, Power BI, and Power Apps implementation.')
@section('meta_type', 'article')
@section('meta_canonical', route('blog.show', $post))
@if($post->featured_image)
    @section('meta_image', $post->featured_image)
@endif

@push('seo')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": @json($post->title),
    "description": @json($post->excerpt),
    "datePublished": "{{ optional($post->published_at)->toAtomString() }}",
    "dateModified": "{{ optional($post->updated_at)->toAtomString() }}",
    "author": {
        "@type": "Person",
        "name": "Rahmat Fauji"
    },
    "mainEntityOfPage": "{{ route('blog.show', $post) }}",
    "image": "{{ $post->featured_image ?: asset('favicon.ico') }}"
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css">
@endpush

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-lg-8">
        <article class="clean-card p-4 p-lg-5 fade-in-up">
            <div class="small text-muted mb-2">{{ optional($post->published_at)->format('d M Y') }} · {{ number_format($post->view_count) }} {{ __('views') }}</div>
            <div class="d-flex flex-wrap gap-2 mb-3">
                @if($post->category)
                    <span class="badge blue-badge">{{ $post->category }}</span>
                @endif
                @foreach(($post->tags ?? []) as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag]) }}" class="blog-inline-tag">#{{ $tag }}</a>
                @endforeach
            </div>
            <h1 class="section-title mb-3">{{ $post->title }}</h1>
            @if($post->featured_image)
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="img-fluid rounded mb-4" style="max-height: 380px; width: 100%; object-fit: cover;">
            @endif
            <p class="lead">{{ $post->excerpt }}</p>
            <hr>
            <div class="rich-content mb-0" data-article-content>{!! $post->content !!}</div>
        </article>
    </div>
    <div class="col-lg-4">
        @if($relatedPosts->isNotEmpty())
            <aside class="clean-card p-4 fade-in-up">
                <div class="mb-3">
                    <span class="section-kicker">{{ __('Keep Reading') }}</span>
                    <h3 class="section-title mb-0">{{ __('Related Articles') }}</h3>
                </div>
                <div class="d-flex flex-column gap-3">
                    @foreach($relatedPosts as $relatedPost)
                        <article class="related-post-card">
                            <div class="small text-muted mb-2">{{ optional($relatedPost->published_at)->format('d M Y') }}</div>
                            <h5>{{ $relatedPost->title }}</h5>
                            <p class="mb-2">{{ $relatedPost->excerpt }}</p>
                            <a href="{{ route('blog.show', $relatedPost) }}" class="text-decoration-none">{{ __('Read article') }}</a>
                        </article>
                    @endforeach
                </div>
            </aside>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script>
    document.querySelectorAll('.rich-content pre code').forEach((el) => {
        hljs.highlightElement(el);
    });
</script>
@endpush
