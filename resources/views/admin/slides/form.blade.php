<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="col-md-6">
        <label class="form-label">{{ __('Slide Title') }}</label>
        <input type="text" name="title" value="{{ old('title', optional($slide)->title) }}" class="form-control @error('title') is-invalid @enderror" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Image URL') }}</label>
        <input type="url" name="image_url" value="{{ old('image_url', optional($slide)->image_url) }}" class="form-control @error('image_url') is-invalid @enderror" required>
        @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Subtitle') }}</label>
        <textarea name="subtitle" rows="2" class="form-control @error('subtitle') is-invalid @enderror">{{ old('subtitle', optional($slide)->subtitle) }}</textarea>
        @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">{{ __('Button Text') }}</label>
        <input type="text" name="button_text" value="{{ old('button_text', optional($slide)->button_text) }}" class="form-control @error('button_text') is-invalid @enderror">
        @error('button_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-5">
        <label class="form-label">{{ __('Button URL') }}</label>
        <input type="url" name="button_url" value="{{ old('button_url', optional($slide)->button_url) }}" class="form-control @error('button_url') is-invalid @enderror">
        @error('button_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('Order') }}</label>
        <input type="number" min="0" name="display_order" value="{{ old('display_order', optional($slide)->display_order ?? 0) }}" class="form-control @error('display_order') is-invalid @enderror">
        @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', optional($slide)->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('Enable slide') }}</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.slides.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </div>
</form>
