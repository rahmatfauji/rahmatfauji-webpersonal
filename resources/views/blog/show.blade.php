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

        @if($relatedPosts->isNotEmpty())
            <section class="clean-card p-4 mt-4 fade-in-up">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <span class="section-kicker">{{ __('Keep Reading') }}</span>
                        <h3 class="section-title mb-0">{{ __('Related Articles') }}</h3>
                    </div>
                </div>
                <div class="row g-3">
                    @foreach($relatedPosts as $relatedPost)
                        <div class="col-md-4">
                            <article class="related-post-card h-100">
                                <div class="small text-muted mb-2">{{ optional($relatedPost->published_at)->format('d M Y') }}</div>
                                <h5>{{ $relatedPost->title }}</h5>
                                <p class="mb-2">{{ $relatedPost->excerpt }}</p>
                                <a href="{{ route('blog.show', $relatedPost) }}" class="text-decoration-none">{{ __('Read article') }}</a>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
    <div class="col-lg-4">
        <aside class="clean-card p-4 fade-in-up blog-toc-shell" data-toc-shell hidden>
            <div class="small text-muted text-uppercase fw-semibold mb-2">{{ __('On This Page') }}</div>
            <div class="blog-toc-list" data-toc-list></div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script>
    document.querySelectorAll('.rich-content pre code').forEach((el) => {
        hljs.highlightElement(el);
    });

    document.addEventListener('DOMContentLoaded', () => {
        const articleContent = document.querySelector('[data-article-content]');
        const tocShell = document.querySelector('[data-toc-shell]');
        const tocList = document.querySelector('[data-toc-list]');

        if (!articleContent || !tocShell || !tocList) {
            return;
        }

        const headings = [...articleContent.querySelectorAll('h2, h3')];
        if (headings.length === 0) {
            return;
        }

        headings.forEach((heading, index) => {
            if (!heading.id) {
                heading.id = `article-section-${index + 1}`;
            }

            const link = document.createElement('a');
            link.href = `#${heading.id}`;
            link.textContent = heading.textContent || `Section ${index + 1}`;
            link.className = `blog-toc-link ${heading.tagName.toLowerCase() === 'h3' ? 'is-sub' : ''}`;
            tocList.appendChild(link);
        });

        tocShell.hidden = false;
    });
</script>
@endpush
