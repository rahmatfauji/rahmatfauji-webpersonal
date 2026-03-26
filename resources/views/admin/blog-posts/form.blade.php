<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <input type="hidden" id="upload-token" name="upload_token" value="{{ old('upload_token', (string) \Illuminate\Support\Str::uuid()) }}">

    <div class="col-md-8">
        <label class="form-label">{{ __('Title') }}</label>
        <input type="text" id="blog-title" name="title" value="{{ old('title', optional($post)->title) }}" class="form-control @error('title') is-invalid @enderror" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">{{ __('Slug') }}</label>
        <input type="text" id="blog-slug" name="slug" value="{{ old('slug', optional($post)->slug) }}" class="form-control @error('slug') is-invalid @enderror" placeholder="{{ __('optional-auto') }}" pattern="[a-z0-9-]+">
        <div class="form-text">{{ __('Use lowercase letters, numbers, and hyphens only.') }}</div>
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Category') }}</label>
        <input type="text" name="category" value="{{ old('category', optional($post)->category) }}" class="form-control @error('category') is-invalid @enderror" placeholder="{{ __('Data Analytics') }}">
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Tags') }}</label>
        <input type="text" name="tags" value="{{ old('tags', is_array(optional($post)->tags) ? implode(', ', optional($post)->tags) : '') }}" class="form-control @error('tags') is-invalid @enderror" placeholder="{{ __('Power BI, Dashboard, SQL') }}">
        <div class="form-text">{{ __('Separate tags with commas.') }}</div>
        @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Excerpt') }}</label>
        <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', optional($post)->excerpt) }}</textarea>
        @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Content') }}</label>
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet">
        <style>
            #content-editor .ql-editor img {
                max-width: 100%;
                height: auto;
                cursor: pointer;
            }

            #content-editor .ql-container {
                position: relative;
            }

            .quill-image-resize-overlay {
                position: absolute;
                border: 2px solid #2563eb;
                border-radius: 0.5rem;
                box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.12);
                pointer-events: none;
                display: none;
                z-index: 20;
            }

            .quill-image-resize-handle {
                position: absolute;
                right: -0.5rem;
                bottom: -0.5rem;
                width: 1rem;
                height: 1rem;
                border: 2px solid #ffffff;
                border-radius: 999px;
                background: #2563eb;
                box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
                cursor: nwse-resize;
                pointer-events: auto;
            }

            .quill-image-resize-toolbar {
                position: absolute;
                left: 50%;
                bottom: calc(100% + 0.55rem);
                transform: translateX(-50%);
                display: flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.35rem;
                border-radius: 999px;
                background: rgba(15, 23, 42, 0.92);
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.22);
                pointer-events: auto;
                white-space: nowrap;
            }

            .quill-image-resize-button {
                border: 0;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.14);
                color: #ffffff;
                font-size: 0.72rem;
                font-weight: 700;
                line-height: 1;
                padding: 0.4rem 0.55rem;
            }

            .quill-image-resize-button:hover,
            .quill-image-resize-button:focus {
                background: rgba(96, 165, 250, 0.9);
                color: #ffffff;
            }

            .quill-image-resize-hint {
                margin-top: 0.5rem;
                font-size: 0.82rem;
                color: #64748b;
            }
        </style>
        <div id="content-editor" style="height: 400px;" class="@error('content') border-danger @enderror"></div>
        <textarea id="content-value" name="content" style="display:none;">{{ old('content', optional($post)->content) }}</textarea>
        <div class="quill-image-resize-hint">{{ __('Click an image in the editor, then drag the blue handle to resize it.') }}</div>
        @error('content')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Featured Image') }}</label>
        <div class="d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="file" id="featured-image-input" accept="image/*" class="form-control @error('featured_image') is-invalid @enderror">
                @error('featured_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <input type="hidden" id="featured-image-value" name="featured_image" value="{{ old('featured_image', optional($post)->featured_image) }}">
                <div class="form-text mt-2">{{ __('Recommended: 1200×675px landscape (16:9 aspect ratio) · JPG, PNG, WebP · Max 5MB') }}</div>
            </div>
            @if(optional($post)->featured_image)
            <img id="featured-image-preview" src="{{ optional($post)->featured_image }}" alt="Featured" style="width: 100px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer;">
            @else
            <div id="featured-image-placeholder" style="width: 100px; height: 80px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;" class="text-muted small">{{ __('No image') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('Publish Date') }}</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional(optional($post)->published_at)->format('Y-m-d\\TH:i')) }}" class="form-control @error('published_at') is-invalid @enderror">
        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', optional($post)->is_published ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('Publish') }}</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </div>
</form>

<!-- Crop Preview Modal for Featured Image -->
<div id="crop-preview-modal" class="crop-preview-modal">
    <div class="crop-preview-container">
        <div class="crop-preview-header">
            <h5>{{ __('Preview Featured Image') }}</h5>
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

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
<script>
    const uploadImageUrl = @json(route('admin.upload-image'));
    const messageContentEmpty = @json(__('Content cannot be empty.'));
    const messageImageUploadFailed = @json(__('Image upload failed.'));
    const messageUploading = @json(__('Uploading...'));
    const messageNoImage = @json(__('No image'));
    const imageResizePresets = [25, 50, 75, 100];

    // Wait for DOM to be fully ready
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('blog-title');
        const slugInput = document.getElementById('blog-slug');
        const csrfToken = document.querySelector('input[name="_token"]').value;
        const uploadToken = document.getElementById('upload-token').value;
        const contentValue = document.getElementById('content-value');
        const featuredImageInput = document.getElementById('featured-image-input');
        const featuredImageValue = document.getElementById('featured-image-value');
        const featuredImagePreview = document.getElementById('featured-image-preview');
        const featuredImagePlaceholder = document.getElementById('featured-image-placeholder');

        const sanitizeSlug = (value) => value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

        titleInput.addEventListener('input', () => {
            if (!slugInput.dataset.userEdited || slugInput.value.trim() === '') {
                slugInput.value = sanitizeSlug(titleInput.value);
            }
        });

        slugInput.addEventListener('input', () => {
            slugInput.dataset.userEdited = '1';
            slugInput.value = sanitizeSlug(slugInput.value);
        });

        // Initialize Quill editor
        const quill = new Quill('#content-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        const imageResizeOverlay = document.createElement('div');
        imageResizeOverlay.className = 'quill-image-resize-overlay';

        const imageResizeToolbar = document.createElement('div');
        imageResizeToolbar.className = 'quill-image-resize-toolbar';

        const imageResizeHandle = document.createElement('div');
        imageResizeHandle.className = 'quill-image-resize-handle';

        imageResizeOverlay.appendChild(imageResizeToolbar);
        imageResizeOverlay.appendChild(imageResizeHandle);
        quill.container.appendChild(imageResizeOverlay);

        let selectedImage = null;
        let resizeFrame = null;
        let resizeState = null;

        const normalizeEditorImage = (image) => {
            if (!image) {
                return;
            }

            image.style.maxWidth = '100%';
            image.style.height = 'auto';

            if (!image.style.width) {
                image.style.width = '100%';
            }
        };

        const syncEditorContent = () => {
            contentValue.value = quill.root.innerHTML;
        };

        const updateResizeOverlay = () => {
            if (!selectedImage || !selectedImage.isConnected) {
                imageResizeOverlay.style.display = 'none';
                return;
            }

            const containerRect = quill.container.getBoundingClientRect();
            const imageRect = selectedImage.getBoundingClientRect();
            const top = imageRect.top - containerRect.top + quill.container.scrollTop;
            const left = imageRect.left - containerRect.left + quill.container.scrollLeft;

            imageResizeOverlay.style.display = 'block';
            imageResizeOverlay.style.top = `${top}px`;
            imageResizeOverlay.style.left = `${left}px`;
            imageResizeOverlay.style.width = `${imageRect.width}px`;
            imageResizeOverlay.style.height = `${imageRect.height}px`;
        };

        const requestResizeOverlayUpdate = () => {
            if (resizeFrame) {
                cancelAnimationFrame(resizeFrame);
            }

            resizeFrame = requestAnimationFrame(updateResizeOverlay);
        };

        const clearSelectedImage = () => {
            selectedImage = null;
            imageResizeOverlay.style.display = 'none';
        };

        const selectImage = (image) => {
            selectedImage = image;
            normalizeEditorImage(selectedImage);
            requestResizeOverlayUpdate();
        };

        const applyImageWidth = (widthPercent) => {
            if (!selectedImage) {
                return;
            }

            selectedImage.style.width = `${widthPercent}%`;
            selectedImage.style.height = 'auto';
            syncEditorContent();
            requestResizeOverlayUpdate();
        };

        imageResizePresets.forEach((widthPercent) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'quill-image-resize-button';
            button.textContent = `${widthPercent}%`;
            button.addEventListener('click', () => applyImageWidth(widthPercent));
            imageResizeToolbar.appendChild(button);
        });

        const resetButton = document.createElement('button');
        resetButton.type = 'button';
        resetButton.className = 'quill-image-resize-button';
        resetButton.textContent = 'Auto';
        resetButton.addEventListener('click', () => {
            if (!selectedImage) {
                return;
            }

            selectedImage.style.width = '';
            selectedImage.style.height = 'auto';
            normalizeEditorImage(selectedImage);
            syncEditorContent();
            requestResizeOverlayUpdate();
        });
        imageResizeToolbar.appendChild(resetButton);

        imageResizeHandle.addEventListener('mousedown', (event) => {
            if (!selectedImage) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            const bounds = selectedImage.getBoundingClientRect();
            resizeState = {
                startX: event.clientX,
                startWidth: bounds.width,
                naturalWidth: selectedImage.naturalWidth || bounds.width
            };
        });

        document.addEventListener('mousemove', (event) => {
            if (!selectedImage || !resizeState) {
                return;
            }

            const deltaX = event.clientX - resizeState.startX;
            const nextWidth = Math.max(120, Math.min(resizeState.naturalWidth, resizeState.startWidth + deltaX));
            selectedImage.style.width = `${nextWidth}px`;
            selectedImage.style.height = 'auto';
            requestResizeOverlayUpdate();
        });

        document.addEventListener('mouseup', () => {
            if (!resizeState) {
                return;
            }

            resizeState = null;
            syncEditorContent();
            requestResizeOverlayUpdate();
        });

        quill.root.addEventListener('click', (event) => {
            if (event.target instanceof HTMLImageElement) {
                selectImage(event.target);
                return;
            }

            clearSelectedImage();
        });

        document.addEventListener('click', (event) => {
            if (!quill.container.contains(event.target) && !imageResizeOverlay.contains(event.target)) {
                clearSelectedImage();
            }
        });

        quill.root.querySelectorAll('img').forEach(normalizeEditorImage);
        quill.on('text-change', () => {
            quill.root.querySelectorAll('img').forEach(normalizeEditorImage);
            syncEditorContent();
            requestResizeOverlayUpdate();
        });

        quill.root.addEventListener('scroll', requestResizeOverlayUpdate);
        window.addEventListener('resize', requestResizeOverlayUpdate);

        // Set initial content
        if (contentValue.value.trim()) {
            quill.root.innerHTML = contentValue.value;
            quill.root.querySelectorAll('img').forEach(normalizeEditorImage);
        }

        // Sync Quill content to textarea before submit
        const forms = document.querySelectorAll('form');
        const form = forms[forms.length - 1]; // Get the last form (our blog form)
        
        form.addEventListener('submit', function(e) {
            // Get content from Quill editor
            const content = quill.root.innerHTML.trim();
            
            // Check if content is empty (various empty HTML patterns)
            if (!content || 
                content === '<p><br></p>' || 
                content === '<p></p>' ||
                content === '<p>&nbsp;</p>' ||
                content === '<br>' ||
                content === '') {
                e.preventDefault();
                alert(messageContentEmpty);
                return false;
            }
            
            // Sync content to hidden textarea BEFORE form submit
            contentValue.value = content;
        });

        // Handle image uploads in Quill
        const imageHandler = () => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = () => {
                const file = input.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('image', file);
                formData.append('type', 'blog');
                formData.append('temp_token', uploadToken);

                fetch(uploadImageUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', data.url);
                        setTimeout(() => {
                            const insertedImage = Array.from(quill.root.querySelectorAll('img')).find((image) => image.src === data.url);
                            if (insertedImage) {
                                normalizeEditorImage(insertedImage);
                                selectImage(insertedImage);
                                syncEditorContent();
                            }
                        }, 0);
                    } else {
                        alert(messageImageUploadFailed);
                    }
                })
                .catch(() => alert(messageImageUploadFailed));
            };
        };

        // Update toolbar image button handler
        quill.getModule('toolbar').addHandler('image', imageHandler);

        // Handle featured image upload with crop preview
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

        const applyFeaturedImage = (imageUrl) => {
            featuredImageValue.value = imageUrl;
            const img = document.createElement('img');
            img.id = 'featured-image-preview';
            img.src = imageUrl;
            img.alt = 'Featured';
            img.style.cssText = 'width: 100px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer;';
            img.addEventListener('click', () => {
                showCropModal(imageUrl);
            });
            const placeholder = document.getElementById('featured-image-placeholder');
            const existing = document.getElementById('featured-image-preview');
            if (existing) {
                existing.replaceWith(img);
            } else if (placeholder) {
                placeholder.replaceWith(img);
            }
            hideCropModal();
        };

        cropCloseBtn.addEventListener('click', hideCropModal);
        cropCancelBtn.addEventListener('click', hideCropModal);
        cropApplyBtn.addEventListener('click', () => {
            if (pendingImageUrl) {
                applyFeaturedImage(pendingImageUrl);
            }
        });

        // Close modal when clicking outside
        cropModal.addEventListener('click', (e) => {
            if (e.target === cropModal) {
                hideCropModal();
            }
        });

        featuredImageInput.addEventListener('change', () => {
            const file = featuredImageInput.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('type', 'blog');
            formData.append('temp_token', uploadToken);

            const loadingText = document.createElement('div');
            loadingText.className = 'text-muted small';
            loadingText.textContent = messageUploading;
            const placeholder = document.getElementById('featured-image-placeholder');
            const preview = document.getElementById('featured-image-preview');
            if (placeholder) placeholder.replaceWith(loadingText);
            if (preview) preview.replaceWith(loadingText);

            fetch(uploadImageUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    pendingImageUrl = data.url;
                    showCropModal(data.url);
                } else {
                    alert(messageImageUploadFailed);
                    loadingText.remove();
                }
            })
            .catch(() => alert(messageImageUploadFailed));
        });

        // Allow clicking on existing featured image to crop
        const existingPreview = document.getElementById('featured-image-preview');
        if (existingPreview) {
            existingPreview.style.cursor = 'pointer';
            existingPreview.addEventListener('click', () => {
                showCropModal(existingPreview.src);
            });
        }
    });
</script>
