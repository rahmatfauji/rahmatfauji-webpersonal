@extends('layouts.app')

@section('title', __('Add Article'))

@section('content')
<div class="clean-card p-4">
    <h1 class="section-title h3 mb-4">{{ __('Add Blog Article') }}</h1>
    @include('admin.blog-posts.form', ['post' => null, 'action' => route('admin.blog-posts.store'), 'method' => 'POST'])
</div>
@endsection
