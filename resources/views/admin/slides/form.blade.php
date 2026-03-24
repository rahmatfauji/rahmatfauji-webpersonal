<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    @php($uploadToken = old('upload_token', (string) \Illuminate\Support\Str::uuid()))
    <input type="hidden" id="upload-token" name="upload_token" value="{{ $uploadToken }}">

    <div class="col-md-6">
        <label class="form-label">{{ __('Slide Title') }}</label>
        <input type="text" name="title" value="{{ old('title', optional($slide)->title) }}" class="form-control @error('title') is-invalid @enderror" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Slide Image') }}</label>
        <div class="d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="file" id="slide-image-input" accept="image/*" class="form-control @error('image_url') is-invalid @enderror" {{ optional($slide)->id ? '' : 'required' }}>
                @error('image_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <input type="hidden" id="slide-image-value" name="image_url" value="{{ old('image_url', optional($slide)->image_url) }}">
                <div class="form-text mt-2">{{ __('Supported formats: JPG, PNG, WebP (max 5MB)') }}</div>
            </div>
            @if(optional($slide)->image_url)
            <img id="slide-image-preview" src="{{ optional($slide)->image_url }}" alt="Slide" style="width: 120px; height: 80px; object-fit: cover; border-radius: 4px;">
            @else
            <div id="slide-image-placeholder" style="width: 120px; height: 80px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;" class="text-muted small">{{ __('No image') }}</div>
            @endif
        </div>
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
        <input type="text" name="button_url" value="{{ old('button_url', optional($slide)->button_url) }}" class="form-control @error('button_url') is-invalid @enderror" placeholder="https://example.com or /portfolio">
        <div class="form-text">{{ __('Use a full URL or an internal path starting with /.') }}</div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('input[name="_token"]').value;
        const uploadToken = document.getElementById('upload-token').value;
        const slideImageInput = document.getElementById('slide-image-input');
        const slideImageValue = document.getElementById('slide-image-value');
        const slideImagePreview = document.getElementById('slide-image-preview');
        const slideImagePlaceholder = document.getElementById('slide-image-placeholder');

        // Handle slide image upload
        slideImageInput.addEventListener('change', () => {
            const file = slideImageInput.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('type', 'slide');
            formData.append('temp_token', uploadToken);

            const loadingText = document.createElement('div');
            loadingText.className = 'text-muted small';
            loadingText.textContent = '{{ __("Uploading...") }}';
            loadingText.style.cssText = 'width: 120px; height: 80px; display: flex; align-items: center; justify-content: center;';
            
            if (slideImagePlaceholder) slideImagePlaceholder.replaceWith(loadingText);
            if (slideImagePreview) slideImagePreview.parentNode.removeChild(slideImagePreview);

            fetch('{{ route("admin.upload-image") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    slideImageValue.value = data.url;
                    const img = document.createElement('img');
                    img.id = 'slide-image-preview';
                    img.src = data.url;
                    img.alt = 'Slide';
                    img.style.cssText = 'width: 120px; height: 80px; object-fit: cover; border-radius: 4px;';
                    loadingText.replaceWith(img);
                } else {
                    alert('{{ __("Image upload failed.") }}');
                    loadingText.remove();
                    if (!slideImagePlaceholder) {
                        const placeholder = document.createElement('div');
                        placeholder.id = 'slide-image-placeholder';
                        placeholder.style.cssText = 'width: 120px; height: 80px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;';
                        placeholder.className = 'text-muted small';
                        placeholder.textContent = '{{ __("No image") }}';
                        loadingText.parentNode.appendChild(placeholder);
                    }
                }
            })
            .catch(() => alert('{{ __("Image upload failed.") }}'));
        });
    });
</script>
