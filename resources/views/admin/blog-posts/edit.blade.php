@extends('layouts.app')

@section('title', __('Edit Article'))

@section('content')
<div class="clean-card p-4">
    <h1 class="section-title h3 mb-4">{{ __('Edit Blog Article') }}</h1>
    @include('admin.blog-posts.form', ['post' => $post, 'action' => route('admin.blog-posts.update', $post), 'method' => 'PUT'])
</div>
@endsection
