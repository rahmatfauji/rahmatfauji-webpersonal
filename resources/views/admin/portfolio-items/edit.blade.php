@extends('layouts.app')

@section('title', __('Edit Portfolio'))

@section('content')
<div class="clean-card p-4">
    <h1 class="section-title h3 mb-4">{{ __('Edit Portfolio Item') }}</h1>
    @include('admin.portfolio-items.form', ['item' => $item, 'action' => route('admin.portfolio-items.update', $item), 'method' => 'PUT'])
</div>
@endsection
