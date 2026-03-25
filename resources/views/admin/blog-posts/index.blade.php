@extends('layouts.app')

@section('title', __('Admin - Blog'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Blog Management') }}</h1>
    <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">{{ __('Add Article') }}</a>
</div>

@error('bulk_delete')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="clean-card p-3" data-fade-in>
    <form id="blogBulkDeleteForm" action="{{ route('admin.blog-posts.bulk-destroy') }}" method="POST" class="d-flex justify-content-end mb-3" onsubmit="return confirm('{{ __('Delete selected articles?') }}')">
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
                        <input type="checkbox" class="form-check-input" data-select-all aria-label="{{ __('Select all articles') }}">
                    </th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Slug') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Published') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td class="text-center">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="ids[]"
                            value="{{ $post->id }}"
                            form="blogBulkDeleteForm"
                            data-item-checkbox
                            aria-label="{{ __('Select article: :title', ['title' => $post->title]) }}"
                        >
                    </td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->slug }}</td>
                    <td>{{ number_format($post->view_count) }}</td>
                    <td>{{ $post->is_published ? __('Yes') : __('No') }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex justify-content-end align-items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="m-0" onsubmit="return confirm('{{ __('Delete this article?') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">{{ __('No articles yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 pagination-shell">{{ $posts->links() }}</div>
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
