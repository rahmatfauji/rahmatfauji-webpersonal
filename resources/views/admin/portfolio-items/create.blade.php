@extends('layouts.app')

@section('title', __('Add Portfolio Item'))

@section('content')
<div class="clean-card p-4">
    <h1 class="section-title h3 mb-4">{{ __('Add Portfolio Item') }}</h1>
    @include('admin.portfolio-items.form', ['item' => null, 'action' => route('admin.portfolio-items.store'), 'method' => 'POST'])
</div>
@endsection
