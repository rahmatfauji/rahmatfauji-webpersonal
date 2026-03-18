@extends('layouts.app')

@section('title', __('Edit Profile'))

@section('content')
<div class="clean-card p-4">
    <h1 class="section-title h3 mb-4">{{ __('Edit Profile') }}</h1>
    @include('admin.profiles.form', ['profile' => $profile, 'action' => route('admin.profiles.update', $profile), 'method' => 'PUT'])
</div>
@endsection
