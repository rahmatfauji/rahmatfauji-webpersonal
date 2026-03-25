@extends('layouts.app')

@section('title', __('Admin - Slide Manager'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Slideshow Manager') }}</h1>
    <a href="{{ route('admin.slides.create') }}" class="btn btn-primary">{{ __('Add Slide') }}</a>
</div>

@error('bulk_delete')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="clean-card p-3">
    <form id="slidesBulkDeleteForm" action="{{ route('admin.slides.bulk-destroy') }}" method="POST" class="d-flex justify-content-end mb-3" onsubmit="return confirm('{{ __('Delete selected slides?') }}')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger" data-bulk-delete-btn disabled>
            {{ __('Delete Selected') }}
        </button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 44px;">
                        <input type="checkbox" class="form-check-input" data-select-all aria-label="{{ __('Select all slides') }}">
                    </th>
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
                    <td class="text-center">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="ids[]"
                            value="{{ $slide->id }}"
                            form="slidesBulkDeleteForm"
                            data-item-checkbox
                            aria-label="{{ __('Select slide: :title', ['title' => $slide->title]) }}"
                        >
                    </td>
                    <td>{{ $slide->title }}</td>
                    <td><a href="{{ $slide->image_url }}" target="_blank" rel="noreferrer">{{ __('View') }}</a></td>
                    <td>{{ $slide->display_order }}</td>
                    <td>{{ $slide->is_active ? __('Yes') : __('No') }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex justify-content-end align-items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" class="m-0" onsubmit="return confirm('{{ __('Delete this slide?') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">{{ __('No slides yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 pagination-shell">{{ $slides->links() }}</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bulkDeleteButton = document.querySelector('[data-bulk-delete-btn]');
        const selectAllCheckbox = document.querySelector('[data-select-all]');
        const itemCheckboxes = Array.from(document.querySelectorAll('[data-item-checkbox]'));

        if (!bulkDeleteButton || !selectAllCheckbox || itemCheckboxes.length === 0) {
            return;
        }

        const syncState = () => {
            const checkedCount = itemCheckboxes.filter((checkbox) => checkbox.checked).length;
            bulkDeleteButton.disabled = checkedCount === 0;
            selectAllCheckbox.checked = checkedCount === itemCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < itemCheckboxes.length;
        };

        selectAllCheckbox.addEventListener('change', () => {
            itemCheckboxes.forEach((checkbox) => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            syncState();
        });

        itemCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', syncState);
        });

        syncState();
    });
</script>
@endpush
