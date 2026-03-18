@extends('layouts.app')

@section('title', __('Add Profile'))

@section('content')
<div class="clean-card p-4">
    <h1 class="section-title h3 mb-4">{{ __('Add Profile') }}</h1>
    @include('admin.profiles.form', ['profile' => null, 'action' => route('admin.profiles.store'), 'method' => 'POST'])
</div>
@endsection
