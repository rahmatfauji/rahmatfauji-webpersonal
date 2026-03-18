@extends('layouts.app')

@section('title', __('Admin - Portfolio'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Portfolio Management') }}</h1>
    <a href="{{ route('admin.portfolio-items.create') }}" class="btn btn-primary">{{ __('Add Portfolio Item') }}</a>
</div>

<div class="clean-card p-3">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Order') }}</th>
                    <th>{{ __('Active') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->display_order }}</td>
                    <td>{{ $item->is_active ? __('Yes') : __('No') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.portfolio-items.edit', $item) }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.portfolio-items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this portfolio item?') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">{{ __('No portfolio items yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 pagination-shell">{{ $items->links() }}</div>
@endsection
