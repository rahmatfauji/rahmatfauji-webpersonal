<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="col-md-6">
        <label class="form-label">{{ __('Title') }}</label>
        <input type="text" name="title" value="{{ old('title', optional($item)->title) }}" class="form-control @error('title') is-invalid @enderror" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Category') }}</label>
        <input type="text" name="category" value="{{ old('category', optional($item)->category) }}" class="form-control @error('category') is-invalid @enderror" required>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Summary') }}</label>
        <textarea name="summary" rows="2" class="form-control @error('summary') is-invalid @enderror" required>{{ old('summary', optional($item)->summary) }}</textarea>
        @error('summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Description') }}</label>
        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', optional($item)->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Project URL') }}</label>
        <input type="url" name="project_url" value="{{ old('project_url', optional($item)->project_url) }}" class="form-control @error('project_url') is-invalid @enderror">
        @error('project_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Image URL') }}</label>
        <input type="url" name="image_url" value="{{ old('image_url', optional($item)->image_url) }}" class="form-control @error('image_url') is-invalid @enderror">
        @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('Order') }}</label>
        <input type="number" min="0" name="display_order" value="{{ old('display_order', optional($item)->display_order ?? 0) }}" class="form-control @error('display_order') is-invalid @enderror">
        @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', optional($item)->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('Active') }}</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.portfolio-items.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </div>
</form>
