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
        <input type="text" name="project_url" value="{{ old('project_url', optional($item)->project_url) }}" class="form-control @error('project_url') is-invalid @enderror" placeholder="https://example.com or /portfolio/project-slug">
        <div class="form-text">{{ __('Use a full URL or an internal path starting with /.') }}</div>
        @error('project_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Image') }}</label>
        <div class="d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="file" id="portfolio-image-input" accept="image/*" class="form-control @error('image_url') is-invalid @enderror">
                @error('image_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <input type="hidden" id="portfolio-image-value" name="image_url" value="{{ old('image_url', optional($item)->image_url) }}">
                <div class="form-text mt-2">{{ __('Recommended: 1200×675px landscape (16:9) · JPG, PNG, WebP · Max 5MB') }}</div>
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

<!-- Crop Preview Modal -->
<div id="crop-preview-modal" class="crop-preview-modal">
    <div class="crop-preview-container">
        <div class="crop-preview-header">
            <h5>{{ __('Preview Portfolio Image') }}</h5>
            <button type="button" class="crop-preview-close" id="crop-preview-close">&times;</button>
        </div>
        <div class="crop-preview-canvas">
            <img id="crop-preview-image" src="" alt="Crop Preview" class="crop-preview-image">
            <div class="crop-preview-overlay"></div>
        </div>
        <div class="crop-preview-footer">
            <button type="button" class="crop-preview-btn crop-preview-btn-cancel" id="crop-preview-cancel">{{ __('Cancel') }}</button>
            <button type="button" class="crop-preview-btn crop-preview-btn-apply" id="crop-preview-apply">{{ __('Use This Image') }}</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('input[name="_token"]').value;
        const uploadToken = document.getElementById('upload-token').value;
        const portfolioImageInput = document.getElementById('portfolio-image-input');
        const portfolioImageValue = document.getElementById('portfolio-image-value');

        const cropModal = document.getElementById('crop-preview-modal');
        const cropImage = document.getElementById('crop-preview-image');
        const cropCloseBtn = document.getElementById('crop-preview-close');
        const cropCancelBtn = document.getElementById('crop-preview-cancel');
        const cropApplyBtn = document.getElementById('crop-preview-apply');
        let pendingImageUrl = null;

        const showCropModal = (imageUrl) => {
            cropImage.src = imageUrl;
            cropModal.classList.add('is-active');
        };

        const hideCropModal = () => {
            cropModal.classList.remove('is-active');
            pendingImageUrl = null;
            cropImage.src = '';
        };

        const applyPortfolioImage = (imageUrl) => {
            portfolioImageValue.value = imageUrl;
            const img = document.createElement('img');
            img.id = 'portfolio-image-preview';
            img.src = imageUrl;
            img.alt = 'Portfolio';
            img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-radius: 4px; cursor: pointer;';
            img.addEventListener('click', () => showCropModal(imageUrl));
            const placeholder = document.getElementById('portfolio-image-placeholder');
            const existing = document.getElementById('portfolio-image-preview');
            if (existing) existing.replaceWith(img);
            else if (placeholder) placeholder.replaceWith(img);
            hideCropModal();
        };

        cropCloseBtn.addEventListener('click', hideCropModal);
        cropCancelBtn.addEventListener('click', hideCropModal);
        cropApplyBtn.addEventListener('click', () => { if (pendingImageUrl) applyPortfolioImage(pendingImageUrl); });
        cropModal.addEventListener('click', (e) => { if (e.target === cropModal) hideCropModal(); });

        portfolioImageInput.addEventListener('change', () => {
        const file = portfolioImageInput.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('type', 'portfolio');
        formData.append('temp_token', uploadToken);

            const loadingText = document.createElement('div');
            loadingText.className = 'text-muted small';
            loadingText.textContent = '{{ __("Uploading...") }}';
            loadingText.style.cssText = 'width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;';
            const placeholder = document.getElementById('portfolio-image-placeholder');
            const existing = document.getElementById('portfolio-image-preview');
            if (placeholder) placeholder.replaceWith(loadingText);
            else if (existing) existing.replaceWith(loadingText);

            fetch('{{ route("admin.upload-image") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || '{{ __("Image upload failed.") }}');
                return data;
            })
            .then(data => {
                if (data.success) {
                    pendingImageUrl = data.url;
                    loadingText.replaceWith(document.createElement('span'));
                    showCropModal(data.url);
                } else {
                    throw new Error('{{ __("Image upload failed.") }}');
                }
            })
            .catch((error) => {
                const fallback = document.createElement('div');
                fallback.id = 'portfolio-image-placeholder';
                fallback.style.cssText = 'width: 100px; height: 100px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;';
                fallback.className = 'text-muted small';
                fallback.textContent = '{{ __("No image") }}';
                loadingText.replaceWith(fallback);
                alert(error.message || '{{ __("Image upload failed.") }}');
            });
        });

        const existingPreview = document.getElementById('portfolio-image-preview');
        if (existingPreview) {
            existingPreview.style.cursor = 'pointer';
            existingPreview.addEventListener('click', () => showCropModal(existingPreview.src));
        }
    });
</script>
