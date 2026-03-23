<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    @php($uploadToken = old('upload_token', (string) \Illuminate\Support\Str::uuid()))
    <input type="hidden" id="upload-token" name="upload_token" value="{{ $uploadToken }}">

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
        <label class="form-label">{{ __('Image') }}</label>
        <div class="d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="file" id="portfolio-image-input" accept="image/*" class="form-control @error('image_url') is-invalid @enderror">
                @error('image_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <input type="hidden" id="portfolio-image-value" name="image_url" value="{{ old('image_url', optional($item)->image_url) }}">
                <div class="form-text mt-2">{{ __('Supported formats: JPG, PNG, WebP (max 5MB)') }}</div>
            </div>
            @if(optional($item)->image_url)
            <img id="portfolio-image-preview" src="{{ optional($item)->image_url }}" alt="Portfolio" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
            @else
            <div id="portfolio-image-placeholder" style="width: 100px; height: 100px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;" class="text-muted small">{{ __('No image') }}</div>
            @endif
        </div>
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

<script>
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const uploadToken = document.getElementById('upload-token').value;
    const portfolioImageInput = document.getElementById('portfolio-image-input');
    const portfolioImageValue = document.getElementById('portfolio-image-value');
    const portfolioImagePreview = document.getElementById('portfolio-image-preview');
    const portfolioImagePlaceholder = document.getElementById('portfolio-image-placeholder');

    // Handle portfolio image upload
    portfolioImageInput.addEventListener('change', () => {
        const file = portfolioImageInput.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('type', 'portfolio');
        formData.append('temp_token', uploadToken);

        const loadingText = document.createElement('div');
        loadingText.className = 'text-muted small';
        loadingText.textContent = '{{ __('Uploading...') }}';
        if (portfolioImagePlaceholder) portfolioImagePlaceholder.replaceWith(loadingText);
        if (portfolioImagePreview) portfolioImagePreview.parentNode.removeChild(portfolioImagePreview);

        fetch('{{ route('admin.upload-image') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                portfolioImageValue.value = data.url;
                const img = document.createElement('img');
                img.id = 'portfolio-image-preview';
                img.src = data.url;
                img.alt = 'Portfolio';
                img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-radius: 4px;';
                loadingText.replaceWith(img);
            } else {
                alert('{{ __('Image upload failed.') }}');
                loadingText.remove();
                if (!portfolioImagePlaceholder) {
                    const placeholder = document.createElement('div');
                    placeholder.id = 'portfolio-image-placeholder';
                    placeholder.style.cssText = 'width: 100px; height: 100px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;';
                    placeholder.className = 'text-muted small';
                    placeholder.textContent = '{{ __('No image') }}';
                    loadingText.parentNode.appendChild(placeholder);
                }
            }
        })
        .catch(() => alert('{{ __('Image upload failed.') }}'));
    });
</script>
