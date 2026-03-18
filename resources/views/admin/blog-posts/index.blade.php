@extends('layouts.app')

@section('title', __('Admin - Blog'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Blog Management') }}</h1>
    <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">{{ __('Add Article') }}</a>
</div>

<div class="clean-card p-3">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Slug') }}</th>
                    <th>{{ __('Published') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->slug }}</td>
                    <td>{{ $post->is_published ? __('Yes') : __('No') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this article?') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">{{ __('No articles yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 pagination-shell">{{ $posts->links() }}</div>
@endsection
