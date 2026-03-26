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
                <div class="form-text mt-2">{{ __('Recommended: 1920×1080px landscape (16:9) · JPG, PNG, WebP · Max 5MB') }}</div>
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

<!-- Crop Preview Modal -->
<div id="crop-preview-modal" class="crop-preview-modal">
    <div class="crop-preview-container">
        <div class="crop-preview-header">
            <h5>{{ __('Preview Slide Image') }}</h5>
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
        const slideImageInput = document.getElementById('slide-image-input');
        const slideImageValue = document.getElementById('slide-image-value');
        const formElement = slideImageInput.closest('form');

        const cropModal = document.getElementById('crop-preview-modal');
        const cropImage = document.getElementById('crop-preview-image');
        const cropCloseBtn = document.getElementById('crop-preview-close');
        const cropCancelBtn = document.getElementById('crop-preview-cancel');
        const cropApplyBtn = document.getElementById('crop-preview-apply');
        let pendingImageUrl = null;
        let imageUploadState = 'idle';

        const showCropModal = (imageUrl) => {
            cropImage.src = imageUrl;
            cropModal.classList.add('is-active');
        };

        const hideCropModal = () => {
            cropModal.classList.remove('is-active');
            pendingImageUrl = null;
            cropImage.src = '';
        };

        const applySlideImage = (imageUrl) => {
            slideImageValue.value = imageUrl;
            const img = document.createElement('img');
            img.id = 'slide-image-preview';
            img.src = imageUrl;
            img.alt = 'Slide';
            img.style.cssText = 'width: 120px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer;';
            img.addEventListener('click', () => showCropModal(imageUrl));
            const placeholder = document.getElementById('slide-image-placeholder');
            const existing = document.getElementById('slide-image-preview');
            if (existing) existing.replaceWith(img);
            else if (placeholder) placeholder.replaceWith(img);
            imageUploadState = 'success';
            hideCropModal();
        };

        cropCloseBtn.addEventListener('click', hideCropModal);
        cropCancelBtn.addEventListener('click', hideCropModal);
        cropApplyBtn.addEventListener('click', () => { if (pendingImageUrl) applySlideImage(pendingImageUrl); });
        cropModal.addEventListener('click', (e) => { if (e.target === cropModal) hideCropModal(); });

        const setUploadFeedback = (message) => {
            let feedback = document.getElementById('slide-image-upload-feedback');

            if (!feedback) {
                feedback = document.createElement('div');
                feedback.id = 'slide-image-upload-feedback';
                feedback.className = 'text-danger small mt-1';
                slideImageInput.insertAdjacentElement('afterend', feedback);
            }

            feedback.textContent = message || '';
            feedback.style.display = message ? 'block' : 'none';
        };

        // Handle slide image upload
        slideImageInput.addEventListener('change', () => {
            const file = slideImageInput.files[0];
            if (!file) return;

            imageUploadState = 'uploading';
            setUploadFeedback('');

            const formData = new FormData();
            formData.append('image', file);
            formData.append('type', 'slide');
            formData.append('temp_token', uploadToken);

            const loadingText = document.createElement('div');
            loadingText.className = 'text-muted small';
            loadingText.textContent = '{{ __("Uploading...") }}';
            loadingText.style.cssText = 'width: 120px; height: 80px; display: flex; align-items: center; justify-content: center;';

            const currentPreview = document.getElementById('slide-image-preview');
            const currentPlaceholder = document.getElementById('slide-image-placeholder');

            if (currentPlaceholder) {
                currentPlaceholder.replaceWith(loadingText);
            } else if (currentPreview) {
                currentPreview.replaceWith(loadingText);
            }

            fetch('{{ route("admin.upload-image") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));

                if (!res.ok) {
                    const message = data.message || '{{ __("Image upload failed.") }}';
                    throw new Error(message);
                }

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
                imageUploadState = 'failed';
                setUploadFeedback(error.message || '{{ __("Image upload failed.") }}');

                const fallback = document.createElement('div');
                fallback.id = 'slide-image-placeholder';
                fallback.style.cssText = 'width: 120px; height: 80px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;';
                fallback.className = 'text-muted small';
                fallback.textContent = '{{ __("No image") }}';
                loadingText.replaceWith(fallback);
            });
        });

        formElement.addEventListener('submit', (event) => {
            if (slideImageInput.files.length > 0 && imageUploadState !== 'success') {
                event.preventDefault();

                if (imageUploadState === 'uploading') {
                    setUploadFeedback('{{ __("Please wait until image upload is complete.") }}');
                    return;
                }

                setUploadFeedback('{{ __("Image upload failed. Please choose a valid image and try again.") }}');
            }
        });

            const existingPreview = document.getElementById('slide-image-preview');
            if (existingPreview) {
                existingPreview.style.cursor = 'pointer';
                existingPreview.addEventListener('click', () => showCropModal(existingPreview.src));
            }
    });
</script>
