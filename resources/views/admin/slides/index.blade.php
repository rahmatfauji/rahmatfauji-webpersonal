@extends('layouts.app')

@section('title', __('Admin - Slide Manager'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Slideshow Manager') }}</h1>
    <a href="{{ route('admin.slides.create') }}" class="btn btn-primary">{{ __('Add Slide') }}</a>
</div>

<div class="clean-card p-3">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Order') }}</th>
                    <th>{{ __('Active') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($slides as $slide)
                <tr>
                    <td>{{ $slide->title }}</td>
                    <td><a href="{{ $slide->image_url }}" target="_blank" rel="noreferrer">{{ __('View') }}</a></td>
                    <td>{{ $slide->display_order }}</td>
                    <td>{{ $slide->is_active ? __('Yes') : __('No') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this slide?') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">{{ __('No slides yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 pagination-shell">{{ $slides->links() }}</div>
@endsection
