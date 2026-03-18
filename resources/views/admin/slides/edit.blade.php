@extends('layouts.app')

@section('title', __('Edit Slide'))

@section('content')
<div class="clean-card p-4">
    <h1 class="section-title h3 mb-4">{{ __('Edit Slide') }}</h1>
    @include('admin.slides.form', ['slide' => $slide, 'action' => route('admin.slides.update', $slide), 'method' => 'PUT'])
</div>
@endsection
