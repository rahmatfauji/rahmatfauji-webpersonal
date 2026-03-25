@extends('layouts.app')

@section('title', __('Admin - Portfolio'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Portfolio Management') }}</h1>
    <a href="{{ route('admin.portfolio-items.create') }}" class="btn btn-primary">{{ __('Add Portfolio Item') }}</a>
</div>

@error('bulk_delete')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="clean-card p-3" data-fade-in>
    <form id="portfolioBulkDeleteForm" action="{{ route('admin.portfolio-items.bulk-destroy') }}" method="POST" class="d-flex justify-content-end mb-3" onsubmit="return confirm('{{ __('Delete selected portfolio items?') }}')">
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
                        <input type="checkbox" class="form-check-input" data-select-all aria-label="{{ __('Select all portfolio items') }}">
                    </th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Order') }}</th>
                    <th>{{ __('Active') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td class="text-center">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="ids[]"
                            value="{{ $item->id }}"
                            form="portfolioBulkDeleteForm"
                            data-item-checkbox
                            aria-label="{{ __('Select portfolio item: :title', ['title' => $item->title]) }}"
                        >
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ number_format($item->view_count) }}</td>
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
                <tr><td colspan="7">{{ __('No portfolio items yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 pagination-shell">{{ $items->links() }}</div>
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
