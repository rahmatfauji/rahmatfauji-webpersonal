@extends('layouts.app')

@section('title', __('Add Slide'))

@section('content')
<div class="clean-card p-4">
    <h1 class="section-title h3 mb-4">{{ __('Add Slide') }}</h1>
    @include('admin.slides.form', ['slide' => null, 'action' => route('admin.slides.store'), 'method' => 'POST'])
</div>
@endsection
